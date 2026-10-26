<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuestController extends Controller
{
    /**
     * Normalize phone number to 255 format
     */
    private function normalizePhone($phone)
    {
        // Convert to string and remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);
        
        // Remove leading zeros
        $phone = ltrim($phone, '0');
        
        // If it starts with 255, return as is
        if (str_starts_with($phone, '255')) {
            return $phone;
        }
        
        // If it doesn't start with 255, add the prefix
        return '255' . $phone;
    }

    /**
     * Generate a unique 5-digit numeric code for guest
     */
    public function generateUniqueGuestCode($eventId = null)
    {
        $maxAttempts = 100; // Prevent infinite loop
        $attempts = 0;
        
        do {
            // Generate a random 5-digit number (10000 to 99999)
            $code = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            
            // Check if code already exists
            $query = Guest::where('invitation_code', $code);
            if ($eventId) {
                $query->where('event_id', $eventId);
            }
            $exists = $query->exists();
            
            $attempts++;
            
            // If we've tried too many times, use timestamp-based code
            if ($attempts >= $maxAttempts) {
                // Use last 5 digits of timestamp + random
                $code = substr(time(), -4) . rand(0, 9);
                // Check one more time
                $query = Guest::where('invitation_code', $code);
                if ($eventId) {
                    $query->where('event_id', $eventId);
                }
                if (!$query->exists()) {
                    return $code;
                }
                // If still exists, use microtime
                $code = substr(str_replace('.', '', microtime(true)), -5);
                return $code;
            }
        } while ($exists);
        
        return $code;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $host = Auth::user();
        
        // Get the host's configured event
        $event = Event::where('host_id', $host->id)->first();
        
        if (!$event) {
            return redirect()->route('host.event-config')->with('error', 'Please configure your event first before managing guests.');
        }
        
        // Generate codes for guests that don't have one (only update those without codes)
        $guestsWithoutCodes = Guest::where('event_id', $event->id)
            ->where(function($query) {
                $query->whereNull('invitation_code')
                      ->orWhere('invitation_code', '');
            })
            ->get();
        
        foreach ($guestsWithoutCodes as $guest) {
            $uniqueCode = $this->generateUniqueGuestCode($event->id);
            $guest->update(['invitation_code' => $uniqueCode]);
        }
        
        // Get paginated guests
        $guests = Guest::where('event_id', $event->id)->paginate(15);

        $currentGuestCount = Guest::where('event_id', $event->id)->count();
        $allowedGuests = $host->number_of_guests;
        $remainingGuests = $allowedGuests - $currentGuestCount;

        $stats = [
            'total' => $currentGuestCount,
            'confirmed' => Guest::where('event_id', $event->id)->where('status', 'attended')->count(),
            'pending' => Guest::where('event_id', $event->id)->where('status', 'active')->count(),
            'declined' => Guest::where('event_id', $event->id)->where('status', 'declined')->count(),
            'allowed' => $allowedGuests,
            'remaining' => $remainingGuests,
        ];

        return view('host.guests.index', compact('guests', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('host.guests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'guest_type' => 'required|in:single,double',
            'phone' => 'required|string|max:20',
        ]);

        $host = Auth::user();
        
        // Get the host's configured event
        $event = Event::where('host_id', $host->id)->first();
        
        if (!$event) {
            return redirect()->back()->with('error', 'No event found. Please configure your event first.');
        }
        
        // Check guest limit
        $currentGuestCount = Guest::where('event_id', $event->id)->count();
        $allowedGuests = $host->number_of_guests;
        
        if ($currentGuestCount >= $allowedGuests) {
            return redirect()->back()->with('error', "You have reached your guest limit of {$allowedGuests} guests. Please upgrade your plan to add more guests.");
        }
        
        // Normalize phone number
        $normalizedPhone = $this->normalizePhone($request->phone);
        
        // Check for duplicate phone number
        $existingGuest = Guest::where('event_id', $event->id)
            ->where('phone', $normalizedPhone)
            ->first();
        
        if ($existingGuest) {
            return redirect()->back()->with('error', 'A guest with this phone number already exists.');
        }
        
        // Generate unique 5-digit code
        $uniqueCode = $this->generateUniqueGuestCode($event->id);
        
        // Determine Pax and generate 4-digit PIN for SMS / kitochi
        $pax = $request->guest_type === 'double' ? 2 : 1;
        $pinCode = Guest::generatePinCode($event->id);
        $amountPledged = (float) ($request->input('amount_pledged', 0));
        $amountPaid = (float) ($request->input('amount_paid', 0));
        $paymentStatus = 'pending';
        if ($amountPaid > 0) {
            $paymentStatus = ($amountPledged > 0 && $amountPaid >= $amountPledged) ? 'paid' : 'partial';
        }

        // Create guest with event association and smart Tanzanian event fields
        $guest = Guest::create([
            'event_id' => $event->id,
            'full_name' => $request->full_name,
            'guest_type' => $request->guest_type,
            'phone' => $normalizedPhone,
            'status' => 'active',
            'qr_code' => Str::random(10),
            'invitation_code' => $uniqueCode,
            'pin_code' => $pinCode,
            'table_number' => $request->input('table_number'),
            'category' => $request->input('category', 'friends'),
            'pax' => $pax,
            'amount_pledged' => $amountPledged,
            'amount_paid' => $amountPaid,
            'payment_method' => $request->input('payment_method'),
            'payment_status' => $paymentStatus,
        ]);

        // Automatically create the personalized Invitation & QR Code for the guest
        $invitation = \App\Models\Invitation::firstOrCreate(
            ['event_id' => $event->id, 'guest_id' => $guest->id],
            [
                'template_id' => $event->template_id ?: 1,
                'slug' => Str::slug($event->title ?: 'invitation') . '-' . $guest->id . '-' . Str::random(8),
                'status' => 'sent',
                'rsvp_status' => 'pending',
                'plus_one_count' => max(0, $pax - 1),
            ]
        );
        app(\App\Services\InvitationQrService::class)->ensureQrCode($invitation);

        $remainingGuests = $allowedGuests - ($currentGuestCount + 1);
        return redirect()->route('host.guests.index')->with('success', "Guest added successfully! You have {$remainingGuests} guest slots remaining.");
    }

    /**
     * Import guests from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $host = Auth::user();
        
        // Get the host's configured event
        $event = Event::where('host_id', $host->id)->first();
        
        if (!$event) {
            return redirect()->back()->with('error', 'No event found. Please configure your event first.');
        }
        
        // Check guest limit before import
        $currentGuestCount = Guest::where('event_id', $event->id)->count();
        $allowedGuests = $host->number_of_guests;
        
        if ($currentGuestCount >= $allowedGuests) {
            return redirect()->back()->with('error', "You have reached your guest limit of {$allowedGuests} guests. Please upgrade your plan to add more guests.");
        }
        
        try {
            // Read Excel data to check how many guests we can import
            $excelData = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
                public function array(array $array)
                {
                    return $array;
                }
            }, $request->file('excel_file'));
            
            // Count data rows (excluding header)
            $totalDataRows = count($excelData[0]) - 1;
            $availableSlots = $allowedGuests - $currentGuestCount;
            
            // Determine how many guests we can actually import
            $guestsToImport = min($totalDataRows, $availableSlots);
            
            if ($guestsToImport <= 0) {
                return redirect()->back()->with('error', "You have reached your guest limit of {$allowedGuests} guests. Please upgrade your plan to add more guests.");
            }
            
            // Create closure for generating unique code
            $generateCode = function($eventId) {
                $maxAttempts = 100;
                $attempts = 0;
                
                do {
                    // Generate a random 5-digit number (10000 to 99999)
                    $code = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
                    
                    // Check if code already exists
                    $query = Guest::where('invitation_code', $code);
                    if ($eventId) {
                        $query->where('event_id', $eventId);
                    }
                    $exists = $query->exists();
                    
                    $attempts++;
                    
                    // If we've tried too many times, use timestamp-based code
                    if ($attempts >= $maxAttempts) {
                        $code = substr(time(), -4) . rand(0, 9);
                        $query = Guest::where('invitation_code', $code);
                        if ($eventId) {
                            $query->where('event_id', $eventId);
                        }
                        if (!$query->exists()) {
                            return $code;
                        }
                        $code = substr(str_replace('.', '', microtime(true)), -5);
                        return $code;
                    }
                } while ($exists);
                
                return $code;
            };
            
            // Import only the allowed number of guests
            Excel::import(new class($guestsToImport, $event->id, $generateCode) implements ToModel, WithHeadingRow, WithValidation {
                private $maxGuests;
                private $eventId;
                private $importedCount = 0;
                private $processedPhones = [];
                private $generateCode;

                public function __construct($maxGuests, $eventId, $generateCode)
                {
                    $this->maxGuests = $maxGuests;
                    $this->eventId = $eventId;
                    $this->generateCode = $generateCode;
                }

                private function normalizePhone($phone)
                {
                    // Convert to string and remove all non-numeric characters
                    $phone = preg_replace('/[^0-9]/', '', (string) $phone);
                    
                    // Remove leading zeros
                    $phone = ltrim($phone, '0');
                    
                    // If it starts with 255, return as is
                    if (str_starts_with($phone, '255')) {
                        return $phone;
                    }
                    
                    // If it doesn't start with 255, add the prefix
                    return '255' . $phone;
                }

                public function model(array $row)
                {
                    if ($this->importedCount >= $this->maxGuests) {
                        return null; // Skip this row
                    }
                    
                    $normalizedPhone = $this->normalizePhone($row['phone']);
                    
                    // Check for duplicates within the import batch
                    if (in_array($normalizedPhone, $this->processedPhones)) {
                        return null; // Skip duplicate phone in batch
                    }
                    
                    // Check for duplicates in database
                    $existingGuest = Guest::where('event_id', $this->eventId)
                        ->where('phone', $normalizedPhone)
                        ->first();
                    
                    if ($existingGuest) {
                        return null; // Skip duplicate phone in database
                    }
                    
                    $this->processedPhones[] = $normalizedPhone;
                    $this->importedCount++;
                    
                    // Get guest_type from row, default to 'single' if not provided
                    $guestType = isset($row['guest_type']) ? strtolower(trim($row['guest_type'])) : 'single';
                    // Validate guest_type
                    if (!in_array($guestType, ['single', 'double'])) {
                        $guestType = 'single'; // Default to single if invalid
                    }
                    
                    // Generate unique 5-digit code using closure
                    $uniqueCode = ($this->generateCode)($this->eventId);
                    $pax = $guestType === 'double' ? 2 : 1;
                    $pinCode = Guest::generatePinCode($this->eventId);
                    
                    return new Guest([
                        'event_id' => $this->eventId,
                        'full_name' => (string) $row['full_name'],
                        'guest_type' => $guestType,
                        'phone' => $normalizedPhone,
                        'status' => 'active',
                        'qr_code' => Str::random(10),
                        'invitation_code' => $uniqueCode,
                        'pin_code' => $pinCode,
                        'pax' => $pax,
                    ]);
                }

                public function rules(): array
                {
                    return [
                        'full_name' => 'required|max:255',
                        'guest_type' => 'nullable|string|max:255',
                        'phone' => 'required|max:20',
                    ];
                }
            }, $request->file('excel_file'));

            // Ensure every imported guest has an Invitation and QR code ready
            $guestsWithoutInvitation = Guest::where('event_id', $event->id)->doesntHave('invitation')->get();
            foreach ($guestsWithoutInvitation as $importedGuest) {
                $invitation = \App\Models\Invitation::firstOrCreate(
                    ['event_id' => $event->id, 'guest_id' => $importedGuest->id],
                    [
                        'template_id' => $event->template_id ?: 1,
                        'slug' => Str::slug($event->title ?: 'invitation') . '-' . $importedGuest->id . '-' . Str::random(8),
                        'status' => 'sent',
                        'rsvp_status' => 'pending',
                        'plus_one_count' => max(0, (int) ($importedGuest->pax ?? 1) - 1),
                    ]
                );
                app(\App\Services\InvitationQrService::class)->ensureQrCode($invitation);
            }

            $remainingGuests = $allowedGuests - ($currentGuestCount + $guestsToImport);
            
            if ($guestsToImport < $totalDataRows) {
                $skippedGuests = $totalDataRows - $guestsToImport;
                return redirect()->route('host.guests.index')->with('warning', "Successfully imported {$guestsToImport} guests! {$skippedGuests} guests were skipped due to guest limit. You have {$remainingGuests} guest slots remaining.");
            } else {
                return redirect()->route('host.guests.index')->with('success', "Successfully imported {$guestsToImport} guests! You have {$remainingGuests} guest slots remaining.");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template
     */
    public function template()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="guest_template.xlsx"',
        ];

        $data = [
            ['full_name', 'guest_type', 'phone'],
            ['Mgeni wa Kwanza', 'single', '255700000001'],
            ['Mgeni wa Pili', 'double', '255700000002'],
        ];

        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }
        }, 'guest_template.xlsx', \Maatwebsite\Excel\Excel::XLSX, $headers);
    }

    /**
     * Securely resolve a Guest belonging strictly to the authenticated Host.
     */
    private function findHostGuestOrFail(string $id): Guest
    {
        $hostId = Auth::id();
        return Guest::with('event')
            ->whereHas('event', function ($q) use ($hostId) {
                $q->where('host_id', $hostId);
            })
            ->findOrFail($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guest = $this->findHostGuestOrFail($id);
        return view('host.guests.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guest = $this->findHostGuestOrFail($id);
        return view('host.guests.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'guest_type' => 'required|in:single,double',
            'phone' => 'required|string|max:20',
            'table_number' => 'nullable|string|max:50',
        ]);

        $guest = $this->findHostGuestOrFail($id);
        
        // Normalize phone number
        $normalizedPhone = $this->normalizePhone($request->phone);
        
        // Check for duplicate phone number (excluding current guest)
        $existingGuest = Guest::where('event_id', $guest->event_id)
            ->where('phone', $normalizedPhone)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingGuest) {
            return redirect()->back()->with('error', 'A guest with this phone number already exists.');
        }
        
        $pax = $request->guest_type === 'double' ? 2 : 1;

        $guest->update([
            'full_name' => $request->full_name,
            'guest_type' => $request->guest_type,
            'phone' => $normalizedPhone,
            'pax' => $pax,
            'table_number' => $request->filled('table_number') ? $request->input('table_number') : $guest->table_number,
        ]);

        return redirect()->route('host.guests.index')->with('success', 'Guest updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guest = $this->findHostGuestOrFail($id);
        $guest->delete();

        return redirect()->route('host.guests.index')->with('success', 'Guest deleted successfully!');
    }
}
