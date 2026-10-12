@extends('layouts.host')

@section('title', 'Dashboard')
@section('page-title', 'Host Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    
    <!-- Welcome Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 p-8 sm:p-10 text-white shadow-xl shadow-indigo-500/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-indigo-100">
                    <span>✨ Live Event Space</span>
                    <span>&bull;</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $data['host']->event_type)) }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight text-white">
                    Welcome back, {{ $data['host']->full_name }}! 👋
                </h1>
                <p class="text-indigo-100/90 text-sm sm:text-base max-w-xl">
                    Manage your event guests, send WhatsApp invitations, monitor real-time RSVP check-ins, and arrange car hire.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('host.invitations.create') }}" class="modern-button bg-white text-indigo-700 hover:bg-indigo-50 font-bold shadow-lg shadow-black/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span>Send Invitations</span>
                </a>
                <a href="{{ route('host.guests.create') }}" class="modern-button bg-white/20 hover:bg-white/30 text-white border border-white/30 font-semibold backdrop-blur-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    <span>Add Guest</span>
                </a>
            </div>
        </div>

        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Payment Status Notification Banner -->
    @if(isset($data['invoice']) && $data['invoice'])
        @if($data['invoice']->isPaid())
            <div class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 p-5 sm:p-6 text-white shadow-lg shadow-emerald-500/15 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base sm:text-lg">Event Access Confirmed</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-white text-emerald-800 uppercase tracking-wider">Paid &amp; Active</span>
                        </div>
                        <p class="text-emerald-100 text-xs mt-0.5">Invoice #{{ $data['invoice']->invoice_number }} is fully settled. Instant WhatsApp card delivery is activated.</p>
                    </div>
                </div>
                <a href="{{ route('host.payments.index') }}" class="modern-button bg-white text-emerald-700 hover:bg-emerald-50 font-bold text-xs shadow-md shrink-0">
                    View Receipt &rarr;
                </a>
            </div>
        @elseif($data['invoice']->isUnderReview())
            <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 p-5 sm:p-6 text-white shadow-lg shadow-blue-500/15 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 animate-pulse">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base sm:text-lg">Payment Verification Underway</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-white/20 text-white uppercase tracking-wider">Under Review</span>
                        </div>
                        <p class="text-blue-100 text-xs mt-0.5">Ref: <strong class="font-mono bg-white/20 px-1.5 py-0.5 rounded text-white">{{ $data['invoice']->transaction_reference }}</strong>. Our team is verifying your payment.</p>
                    </div>
                </div>
                <a href="{{ route('host.payments.index') }}" class="modern-button bg-white/20 hover:bg-white/30 text-white border border-white/30 font-bold text-xs shrink-0">
                    Check Status
                </a>
            </div>
        @else
            <div class="rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 p-5 sm:p-6 text-white shadow-lg shadow-amber-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base sm:text-lg">Complete Your Event Payment</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-white text-amber-900 uppercase tracking-wider">Pending Lipa</span>
                        </div>
                        <p class="text-amber-100 text-xs mt-0.5">Total Package: <strong>{{ number_format($data['total_cost']) }} TSH</strong>. Pay via Selcom Pesa (Lipa: <strong>5525108767899</strong>) to unlock full dispatch features.</p>
                    </div>
                </div>
                <a href="{{ route('host.payments.index') }}" class="modern-button bg-white text-amber-800 hover:bg-amber-50 font-bold text-xs shadow-md shrink-0">
                    Pay Now with Lipa &rarr;
                </a>
            </div>
        @endif
    @endif

    <!-- Key Stats Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Total Guests -->
        <a href="{{ route('host.guests.index') }}" class="host-stat-card p-6 flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Guests</p>
                    <p class="text-2xl font-black font-heading text-slate-900 mt-0.5">{{ $data['host']->number_of_guests }}</p>
                    <p class="text-xs text-indigo-600 font-medium group-hover:underline">Manage Guest List &rarr;</p>
                </div>
            </div>
        </a>

        <!-- Billing & Invoicing -->
        <a href="{{ route('host.payments.index') }}" class="host-stat-card p-6 flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Amount</p>
                    <p class="text-2xl font-black font-heading text-slate-900 mt-0.5">{{ number_format($data['total_cost']) }} <span class="text-xs font-bold text-slate-400">TSH</span></p>
                    @if(isset($data['invoice']) && $data['invoice'])
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $data['invoice']->status_badge_class }} border mt-1">
                            {{ $data['invoice']->status_label }}
                        </span>
                    @endif
                </div>
            </div>
        </a>

        <!-- Event Type -->
        <div class="host-stat-card p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Event Setup</p>
                    <p class="text-2xl font-black font-heading text-slate-900 mt-0.5 capitalize">{{ ucfirst(str_replace('_', ' ', $data['host']->event_type)) }}</p>
                    <a href="{{ route('host.event-config') }}" class="text-xs text-purple-600 font-medium hover:underline">Edit Configuration &rarr;</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions Launchpad -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-6">
        <div>
            <h2 class="text-xl font-bold font-heading text-slate-900">Host Quick Launchpad</h2>
            <p class="text-xs text-slate-500">Fast access to essential event planning modules.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- Action: Billing -->
            <a href="{{ route('host.payments.index') }}" class="group p-5 rounded-2xl bg-gradient-to-br from-amber-50/80 to-amber-100/40 border border-amber-200/80 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center mb-3 shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1">Billing &amp; Payment</h3>
                <p class="text-xs text-slate-500">Pay via Lipa Selcom &amp; get receipts</p>
            </a>

            <!-- Action: Guests -->
            <a href="{{ route('host.guests.index') }}" class="group p-5 rounded-2xl bg-gradient-to-br from-indigo-50/80 to-indigo-100/40 border border-indigo-200/80 hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center mb-3 shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.75c0-.621-.503-1.125-1.125-1.125H5.625c-.621 0-1.125.503-1.125 1.125v2.25c0 .621.503 1.125 1.125 1.125h11.25c.621 0 1.125-.503 1.125-1.125v-2.25zM15.75 10.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1">Guest Management</h3>
                <p class="text-xs text-slate-500">Add guests or import from Excel</p>
            </a>

            <!-- Action: Invitations -->
            <a href="{{ route('host.invitations.index') }}" class="group p-5 rounded-2xl bg-gradient-to-br from-blue-50/80 to-blue-100/40 border border-blue-200/80 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-600 text-white flex items-center justify-center mb-3 shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.902l-5.4 2.7a2.25 2.25 0 01-2.13 0l-5.4-2.7a2.25 2.25 0 01-1.07-1.902V6.75m19.5 0H4.5"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1">Digital Invitations</h3>
                <p class="text-xs text-slate-500">Deliver via WhatsApp with QR</p>
            </a>

            <!-- Action: Car Hire -->
            <a href="{{ route('host.car-hire.index') }}" class="group p-5 rounded-2xl bg-gradient-to-br from-emerald-50/80 to-emerald-100/40 border border-emerald-200/80 hover:border-emerald-400 hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white flex items-center justify-center mb-3 shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1">Car Hire &amp; Fleet</h3>
                <p class="text-xs text-slate-500">Book VIP bridal &amp; escort fleet</p>
            </a>

            <!-- Action: Event Settings -->
            <a href="{{ route('host.event-config') }}" class="group p-5 rounded-2xl bg-gradient-to-br from-purple-50/80 to-purple-100/40 border border-purple-200/80 hover:border-purple-400 hover:shadow-lg hover:shadow-purple-500/10 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-purple-600 to-pink-600 text-white flex items-center justify-center mb-3 shadow-md shadow-purple-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1">Event Settings</h3>
                <p class="text-xs text-slate-500">Edit titles, venue &amp; photos</p>
            </a>

        </div>
    </div>

</div>
@endsection
