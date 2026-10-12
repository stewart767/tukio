<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Host;
use App\Models\Event;
use App\Models\User;
use App\Notifications\NewHostRegisteredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class HostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hosts = Host::latest()->paginate(10);

        $metrics = [
            'total_hosts' => Host::count(),
            'active_hosts' => Host::where('is_active', true)->count(),
            'configured_hosts' => Host::where('event_configured', true)->count(),
        ];

        return view('admin.hosts.index', compact('hosts', 'metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eventTypes = $this->eventTypes();
        return view('admin.hosts.create', compact('eventTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:hosts,email'],
            'phone' => ['required', 'string', 'max:25'],
            'event_type' => ['required', Rule::in(array_keys($this->eventTypes()))],
            'number_of_guests' => ['required', 'integer', 'min:1'],
            'total_cost' => ['required', 'numeric', 'min:0'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $host = Host::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'event_type' => $validated['event_type'],
            'number_of_guests' => $validated['number_of_guests'],
            'total_cost' => $validated['total_cost'],
            'password' => $validated['password'], // Model will hash automatically due to 'hashed' cast
            'is_active' => $validated['is_active'] ?? true,
            'event_configured' => false,
        ]);

        // Send email notification to admin emails
        try {
            $adminEmails = ['info@mycard.co.tz', 'deangarlus@gmail.com'];
            foreach ($adminEmails as $email) {
                Notification::route('mail', $email)
                    ->notify(new NewHostRegisteredNotification($host));
            }
        } catch (\Exception $e) {
            // Log error but don't fail host creation
            \Log::error('Failed to send host registration notification: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.hosts.index')
            ->with('success', 'Host account created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Host $host)
    {
        $eventTypes = $this->eventTypes();
        return view('admin.hosts.edit', compact('host', 'eventTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Host $host)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('hosts', 'email')->ignore($host->id)],
            'phone' => ['required', 'string', 'max:25'],
            'event_type' => ['required', Rule::in(array_keys($this->eventTypes()))],
            'number_of_guests' => ['required', 'integer', 'min:1'],
            'total_cost' => ['required', 'numeric', 'min:0'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'event_configured' => ['nullable', 'boolean'],
        ]);

        $host->fill([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'event_type' => $validated['event_type'],
            'number_of_guests' => $validated['number_of_guests'],
            'total_cost' => $validated['total_cost'],
            'is_active' => $validated['is_active'] ?? false,
            'event_configured' => $validated['event_configured'] ?? false,
        ]);

        if (!empty($validated['password'])) {
            $host->password = $validated['password']; // Model will hash automatically due to 'hashed' cast
        }

        $host->save();

        return redirect()
            ->route('admin.hosts.index')
            ->with('success', 'Host updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Host $host)
    {
        $host->delete();

        return redirect()
            ->route('admin.hosts.index')
            ->with('success', 'Host deleted successfully.');
    }

    /**
     * Mark the specified host's event as finished and deactivate the account.
     */
    public function finish(Host $host)
    {
        if ($host->hasFinishedEvent()) {
            return redirect()
                ->route('admin.hosts.index')
                ->with('success', 'This event was already marked as finished.');
        }

        $host->update([
            'event_finished_at' => now(),
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.hosts.index')
            ->with('success', 'Event marked as finished. The host can no longer access the portal.');
    }

    /**
     * Show form to assign staff to host's events.
     */
    public function showAssignStaff(Host $host)
    {
        $events = Event::where('host_id', $host->id)->with('staffMembers')->get();
        $staffMembers = User::where('role', 'staff')->where('is_active', true)->get();
        
        return view('admin.hosts.assign-staff', compact('host', 'events', 'staffMembers'));
    }

    /**
     * Assign staff to host's events.
     */
    public function assignStaff(Request $request, Host $host)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'staff_ids' => 'required|array|min:1',
            'staff_ids.*' => 'required|exists:users,id',
        ]);

        // Verify the event belongs to this host
        $event = Event::where('id', $request->event_id)
            ->where('host_id', $host->id)
            ->firstOrFail();

        // Verify all users are staff
        $staffMembers = User::whereIn('id', $request->staff_ids)
            ->where('role', 'staff')
            ->get();

        if ($staffMembers->count() !== count($request->staff_ids)) {
            return back()->withErrors([
                'staff_ids' => 'One or more selected users are not valid staff members.',
            ])->withInput();
        }

        // Sync staff members (many-to-many)
        $event->staffMembers()->sync($request->staff_ids);

        // Also update the legacy staff_id field with the first staff member
        $event->update([
            'staff_id' => $request->staff_ids[0],
        ]);

        $staffNames = $staffMembers->pluck('name')->join(', ');
        $message = count($request->staff_ids) === 1 
            ? "Staff {$staffNames} has been assigned to event: {$event->title}"
            : "Staff members ({$staffNames}) have been assigned to event: {$event->title}";

        return redirect()
            ->route('admin.hosts.index')
            ->with('success', $message);
    }

    /**
     * Available event types for host creation.
     */
    protected function eventTypes(): array
    {
        return [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday',
            'kitchen_party' => 'Kitchen Party',
            'send_off' => 'Send Off',
            'corporate' => 'Corporate',
        ];
    }
}

