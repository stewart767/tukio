<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Event;

class MessageTemplateService
{
    /**
     * Message 1: Ujumbe wa Awali (Taarifa ya Tukio, Michango & RSVP)
     */
    public static function getWelcomeAndPledgeMessage(Guest $guest, Event $event, string $channel = 'whatsapp'): string
    {
        $guestName = $guest->full_name;
        $eventTitle = $event->title;
        $eventDate = $event->date ? $event->date->format('d/m/Y') : 'Tarehe Itathibitishwa';
        $eventTime = $event->time ?: 'Saa 1:00 Usiku';
        $venue = $event->venue ?: 'Ukumbi Utatangazwa';
        $lipaNamba = $event->lipa_namba ?: 'Inakuja punde';
        $accountName = $event->account_name ?: $eventTitle;
        $treasurerPhone = $event->treasurer_phone ?: '';
        $appUrl = config('app.url', 'https://tukio.co.tz');
        $invitationSlug = $guest->invitation?->slug ?: $guest->invitation_code;
        $rsvpUrl = "{$appUrl}/invitation/{$invitationSlug}";

        if ($channel === 'sms') {
            return "Habari {$guestName}, umealikwa kwenye {$eventTitle} ({$eventDate}, {$venue}). Mchango unapokelewa kupitia Lipa Namba: {$lipaNamba} ({$accountName}) au Simu: {$treasurerPhone}. Kuthibitisha kuhudhuria, jibu: 1=Nitafika, 2=Sitoweza. Maelezo zaidi: {$rsvpUrl}";
        }

        // WhatsApp Version
        return "✨ *TAARIFA YA SHANGWE NA UPENDO* ✨\n\n" .
               "Habari Ndugu *{$guestName}*,\n\n" .
               "Kwa furaha kubwa, tunapenda kukutaarifu kuhusu maandalizi ya sherehe ya:\n\n" .
               "💍 *{$eventTitle}*\n" .
               "📅 *Tarehe:* {$eventDate}\n" .
               "⏰ *Muda:* {$eventTime}\n" .
               "📍 *Ukumbi:* {$venue}\n\n" .
               "Ushiriki na maombi yako ni wa thamani kubwa kwetu. Unaweza kuwasilisha mchango wako kupitia:\n" .
               "💳 *LIPA NAMBA:* `{$lipaNamba}`\n" .
               "🏷️ *Jina la Akaunti:* {$accountName}\n" .
               ($treasurerPhone ? "📱 *Mweka Hazina:* `{$treasurerPhone}`\n" : "") . "\n" .
               "Tafadhali thibitisha ushiriki wako kupitia link hii:\n" .
               "👉 {$rsvpUrl}\n\n" .
               "_Karibu sana tujumuike pamoja!_ 🙏";
    }

    /**
     * Message 2: Kadi Rasmi ya Mwaliko (Baada ya Kulipa / Kuthibitishwa)
     */
    public static function getOfficialCardMessage(Guest $guest, Event $event, string $channel = 'whatsapp'): string
    {
        $guestName = $guest->full_name;
        $eventTitle = $event->title;
        $eventTime = $event->time ?: 'Saa 1:00 Usiku';
        $venue = $event->venue ?: 'Ukumbini';
        $table = $guest->table_number ?: 'Meza ya Kawaida';
        $pax = $guest->pax > 1 ? "Watu {$guest->pax} (Double Pass)" : "Mtu 1 (Single Pass)";
        $pinCode = $guest->pin_code ?: '1234';
        $appUrl = config('app.url', 'https://tukio.co.tz');
        $invitationSlug = $guest->invitation?->slug ?: $guest->invitation_code;
        $cardUrl = "{$appUrl}/invitation/{$invitationSlug}";

        if ($channel === 'sms') {
            return "Ahsante {$guestName} kwa mchango wako wa {$eventTitle}. Kadi yako iko tayari! Ukumbi: {$venue}, Muda: {$eventTime}. Meza: {$table}, {$pax}. PIN yako ya mlangoni ni: {$pinCode}. Fungua kadi hapa: {$cardUrl}";
        }

        // WhatsApp Version
        return "🎟️ *KADI RASMI YA MWALIKO (INVITATION PASS)* 🎟️\n\n" .
               "Habari Ndugu *{$guestName}*,\n\n" .
               "Uongozi wa Kamati ya *{$eventTitle}* unathibitisha kupokea mchango wako na kukukabidhi kadi yako rasmi ya mwaliko:\n\n" .
               "🏛️ *Ukumbi:* {$venue}\n" .
               "⏰ *Muda wa Kuingia:* {$eventTime}\n" .
               "🪑 *Meza Yako:* *{$table}*\n" .
               "👥 *Nafasi:* {$pax}\n" .
               "🔢 *PIN ya Mlangoni (Kitochi):* *{$pinCode}*\n\n" .
               "📌 _Tafadhali hifadhi picha hii ya kadi au namba yako ya PIN. Utaionyesha kwa wahudumu getini siku ya sherehe._\n\n" .
               "Fungua kadi mtandaoni na ramani ya ukumbi:\n" .
               "👉 {$cardUrl}\n\n" .
               "_Tunakusubiri kwa furaha kubwa!_ ✨";
    }

    /**
     * Message 3: Ujumbe wa Shukrani Baada ya Harusi na Kujitangaza
     */
    public static function getThankYouMessage(Guest $guest, Event $event, string $channel = 'whatsapp'): string
    {
        $guestName = $guest->full_name;
        $eventTitle = $event->title;
        $appUrl = config('app.url', 'https://tukio.co.tz');

        if ($channel === 'sms') {
            return "Familia na uongozi wa {$eventTitle} wanatoa shukrani za dhati kwako {$guestName} kwa kujumuika nasi na kufanikisha sherehe yetu. Mungu akubariki sana! (Je una sherehe inakuja? Pata kadi za kidijitali na scanner kutoka Tukio: {$appUrl})";
        }

        // WhatsApp Version
        return "💐 *SHUKRANI ZA DHATI KUTOKA KWA FAMILIA NA MAHARUSI* 💐\n\n" .
               "Mpendwa *{$guestName}*,\n\n" .
               "Kwa niaba ya uongozi wa familia na sherehe ya *{$eventTitle}*, tunatoa shukrani zetu za dhati kwako kwa kujumuika nasi, upendo wako, maombi, na michango iliyofanikisha sherehe yetu kuwa ya kipekee na kumbukumbu isiyosahaulika.\n\n" .
               "Mwenyezi Mungu akubariki na kukurudishia tele pale ulipopunguza kwa ajili yetu! 🙏\n\n" .
               "━━━━━━━━━━━━━━━━━━━━━━\n" .
               "✨ *JE, UNA HARUSI, SEND-OFF AU SHEREHE INAKUJA?* ✨\n" .
               "_Fanya sherehe yako iwe ya kisasa! Pata kadi za kidijitali za WhatsApp, SMS za PIN, na Scanner ya kisasa ya getini kutoka *Tukio*._\n" .
               "🌐 Tembelea: {$appUrl}\n" .
               "📲 Wasiliana nasi kwa huduma bora!";
    }
}
