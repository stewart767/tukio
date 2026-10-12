@extends('layouts.host')

@section('title', 'Events')
@section('page-title', 'Events')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Events</h1>
            <p class="text-gray-600 mt-2">Manage your events and view details</p>
        </div>
        <a href="{{ route('host.events.create') }}" class="modern-button modern-button-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Events Grid - Modern Floating Cards -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <div class="event-card group overflow-hidden">
                    <!-- Image Header -->
                    <div class="event-card-image h-48 bg-gradient-to-br from-primary-500 to-secondary-500 relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <!-- Floating Status Badge -->
                        <div class="floating-badge 
                            @if($event->status === 'active') bg-green-500 text-white
                            @elseif($event->status === 'draft') bg-yellow-500 text-white
                            @elseif($event->status === 'completed') bg-blue-500 text-white
                            @else bg-red-500 text-white
                            @endif">
                            {{ ucfirst($event->status) }}
                        </div>
                        <!-- Event Title Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-xl font-bold text-white mb-1 line-clamp-2">{{ $event->title }}</h3>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $event->date->format('M d, Y') }} at {{ $event->time }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-lg bg-secondary-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $event->venue }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-lg bg-accent-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $event->category)) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('host.events.show', $event) }}" class="modern-button modern-button-ghost modern-button-sm group/btn">
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('host.events.edit', $event) }}" class="modern-button modern-button-secondary modern-button-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                            <div class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $event->guests->count() }} guests
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="floating-card p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No events yet</h3>
            <p class="text-gray-600 mb-6">Create your first event to get started with managing guests and invitations.</p>
            <a href="{{ route('host.events.create') }}" class="modern-button modern-button-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Your First Event
            </a>
        </div>
    @endif
</div>
@endsection