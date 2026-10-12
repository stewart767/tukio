@extends('layouts.dashboard')

@section('title', 'Host Payments & Invoices')
@section('page-title', 'Host Payments & Invoices')

@section('content')
<div class="space-y-8 animate-fade-in max-w-7xl mx-auto">

    <!-- Header & Stats Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight">Host Payments &amp; Invoices</h1>
            <p class="text-sm text-slate-500 mt-1">Manage event fees, verify Selcom Pesa transactions, and track platform revenue.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.index') }}#payment" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition-all shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Selcom Settings
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Collected</span>
                <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 font-mono mt-3">{{ number_format($stats['total_revenue']) }} <span class="text-xs text-slate-400 font-sans">TZS</span></p>
            <p class="text-xs text-emerald-600 font-medium mt-1">{{ $stats['paid_count'] }} paid invoices</p>
        </div>

        <!-- Under Review / Action Required -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Under Review</span>
                <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-black text-blue-600 font-mono mt-3">{{ $stats['under_review_count'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-1">Proof submitted by host</p>
        </div>

        <!-- Pending Unpaid -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Payment Pending</span>
                <span class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-black text-amber-600 font-mono mt-3">{{ $stats['pending_count'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-1">Awaiting host payment</p>
        </div>

        <!-- Total Invoices -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Invoices</span>
                <span class="p-2.5 rounded-xl bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 font-mono mt-3">{{ $stats['total_invoices'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-1">Across all registered hosts</p>
        </div>

    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
            <a href="{{ route('admin.payments.index', ['status' => 'all', 'search' => $search]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All ({{ $stats['total_invoices'] }})
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'under_review', 'search' => $search]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'under_review' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Under Review ({{ $stats['under_review_count'] }})
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'pending', 'search' => $search]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Pending ({{ $stats['pending_count'] }})
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'paid', 'search' => $search]) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $status === 'paid' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Paid ({{ $stats['paid_count'] }})
            </a>
        </div>

        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.payments.index') }}" class="w-full md:w-80 flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search host, reference, phone..." 
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            @if($search)
                <a href="{{ route('admin.payments.index', ['status' => $status]) }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </form>

    </div>

    <!-- Payments Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-6">Invoice #</th>
                        <th class="py-3.5 px-6">Host / Event</th>
                        <th class="py-3.5 px-6">Amount</th>
                        <th class="py-3.5 px-6">Payment Method / Ref</th>
                        <th class="py-3.5 px-6 text-center">Receipt</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        
                        <!-- Invoice # & Date -->
                        <td class="py-4 px-6 font-mono">
                            <span class="font-bold text-slate-900 block">{{ $inv->invoice_number }}</span>
                            <span class="text-xs text-slate-400">{{ $inv->created_at->format('M d, Y') }}</span>
                        </td>

                        <!-- Host & Event -->
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-900">{{ $inv->host?->full_name ?? 'Host #' . $inv->host_id }}</div>
                            <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                <span>{{ $inv->event?->title ?? 'Event' }}</span>
                                <span>&bull;</span>
                                <span class="text-slate-400">{{ $inv->guest_count }} guests</span>
                            </div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $inv->host?->phone }}</div>
                        </td>

                        <!-- Amount -->
                        <td class="py-4 px-6">
                            <div class="font-black font-mono text-slate-900">{{ number_format($inv->total_amount) }} TZS</div>
                            @if($inv->amount_paid > 0 && $inv->amount_paid != $inv->total_amount)
                                <div class="text-[11px] text-emerald-600 font-medium">Paid: {{ number_format($inv->amount_paid) }} TZS</div>
                            @endif
                        </td>

                        <!-- Payment Ref -->
                        <td class="py-4 px-6">
                            @if($inv->transaction_reference)
                                <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 block max-w-fit">
                                    {{ $inv->transaction_reference }}
                                </span>
                                <span class="text-[11px] text-slate-400 mt-1 block">
                                    {{ ucfirst(str_replace('_', ' ', $inv->payment_method ?? 'selcom_pesa')) }}
                                    @if($inv->payer_phone) &bull; {{ $inv->payer_phone }} @endif
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">No reference submitted</span>
                            @endif
                        </td>

                        <!-- Receipt Proof -->
                        <td class="py-4 px-6 text-center">
                            @if($inv->payment_receipt)
                                <a href="{{ asset('storage/' . $inv->payment_receipt) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-semibold bg-indigo-50 px-2.5 py-1 rounded-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            @else
                                <span class="text-xs text-slate-300">-</span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $inv->status_badge_class }} border">
                                {{ $inv->status_label }}
                            </span>
                            @if($inv->verified_at)
                                <span class="text-[10px] text-slate-400 block mt-1 font-mono">{{ $inv->verified_at->format('M d, H:i') }}</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.payments.show', $inv->id) }}" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition-colors" title="View Full Details">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                @if(!$inv->isPaid())
                                <form action="{{ route('admin.payments.verify', $inv->id) }}" method="POST" onsubmit="return confirm('Verify and mark invoice #{{ $inv->invoice_number }} as PAID?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Verify
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-base font-semibold text-slate-600">No invoices found</p>
                            <p class="text-xs text-slate-400 mt-1">Try changing your search query or filter</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
