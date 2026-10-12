<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Models\Page;
use App\Services\SystemSettingsService;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     */
    public function show()
    {
        // Always use the contact view with landing layout for consistency
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Hydrate mail configuration from database settings if available
            $settingsService = app(SystemSettingsService::class);
            $settingsService->hydrateRuntimeConfig();

            // Get system settings for email
            $settings = Setting::where('key', 'system')->first();
            $systemSettings = $settings ? json_decode($settings->value, true) : [];
            $contactEmail = $systemSettings['email'] ?? config('mail.from.address', 'info@tukio360.co.tz');
            $brandName = $systemSettings['name'] ?? 'Tukio 360';

            // Validate contact email
            if (empty($contactEmail) || !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
                Log::warning('Contact form: Invalid recipient email', ['email' => $contactEmail]);
                $contactEmail = config('mail.from.address', 'info@tukio360.co.tz');
            }

            // Prepare email data
            $emailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject ?? 'Contact Form Submission',
                'message' => $request->message,
                'brandName' => $brandName,
            ];

            // Check mail configuration and normalize mailer name (must be lowercase)
            $mailer = strtolower(config('mail.default', 'log'));
            
            // Ensure we're using a valid mailer
            $validMailers = ['smtp', 'sendmail', 'mailgun', 'ses', 'ses-v2', 'postmark', 'resend', 'log', 'array'];
            if (!in_array($mailer, $validMailers)) {
                Log::warning('Contact form: Invalid mailer, falling back to log', ['mailer' => $mailer]);
                $mailer = 'log';
                Config::set('mail.default', 'log');
            }
            
            Log::info('Contact form: Attempting to send email', [
                'mailer' => $mailer,
                'to' => $contactEmail,
            ]);

            // Send email notification
            Mail::mailer($mailer)->send('emails.contact', $emailData, function ($message) use ($contactEmail, $request, $brandName) {
                $message->to($contactEmail)
                    ->subject('New Contact Form Submission - ' . ($request->subject ?? 'No Subject'))
                    ->replyTo($request->email, $request->name);
            });

            // Log the contact submission
            Log::info('Contact form submitted successfully', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'mailer' => $mailer,
            ]);

            return back()->with('success', 'Thank you for contacting us! We will get back to you as soon as possible.');

        } catch (\Exception $e) {
            // Check if it's a mail transport error
            $isTransportError = str_contains($e->getMessage(), 'Connection') || 
                               str_contains($e->getMessage(), 'SMTP') ||
                               str_contains($e->getMessage(), 'transport') ||
                               str_contains($e->getMessage(), 'Could not instantiate mailer');
            
            if ($isTransportError) {
                Log::error('Contact form: Mail transport error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'mailer' => config('mail.default'),
                    'request' => $request->except(['message']),
                ]);

                return back()->with('error', 'Sorry, there was an error sending your message. Please check your email configuration or contact us directly.')
                    ->withInput();
            }
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['message']), // Don't log the full message
            ]);

            return back()->with('error', 'Sorry, there was an error sending your message. Please try again later or contact us directly.')
                ->withInput();
        }
    }
}
