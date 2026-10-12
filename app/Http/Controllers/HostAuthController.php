<?php

namespace App\Http\Controllers;

use App\Models\Host;
use App\Models\EventTypeConfig;
use App\Notifications\NewHostRegisteredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;

class HostAuthController extends Controller
{
    /**
     * Show the host registration form.
     */
    public function showRegister()
    {
        $eventTypes = EventTypeConfig::getAllEventTypes();
        return view('host.auth.register', compact('eventTypes'));
    }

    /**
     * Handle host registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:hosts',
            'phone' => 'required|string|max:20',
            'event_type' => 'required|in:wedding,birthday,kitchen_party,send_off,corporate',
            'number_of_guests' => 'required|integer|min:1|max:1000',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Calculate total cost based on number of guests (2000 per guest)
        $totalCost = $request->number_of_guests * 2000;
        
        $host = Host::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'event_type' => $request->event_type,
            'number_of_guests' => $request->number_of_guests,
            'total_cost' => $totalCost,
            'password' => $request->password, // Model will hash automatically due to 'hashed' cast
        ]);

        // Send email notification to admin emails
        try {
            $adminEmails = ['info@mycard.co.tz', 'deangarlus@gmail.com'];
            foreach ($adminEmails as $email) {
                Notification::route('mail', $email)
                    ->notify(new NewHostRegisteredNotification($host));
            }
        } catch (\Exception $e) {
            // Log error but don't fail registration
            \Log::error('Failed to send host registration notification: ' . $e->getMessage());
        }

        Auth::guard('host')->login($host);

        return redirect()->route('host.event-config')->with('success', 'Registration successful! Welcome to MyCard. Please configure your event to get started.');
    }

    /**
     * Show the host login form.
     */
    public function showLogin()
    {
        return view('host.auth.login');
    }

    /**
     * Handle host login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $login = trim($request->login);
        $password = $request->password;

        // Build phone normalization variants if numeric/phone characters are present
        $cleanedDigits = preg_replace('/[^0-9]/', '', $login);
        $phoneVariants = [$login];
        if (!empty($cleanedDigits)) {
            $phoneVariants[] = '+' . $cleanedDigits;
            $phoneVariants[] = $cleanedDigits;
            if (str_starts_with($cleanedDigits, '255') && strlen($cleanedDigits) >= 12) {
                $phoneVariants[] = '0' . substr($cleanedDigits, 3);
            } elseif (str_starts_with($cleanedDigits, '0') && strlen($cleanedDigits) >= 10) {
                $phoneVariants[] = '+255' . substr($cleanedDigits, 1);
                $phoneVariants[] = '255' . substr($cleanedDigits, 1);
            }
        }
        $phoneVariants = array_unique($phoneVariants);

        // Try to find host by email or phone variants
        $host = Host::where('email', $login)
            ->orWhereIn('phone', $phoneVariants)
            ->first();

        // Check if host exists and password is correct
        if ($host && Hash::check($password, $host->password)) {
            // Check if event is finished
            if ($host->hasFinishedEvent()) {
                return back()->withErrors([
                    'login' => 'This event has already been completed. Please create a new host account to start a new event.',
                ])->onlyInput('login');
            }

            // Check if account is active
            if (! $host->is_active) {
                return back()->withErrors([
                    'login' => 'Your account is currently inactive. Please contact support for assistance.',
                ])->onlyInput('login');
            }

            // Log in the host
            Auth::guard('host')->login($host, $request->boolean('remember'));
            $request->session()->regenerate();
            
            // Check if event is configured, redirect accordingly
            if ($host->hasConfiguredEvent()) {
                return redirect()->intended(route('host.dashboard'));
            }

            return redirect()->intended(route('host.event-config'));
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    /**
     * Handle host logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('host')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('host.login');
    }

    /**
     * Show the event configuration form.
     */
    public function showEventConfig()
    {
        $host = Auth::guard('host')->user();
        $eventTypeConfig = $host->getEventTypeConfig();
        
        return view('host.auth.event-config', compact('host', 'eventTypeConfig'));
    }

    /**
     * Handle event configuration.
     */
    public function configureEvent(Request $request)
    {
        $host = Auth::guard('host')->user();
        
        $eventTypeConfig = $host->getEventTypeConfig();
        
        // Start with existing event config data to preserve images
        $customData = $host->event_config_data ?? [];

        // Process custom fields
        if (!empty($eventTypeConfig['fields'])) {
            foreach ($eventTypeConfig['fields'] as $field) {
                $fieldName = $field['name'];
                
                if ($field['type'] === 'file') {
                    // Handle file uploads
                    if ($request->hasFile($fieldName)) {
                        $file = $request->file($fieldName);
                        
                        // Validate file
                        $request->validate([
                            $fieldName => ($field['required'] ?? false ? 'required|' : 'nullable|') . 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
                        ]);
                        
                        // Delete old file if exists
                        if (isset($customData[$fieldName]) && !empty($customData[$fieldName])) {
                            $oldFilePath = storage_path('app/public/event_uploads/' . $customData[$fieldName]);
                            if (file_exists($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        }
                        
                        // Generate unique filename
                        $filename = time() . '_' . $fieldName . '_' . $host->id . '.' . $file->getClientOriginalExtension();
                        
                        // Store file in public storage
                        $file->storeAs('public/event_uploads', $filename);
                        
                        // Store the filename in custom data
                        $customData[$fieldName] = $filename;
                    }
                    // If no new file uploaded, keep existing image (already in $customData from above)
                } else {
                    // Handle non-file fields
                    if ($request->has($fieldName)) {
                        $customData[$fieldName] = $request->input($fieldName);
                    }
                }
            }
        }

        // Extract event details from custom data
        $title = $customData['wedding_title'] ?? $customData['event_title'] ?? $host->event_type . ' Event';
        $description = $customData['description'] ?? 'Event created during configuration';
        $date = $customData['wedding_date'] ?? $customData['event_date'] ?? now()->addDays(30)->format('Y-m-d');
        $venue = $customData['venue'] ?? 'TBD';
        $venueAddress = $customData['venue_address'] ?? 'TBD';

        // Calculate total cost based on number of guests (2000 per guest)
        $totalCost = $host->number_of_guests * 2000;
        
        // Generate 5-character event password
        $eventPassword = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));
        
        // Check if event already exists (for updates)
        $existingEvent = \App\Models\Event::where('host_id', $host->id)->first();
        
        if ($existingEvent) {
            // Update existing event
            $existingEvent->update([
                'title' => $title,
                'description' => $description,
                'date' => $date,
                'venue' => $venue,
                'venue_address' => $venueAddress,
                'custom_data' => $customData, // Update with all image fields preserved
            ]);
            $event = $existingEvent;
        } else {
            // Create new event
            $event = \App\Models\Event::create([
                'host_id' => $host->id,
                'title' => $title,
                'description' => $description,
                'category' => $host->event_type,
                'date' => $date,
                'time' => '18:00', // Default time
                'venue' => $venue,
                'venue_address' => $venueAddress,
                'status' => 'active',
                'event_password' => $eventPassword,
                'guest_count' => $host->number_of_guests,
                'total_cost' => $totalCost,
                'custom_data' => $customData, // Store all image fields
            ]);
        }

        // Update host with event config data (preserves all images)
        $host->update([
            'event_config_data' => $customData, // Store all image fields here too
            'event_configured' => true,
            'total_cost' => $totalCost, // Update host's total cost
        ]);

        // Automatically initialize the invoice for this host & event
        $host->ensureInvoice();

        return redirect()->route('host.dashboard')->with('success', 'Event configured successfully! You can now proceed to review your invoice and complete payment.');
    }
}
