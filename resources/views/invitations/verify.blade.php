@extends('layouts.app')

@section('title', 'Invitation Verification')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="bg-white shadow rounded-2xl border border-gray-100 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Invitation Verification</h1>
        <p class="text-gray-600 mb-6">Share this screen with event staff at check-in.</p>

        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">Guest</p>
                <p class="text-lg font-semibold text-gray-900">{{ $guest->full_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Event</p>
                <p class="text-lg font-semibold text-gray-900">{{ $event->title }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Date</p>
                    <p class="font-medium text-gray-900">{{ optional($event->date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Time</p>
                    <p class="font-medium text-gray-900">{{ $event->time ?: 'N/A' }}</p>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500">Venue</p>
                <p class="font-medium text-gray-900">{{ $event->venue ?: 'N/A' }}</p>
            </div>

            <div class="pt-4">
                @if($checkedIn)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800">
                        Already checked in at {{ optional($guest->attended_at)->format('M d, Y H:i') }}.
                    </div>
                @else
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                        Invitation is valid. Not checked in yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
