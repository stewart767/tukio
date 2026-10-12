<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Template;
use App\Models\EventTypeConfig;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $host = auth('host')->user();
        $events = Event::where('host_id', $host->id)->with('template')->latest()->paginate(10);
        return view('host.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templates = Template::where('is_active', true)->get();
        $eventTypes = EventTypeConfig::getAllEventTypes();
        return view('host.events.create', compact('templates', 'eventTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:wedding,birthday,kitchen_party,send_off,corporate',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'template_id' => 'nullable|exists:templates,id',
        ]);

        // Get custom fields for the selected event type
        $customFields = EventTypeConfig::getFieldsForType($request->category);
        $customData = [];

        // Process custom fields
        if (!empty($customFields['fields'])) {
            foreach ($customFields['fields'] as $field) {
                $fieldName = $field['name'];
                if ($request->has($fieldName)) {
                    if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                        // Handle file uploads
                        $file = $request->file($fieldName);
                        $filename = time() . '_' . $fieldName . '.' . $file->getClientOriginalExtension();
                        $file->storeAs('public/event_uploads', $filename);
                        $customData[$fieldName] = $filename;
                    } else {
                        $customData[$fieldName] = $request->input($fieldName);
                    }
                }
            }
        }

        // Generate 5-character event password
        $eventPassword = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));
        
        Event::create([
            'host_id' => auth('host')->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'date' => $request->date,
            'time' => $request->time,
            'venue' => $request->venue,
            'venue_address' => $request->venue_address,
            'template_id' => $request->template_id,
            'status' => 'draft',
            'event_password' => $eventPassword,
            'custom_data' => $customData,
        ]);

        return redirect()->route('host.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);
        $event->load(['template', 'guests', 'invitations']);
        return view('host.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $templates = Template::where('is_active', true)->get();
        $eventTypes = EventTypeConfig::getAllEventTypes();
        return view('host.events.edit', compact('event', 'templates', 'eventTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:wedding,birthday,kitchen_party,send_off,corporate',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'template_id' => 'nullable|exists:templates,id',
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        // Get custom fields for the selected event type
        $customFields = EventTypeConfig::getFieldsForType($request->category);
        $customData = $event->custom_data ?? [];

        // Process custom fields
        if (!empty($customFields['fields'])) {
            foreach ($customFields['fields'] as $field) {
                $fieldName = $field['name'];
                if ($request->has($fieldName)) {
                    if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                        // Handle file uploads
                        $file = $request->file($fieldName);
                        $filename = time() . '_' . $fieldName . '.' . $file->getClientOriginalExtension();
                        $file->storeAs('public/event_uploads', $filename);
                        $customData[$fieldName] = $filename;
                    } else {
                        $customData[$fieldName] = $request->input($fieldName);
                    }
                }
            }
        }

        $eventData = $request->all();
        $eventData['custom_data'] = $customData;
        $event->update($eventData);

        return redirect()->route('host.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('host.events.index')->with('success', 'Event deleted successfully.');
    }
}
