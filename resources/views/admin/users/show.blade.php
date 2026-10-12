@extends('layouts.dashboard')

@section('title', 'User Details - ' . $user->name)
@section('page-title', 'User Profile')

@section('content')
<div class="space-y-8 pb-12 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Security &amp; Personnel Profile
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">{{ $user->name }}</h1>
            <p class="text-sm text-slate-500">Overview of access privileges, communication channels, and account status.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary text-xs sm:text-sm shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Account
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Users
            </a>
        </div>
    </div>

    <!-- User Profile Overview Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <!-- Top Hero Section -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 text-white relative">
            <div class="flex flex-col sm:flex-row sm:items-center gap-6 relative z-10">
                <div class="relative">
                    <div class="h-20 w-20 rounded-3xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center font-black text-2xl text-white shadow-xl shadow-indigo-500/30">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-400' }} border-3 border-slate-900 shadow-xs"></span>
                </div>
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="text-2xl font-black">{{ $user->name }}</h2>
                        @if($user->role === 'super_admin')
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-purple-500/20 text-purple-300 border border-purple-400/30">👑 Super Admin</span>
                        @elseif($user->role === 'event_admin')
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-500/20 text-blue-300 border border-blue-400/30">⚡ Event Admin</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">🛡️ Staff Member</span>
                        @endif
                    </div>
                    <p class="text-sm text-indigo-200/80 font-mono">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Contact Details</h3>
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500">Email Address</span>
                            <span class="text-xs font-bold text-slate-900 font-mono">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500">Phone Number</span>
                            <span class="text-xs font-bold text-slate-900 font-mono">{{ $user->phone ?? 'Not specified' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Account Metadata</h3>
                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500">Account Status</span>
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500">Member Since</span>
                            <span class="text-xs font-bold text-slate-900">{{ $user->created_at->format('F j, Y - H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection