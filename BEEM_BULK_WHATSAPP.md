### Beem WhatsApp Bulk Sending (Invitations) – Implementation Guide

This guide shows how to integrate Beem’s APIs in this Laravel app to send bulk WhatsApp invitation messages reliably, including 24-hour window rules, template-based sending, delivery reports, and practical examples.

---

## 1) Pick the right Beem API for your use case

- **Within 24h of last user interaction (session active)**: You may use the Moja Chat API to send free-form text/media to “active sessions”. Endpoint: `https://apichatcore.beem.africa/v1/chatapi`.
- **Initiating conversations or sending after 24h**: You must use an approved WhatsApp Template and send via the Broadcast Template API. Endpoint: `https://apibroadcast.beem.africa/v1/broadcast/template/api-send`.

For bulk invitations, you usually want the Template API to ensure delivery even when the guest hasn’t messaged you in the last 24 hours.

References (from `beem_whatsapp.md`):
- Templates list: `GET https://apichatcore.beem.africa/v1/message-templates/list`
- Send template: `POST https://apibroadcast.beem.africa/v1/broadcast/template/api-send`

---

## 2) Prerequisites

1. Beem account and active WhatsApp channel
2. Approved WhatsApp template(s) for your invitation content
3. API credentials (API Key and Secret Key)
4. Your WhatsApp business number (sender) configured on Beem

---

## 3) Environment and config

Add these to `.env` (do not commit real secrets):

```bash
BEEM_API_KEY=your_api_key
BEEM_SECRET_KEY=your_secret_key
BEEM_WHATSAPP_FROM=2557xxxxxxx   # your Beem WhatsApp number (no +)
BEEM_BROADCAST_BASE=https://apibroadcast.beem.africa
BEEM_CHAT_BASE=https://apichatcore.beem.africa
```

Register in `config/services.php`:

```php
'beem' => [
    'api_key' => env('BEEM_API_KEY'),
    'secret_key' => env('BEEM_SECRET_KEY'),
    'from' => env('BEEM_WHATSAPP_FROM'),
    'broadcast_base' => env('BEEM_BROADCAST_BASE', 'https://apibroadcast.beem.africa'),
    'chat_base' => env('BEEM_CHAT_BASE', 'https://apichatcore.beem.africa'),
],
```

---

## 4) Service: Beem WhatsApp bulk sender

Create a dedicated service to send template messages in bulk. This uses HTTP Basic Auth with `api_key:secret_key` and posts JSON to Beem.

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BeemWhatsAppService
{
    private string $apiKey;
    private string $secretKey;
    private string $from;
    private string $broadcastBase;
    private string $chatBase;

    public function __construct()
    {
        $cfg = config('services.beem');
        $this->apiKey = (string)($cfg['api_key'] ?? '');
        $this->secretKey = (string)($cfg['secret_key'] ?? '');
        $this->from = (string)($cfg['from'] ?? '');
        $this->broadcastBase = (string)($cfg['broadcast_base'] ?? 'https://apibroadcast.beem.africa');
        $this->chatBase = (string)($cfg['chat_base'] ?? 'https://apichatcore.beem.africa');
    }

    public function sendTemplateBulk(array $destinations, int $templateId, ?string $mediaUrl = null): array
    {
        // $destinations: [ [ 'phoneNumber' => '2557xxxxxxx', 'params' => ['John', 'Sat 4PM'] ], ... ]
        $url = rtrim($this->broadcastBase, '/') . '/v1/broadcast/template/api-send';

        $payload = [
            'from_addr' => $this->from,
            'destination_addr' => array_values($destinations),
            'channel' => 'whatsapp',
            'messageTemplateData' => [ 'id' => $templateId ],
        ];
        if ($mediaUrl) {
            $payload['content'] = [ 'mediaUrl' => $mediaUrl ];
        }

        $res = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if ($res->successful()) {
            $json = $res->json();
            Log::info('Beem template bulk send success', ['response' => $json]);
            return ['success' => true, 'response' => $json];
        }

        Log::error('Beem template bulk send failed', [
            'status' => $res->status(),
            'body' => $res->body(),
        ]);
        return ['success' => false, 'status' => $res->status(), 'error' => $res->body()];
    }

    public function listTemplates(array $filters = []): array
    {
        $url = rtrim($this->chatBase, '/') . '/v1/message-templates/list';
        $res = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
            'Content-Type' => 'application/json',
        ])->get($url, $filters);

        return $res->json();
    }
}
```

Notes:
- The Template API returns validation stats, pricing breakdown by country, and a `jobId` to track the campaign.
- Make sure numbers are in international format without `+` (e.g., `2557xxxxxxx`).

---

## 5) Build your invitation payload

Assuming you have `events` and `guests` tables, fetch all active guests for an event and map to template params (e.g., guest name, date, venue, invitation link).

Example helper that builds destinations for template placeholders `{{1}}` = name, `{{2}}` = event date, `{{3}}` = RSVP link:

```php
use App\Models\Event;

function buildInvitationDestinations(Event $event): array {
    $link = url('/invite/' . $event->id); // your public invitation link

    return $event->guests()
        ->where('active', true)
        ->pluck('phone_number', 'full_name')
        ->map(function ($phone, $name) use ($event, $link) {
            return [
                'phoneNumber' => preg_replace('/[^0-9]/', '', $phone),
                'params' => [
                    $name,
                    optional($event->start_time)->format('D, j M g:i A'),
                    $link,
                ],
            ];
        })
        ->values()
        ->all();
}
```

Match `params` order with your approved template body placeholders.

---

## 6) Console command to trigger bulk send

Create a command so staff can trigger a send per event and template id. Example usage:

```bash
php artisan invitations:whatsapp:send 123 --template=10913
```

Skeleton command:

```php
<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\BeemWhatsAppService;
use Illuminate\Console\Command;

class SendEventWhatsAppInvitations extends Command
{
    protected $signature = 'invitations:whatsapp:send {eventId} {--template=} {--media=}';
    protected $description = 'Send WhatsApp invitations via Beem Template API';

    public function handle(BeemWhatsAppService $beem)
    {
        $event = Event::with('guests')->findOrFail((int)$this->argument('eventId'));
        $templateId = (int)$this->option('template');
        $mediaUrl = $this->option('media') ?: null; // if your template includes media

        if (!$templateId) {
            $this->error('Missing --template=ID');
            return 1;
        }

        $destinations = buildInvitationDestinations($event);
        if (empty($destinations)) {
            $this->warn('No active guests to send to.');
            return 0;
        }

        $result = $beem->sendTemplateBulk($destinations, $templateId, $mediaUrl);
        if ($result['success'] ?? false) {
            $this->info('Bulk send queued.');
            $this->line(json_encode($result['response'], JSON_PRETTY_PRINT));
            return 0;
        }

        $this->error('Bulk send failed: ' . ($result['error'] ?? 'unknown'));
        return 1;
    }
}
```

Optional: log each attempt to your `invitations` or `guests` table for audit.

---

## 7) Delivery reports (DLRs) callback

Beem can call your webhook with delivery updates. You must first configure the callback URL in Beem’s portal (API Setup). Beem will perform a GET to verify 200 OK, then POST DLRs.

Routes:

```php
// routes/web.php
use Illuminate\Support\Facades\Log;

Route::get('/beem/dlr', fn() => response('OK', 200));

Route::post('/beem/dlr', function() {
    $payload = request()->all();
    Log::info('Beem DLR', $payload);
    // Optionally map message_id/phone to your guest/invitation and update status
    return response()->json(['status' => 'ok']);
});
```

Sample successful DLR (from docs):

```json
{
  "broadcast_id": "broadcastid_123123",
  "message_id": "msgid_123123",
  "status": "read",
  "destination": "255******581",
  "message": "this is the message sent",
  "timestamp": "2023-06-26 02:31:29"
}
```

---

## 8) Testing and examples

List templates to find your `messageTemplateData.id`:

```bash
curl -G "https://apichatcore.beem.africa/v1/message-templates/list" \
  -H "Authorization: Basic $(echo -n "$BEEM_API_KEY:$BEEM_SECRET_KEY" | base64)" \
  -H "Content-Type: application/json" \
  --data-urlencode "category=MARKETING" \
  --data-urlencode "status=approved"
```

Send a small test to two recipients:

```bash
curl -X POST "https://apibroadcast.beem.africa/v1/broadcast/template/api-send" \
  -H "Authorization: Basic $(echo -n "$BEEM_API_KEY:$BEEM_SECRET_KEY" | base64)" \
  -H "Content-Type: application/json" \
  -d '{
    "from_addr": "2557xxxxxxx",
    "destination_addr": [
      {"phoneNumber": "2557xxxx001", "params": ["John", "Sat 4PM", "https://your.link/a"]},
      {"phoneNumber": "2557xxxx002", "params": ["Mary", "Sat 4PM", "https://your.link/a"]}
    ],
    "channel": "whatsapp",
    "messageTemplateData": { "id": 10913 }
  }'
```

---

## 9) Common errors and fixes

- **401 Unauthorized**: Wrong API key/secret or missing Authorization header. Verify Base64 header and env values.
- **Invalid template id**: Ensure the id is from Beem template list endpoint and that the template is approved and enabled.
- **Invalid numbers**: Use international format without `+`, no spaces. Check `validation.invalidNumbers` in response.
- **24-hour window issues**: Free-text via Chat API works only for active sessions. Use template API to initiate conversations.

---

## 10) Mapping to this app

- Keep existing `App\Services\WhatsAppService` for Meta Graph API use-cases. Use `App\Services\BeemWhatsAppService` for Beem template-based bulk sends.
- Trigger bulk sends from an admin UI or the console command. For invitation cards, build `params` as: `{{1}}` guest name, `{{2}}` event date/time, `{{3}}` RSVP/link (align with your approved template).
- Store send results and DLRs against `invitations` or `guests` for traceability.

That’s it. With the service, command, and callback above, you can reliably send bulk WhatsApp invitation messages via Beem.



