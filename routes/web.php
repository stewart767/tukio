<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SettingController;

// Language switching route
Route::get('/language/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

// Public routes
Route::get('/', [App\Http\Controllers\PageController::class, 'showHomepage'])->name('home');
Route::get('/event', [App\Http\Controllers\PageController::class, 'showHomepage']);
Route::get('/mycard', [App\Http\Controllers\PageController::class, 'showHomepage']);
Route::get('/public', [App\Http\Controllers\PageController::class, 'showHomepage']);
Route::get('/event/public', [App\Http\Controllers\PageController::class, 'showHomepage']);
Route::get('/homepage', [App\Http\Controllers\PageController::class, 'showHomepage'])->name('page.homepage');
// Fallback to static homepage if dynamic page doesn't exist
Route::get('/homepage-static', function () {
    return view('homepage-redesigned');
})->name('home.static');
Route::get('/home-alt', function () {
    return view('home.index');
})->name('home.alt');
Route::get('/test-responsive', function () {
    return view('test-responsive');
})->name('test.responsive');

// Public navigation pages
Route::get('/events', function () {
    $page = \App\Models\Page::where('slug', 'events')->where('is_active', true)->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.events');
})->name('events');

Route::get('/categories', function () {
    $milestones = \App\Models\Milestone::where('is_active', true)->orderBy('sort_order', 'asc')->get();
    return view('pages.categories', compact('milestones'));
})->name('categories');

Route::get('/venues', function () {
    $venues = \App\Models\Venue::where('is_active', true)->orderBy('sort_order', 'asc')->get();
    return view('pages.venues', compact('venues'));
})->name('venues');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Public Car Hire & Wedding Fleet routes
Route::get('/car-hire', [App\Http\Controllers\CarHireController::class, 'index'])->name('car-hire.index');
Route::get('/car-hire/{slug}', [App\Http\Controllers\CarHireController::class, 'show'])->name('car-hire.show');
Route::post('/car-hire/{slug}/book', [App\Http\Controllers\CarHireController::class, 'book'])->name('car-hire.book');
Route::get('/car-hire-booking/{reference}', [App\Http\Controllers\CarHireController::class, 'confirmed'])->name('car-hire.confirmed');

// Public Templates routes
Route::get('/templates', [App\Http\Controllers\TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}', [App\Http\Controllers\TemplateController::class, 'show'])->name('templates.show');

// Public pages routes
Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');
Route::get('/invitation/{slug}', [App\Http\Controllers\InvitationPublicController::class, 'show'])->name('invitation.show');
Route::post('/invitation/{slug}/rsvp', [App\Http\Controllers\InvitationPublicController::class, 'rsvp'])->middleware('throttle:20,1')->name('invitation.rsvp');
Route::get('/invitation/{slug}/pdf', [App\Http\Controllers\InvitationPublicController::class, 'pdf'])->name('invitation.pdf');

// Public Track & Verification route (supa.ac.tz style)
Route::match(['get', 'post'], '/track', function (\Illuminate\Http\Request $request) {
    $query = trim($request->input('query', ''));
    if ($request->isMethod('post') || $query !== '') {
        if (!empty($query)) {
            // 1. Check Guest by invitation_code
            $guest = \App\Models\Guest::where('invitation_code', $query)->first();
            if ($guest && $guest->invitation) {
                return redirect()->route('invitation.show', $guest->invitation->slug);
            }
            if ($guest && $guest->event) {
                return redirect()->route('invitation.show', $guest->invitation_code);
            }

            // 2. Check Invitation by slug
            $invitation = \App\Models\Invitation::where('slug', $query)->first();
            if ($invitation) {
                return redirect()->route('invitation.show', $invitation->slug);
            }

            // 3. Check CarBooking by booking_reference
            $booking = \App\Models\CarBooking::where('booking_reference', $query)->first();
            if ($booking) {
                return redirect()->route('car-hire.confirmed', $booking->booking_reference);
            }

            // 4. Check Guest by phone
            $phoneClean = preg_replace('/[^0-9]/', '', $query);
            if (strlen($phoneClean) >= 7) {
                $guestByPhone = \App\Models\Guest::where('phone', 'like', "%{$phoneClean}%")->latest()->first();
                if ($guestByPhone && $guestByPhone->invitation) {
                    return redirect()->route('invitation.show', $guestByPhone->invitation->slug);
                }
                if ($guestByPhone) {
                    return redirect()->route('invitation.show', $guestByPhone->invitation_code);
                }

                $bookingByPhone = \App\Models\CarBooking::where('customer_phone', 'like', "%{$phoneClean}%")->latest()->first();
                if ($bookingByPhone) {
                    return redirect()->route('car-hire.confirmed', $bookingByPhone->booking_reference);
                }
            }

            return back()->with('track_error', 'No active RSVP pass or vehicle booking found matching "' . e($query) . '". Please verify your code or registered phone number.');
        }
    }
    return view('pages.track');
})->middleware('throttle:30,1')->name('track');

Route::get('/track-application', function () {
    return redirect()->route('track');
});


// Admin/Staff Authentication routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Superadmin Authentication routes
Route::get('/admin/login', [App\Http\Controllers\AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AuthController::class, 'adminLogin'])->middleware('throttle:10,1');

// Host Authentication routes
Route::prefix('host')->name('host.')->group(function () {
    Route::get('/login', [App\Http\Controllers\HostAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\HostAuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [App\Http\Controllers\HostAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\HostAuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('/logout', [App\Http\Controllers\HostAuthController::class, 'logout'])->name('logout');
    
    // Host Dashboard and Event Configuration
    Route::middleware(['auth:host'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HostDashboardController::class, 'index'])->name('dashboard');
        Route::get('/event-config', [App\Http\Controllers\HostAuthController::class, 'showEventConfig'])->name('event-config');
        Route::post('/configure-event', [App\Http\Controllers\HostAuthController::class, 'configureEvent'])->name('configure-event');
        
        // Host Panel Routes (require event configuration)
        Route::middleware(['event.configured'])->group(function () {
            Route::get('/event-details', [App\Http\Controllers\Host\EventDetailsController::class, 'index'])->name('event-details');
            
            // Event Management Routes
            Route::get('/events', [App\Http\Controllers\Host\EventController::class, 'index'])->name('events.index');
            
            // Guest Management Routes
            Route::resource('guests', App\Http\Controllers\Host\GuestController::class);
            Route::post('/guests/import', [App\Http\Controllers\Host\GuestController::class, 'import'])->name('guests.import');
            Route::get('/guests/template/download', [App\Http\Controllers\Host\GuestController::class, 'template'])->name('guests.template');
            
            Route::get('/invitations', [App\Http\Controllers\Host\InvitationController::class, 'index'])->name('invitations.index');
            Route::get('/invitations/create', [App\Http\Controllers\Host\InvitationController::class, 'create'])->name('invitations.create');
            Route::post('/invitations', [App\Http\Controllers\Host\InvitationController::class, 'store'])->name('invitations.store');
            Route::get('/invitations/{invitation}/download', [App\Http\Controllers\Host\InvitationController::class, 'download'])->name('invitations.download');
            Route::get('/invitations/{invitation}/preview', [App\Http\Controllers\Host\InvitationController::class, 'preview'])->name('invitations.preview');
            Route::delete('/invitations/{invitation}', [App\Http\Controllers\Host\InvitationController::class, 'destroy'])->name('invitations.destroy');
            
            // WhatsApp invitation routes
            Route::post('/invitations/{invitation}/resend', [App\Http\Controllers\Host\InvitationController::class, 'resend'])->name('invitations.resend');
            Route::get('/whatsapp/test', [App\Http\Controllers\Host\InvitationController::class, 'testWhatsApp'])->name('whatsapp.test');
            Route::post('/whatsapp/send-invitations', [App\Http\Controllers\WhatsAppInvitationController::class, 'sendDirectInvitation'])->name('whatsapp.send-invitations');
            Route::get('/whatsapp/test-connection', [App\Http\Controllers\WhatsAppInvitationController::class, 'testConnection'])->name('whatsapp.test-connection');
            Route::get('/whatsapp/stats', [App\Http\Controllers\WhatsAppInvitationController::class, 'getStats'])->name('whatsapp.stats');
            Route::get('/reports', [App\Http\Controllers\Host\ReportController::class, 'index'])->name('reports.index');

            // Host Payments & Billing routes
            Route::get('/payments', [App\Http\Controllers\Host\PaymentController::class, 'index'])->name('payments.index');
            Route::post('/payments/proof', [App\Http\Controllers\Host\PaymentController::class, 'submitProof'])->name('payments.submit-proof');
            Route::get('/payments/invoice', [App\Http\Controllers\Host\PaymentController::class, 'invoice'])->name('payments.invoice');

            // Host Car Hire & Fleet routes
            Route::get('/car-hire', [App\Http\Controllers\Host\CarHireController::class, 'index'])->name('car-hire.index');
            Route::post('/car-hire', [App\Http\Controllers\Host\CarHireController::class, 'store'])->name('car-hire.store');
            Route::get('/car-hire/{id}', [App\Http\Controllers\Host\CarHireController::class, 'show'])->name('car-hire.show');
        });
    });
});

// WhatsApp webhook routes (no auth required)
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::get('/whatsapp/verify', [App\Http\Controllers\WhatsAppWebhookController::class, 'verify'])->name('whatsapp.verify');
    Route::post('/whatsapp/webhook', [App\Http\Controllers\WhatsAppWebhookController::class, 'webhook'])->name('whatsapp.webhook');
    Route::get('/whatsapp/delivery-stats/{event_id}', [App\Http\Controllers\WhatsAppWebhookController::class, 'getDeliveryStats'])->name('whatsapp.delivery-stats');
});

// Beem SMS & WhatsApp Delivery Report (DLR) webhook routes
Route::prefix('beem')->name('beem.')->group(function () {
    Route::get('/dlr', function () {
        return response('OK', 200);
    })->name('dlr.verify');
    
    Route::post('/dlr', function () {
        $payload = request()->all();
        
        \Illuminate\Support\Facades\Log::info('Beem DLR received', [
            'payload' => $payload,
            'timestamp' => now()->toISOString()
        ]);
        
        $status = strtoupper((string) ($payload['status'] ?? ''));
        $phoneNumber = preg_replace('/[^0-9]/', '', (string) ($payload['destination'] ?? $payload['dest_addr'] ?? ''));
        $recipientId = $payload['recipient_id'] ?? null;

        $invitation = null;
        if ($recipientId && is_numeric($recipientId)) {
            $invitation = \App\Models\Invitation::find((int) $recipientId);
        }
        if (!$invitation && strlen($phoneNumber) >= 9) {
            $guest = \App\Models\Guest::where('phone', 'like', '%' . substr($phoneNumber, -9))->latest()->first();
            $invitation = $guest?->invitation;
        }

        if ($invitation) {
            if (in_array($status, ['DELIVERED', 'DELIVRD', 'READ'], true)) {
                $invitation->update([
                    'status' => 'delivered',
                    'delivered_at' => $invitation->delivered_at ?: now(),
                ]);
            } elseif (in_array($status, ['FAILED', 'UNDELIV', 'REJECTED', 'REJECTD'], true)) {
                $invitation->update([
                    'status' => 'failed',
                ]);
            }
        }
        
        return response()->json(['status' => 'ok']);
    })->middleware('throttle:60,1')->name('dlr.webhook');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes (accessible to all authenticated admins)
    Route::middleware(['role:super_admin,event_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
    
    // Admin routes
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::get('templates/{template}/preview', [App\Http\Controllers\Admin\TemplateController::class, 'preview'])->name('templates.preview');
        Route::post('templates/{template}/duplicate', [App\Http\Controllers\Admin\TemplateController::class, 'duplicate'])->name('templates.duplicate');
        Route::post('templates/sync', [App\Http\Controllers\Admin\TemplateController::class, 'sync'])->name('templates.sync');
        Route::patch('templates/{template}/toggle-active', [App\Http\Controllers\Admin\TemplateController::class, 'toggleActive'])->name('templates.toggle-active');
        Route::post('templates/upload-ai', [App\Http\Controllers\Admin\TemplateController::class, 'uploadAiTemplate'])->name('templates.upload-ai');
        Route::get('templates/{template}/editor', [App\Http\Controllers\Admin\TemplateController::class, 'editor'])->name('templates.editor');
        Route::post('templates/{template}/editor', [App\Http\Controllers\Admin\TemplateController::class, 'updateEditorFields'])->name('templates.editor.update');
        Route::post('templates/{template}/reanalyze', [App\Http\Controllers\Admin\TemplateController::class, 'reanalyze'])->name('templates.reanalyze');
        Route::post('templates/{template}/approve', [App\Http\Controllers\Admin\TemplateController::class, 'approve'])->name('templates.approve');
        Route::resource('templates', App\Http\Controllers\Admin\TemplateController::class);
        Route::patch('hosts/{host}/finish', [App\Http\Controllers\Admin\HostController::class, 'finish'])->name('hosts.finish');
        Route::resource('hosts', App\Http\Controllers\Admin\HostController::class)->except(['show']);
        Route::get('hosts/{host}/assign-staff', [App\Http\Controllers\Admin\HostController::class, 'showAssignStaff'])->name('hosts.assign-staff');
        Route::post('hosts/{host}/assign-staff', [App\Http\Controllers\Admin\HostController::class, 'assignStaff'])->name('hosts.assign-staff.store');
        
        // Host Payments & Invoices Verification
        Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{invoice}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('payments/{invoice}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
        Route::post('payments/{invoice}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject');

        Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
        Route::get('pages/{page}/edit', [App\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');
        Route::patch('pages/{page}', [App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');

        // Fleet & Car Hire Management
        Route::resource('vehicles', App\Http\Controllers\Admin\VehicleController::class);
        Route::patch('vehicles/{vehicle}/toggle-active', [App\Http\Controllers\Admin\VehicleController::class, 'toggleActive'])->name('vehicles.toggle-active');
        Route::get('car-bookings', [App\Http\Controllers\Admin\CarBookingController::class, 'index'])->name('car-bookings.index');
        Route::get('car-bookings/{booking}', [App\Http\Controllers\Admin\CarBookingController::class, 'show'])->name('car-bookings.show');
        Route::patch('car-bookings/{booking}/status', [App\Http\Controllers\Admin\CarBookingController::class, 'updateStatus'])->name('car-bookings.update-status');
        Route::delete('car-bookings/{booking}', [App\Http\Controllers\Admin\CarBookingController::class, 'destroy'])->name('car-bookings.destroy');

        // Milestones & Occasions Management
        Route::resource('milestones', App\Http\Controllers\Admin\MilestoneController::class);
        Route::patch('milestones/{milestone}/toggle-active', [App\Http\Controllers\Admin\MilestoneController::class, 'toggleActive'])->name('milestones.toggle-active');

        // Venues & Spaces Management
        Route::resource('venues', App\Http\Controllers\Admin\VenueController::class);
        Route::patch('venues/{venue}/toggle-active', [App\Http\Controllers\Admin\VenueController::class, 'toggleActive'])->name('venues.toggle-active');
        Route::patch('venues/{venue}/toggle-featured', [App\Http\Controllers\Admin\VenueController::class, 'toggleFeatured'])->name('venues.toggle-featured');

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::post('/system', 'updateSystem')->name('system');
            Route::post('/email', 'updateEmail')->name('email');
            Route::post('/payment', 'updatePayment')->name('payment');
            Route::post('/sms', 'updateSms')->name('sms');
            Route::post('/sms/test', 'testSms')->name('sms.test');
            Route::post('/whatsapp', 'updateWhatsapp')->name('whatsapp');
            Route::post('/contact-footer', 'updateContactAndFooter')->name('contact-footer');
            Route::post('/backups/run', 'createBackup')->name('backup.create');
            Route::post('/backups/restore', 'restoreBackup')->name('backup.restore');
            Route::get('/backups/{filename}', 'downloadBackup')->name('backup.download');
        });
    });
    
    
    // Scanner & Attendance routes (accessible to staff, super_admin, and event_admin)
    Route::middleware(['role:staff,super_admin,event_admin'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('scanner', [App\Http\Controllers\Staff\ScannerController::class, 'index'])->name('scanner');
        Route::post('scan', [App\Http\Controllers\Staff\ScannerController::class, 'scan'])->middleware('throttle:60,1')->name('scan');
        Route::get('attendance', [App\Http\Controllers\Staff\ScannerController::class, 'attendance'])->name('attendance');
    });

    // Scanner route alias for Admin
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('scanner', [App\Http\Controllers\Staff\ScannerController::class, 'index'])->name('scanner');
    });
});

// Direct storage fallback route for local/Apache Windows environments (hardened against path traversal)
Route::get('storage/{path}', function ($path) {
    if (str_contains($path, '..')) {
        abort(403);
    }
    $baseDir = realpath(storage_path('app/public'));
    $filePath = realpath(storage_path('app/public/' . ltrim($path, '/\\')));
    if (!$baseDir || !$filePath || !str_starts_with($filePath, $baseDir) || !is_file($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.local');
