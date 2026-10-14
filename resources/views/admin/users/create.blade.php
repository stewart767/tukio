@extends('layouts.dashboard')

@section('title', 'Create User')
@section('page-title', 'Add New User')

@section('content')
<div class="space-y-8 pb-12 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                User Provisioning
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Create System Account</h1>
            <p class="text-sm text-slate-500">Grant role-based access to administrators and staff members.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs sm:text-sm">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>
    </div>

    <!-- Create Form Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5">
            <h2 class="text-base font-bold text-slate-900 font-heading">Account Credentials &amp; Role</h2>
            <p class="text-xs text-slate-500 mt-0.5">Please ensure the email address is valid for security notifications</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Sarah Jenkins"
                               class="form-input-modern pl-10 text-sm @error('name') border-rose-300 ring-1 ring-rose-300 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="sarah@example.com"
                               class="form-input-modern pl-10 text-sm @error('email') border-rose-300 ring-1 ring-rose-300 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+255 7XX XXX XXX"
                               class="form-input-modern pl-10 text-sm @error('phone') border-rose-300 ring-1 ring-rose-300 @enderror">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Role Selector -->
                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">System Role <span class="text-rose-500">*</span></label>
                    <select name="role" id="role" required
                            class="form-input-modern text-sm @error('role') border-rose-300 ring-1 ring-rose-300 @enderror">
                        <option value="">-- Choose Access Level --</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Full Control)</option>
                        <option value="event_admin" {{ old('role') === 'event_admin' ? 'selected' : '' }}>Event Admin (Operations &amp; Events)</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff (Field Coordination &amp; Check-ins)</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role explanation chips -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                <div class="p-3.5 rounded-2xl bg-purple-50/60 border border-purple-100 text-xs text-purple-900">
                    <div class="font-bold flex items-center gap-1.5 mb-1">
                        Super Admin
                    </div>
                    <p class="text-[11px] text-purple-700">Full system access, configurations, payment gateways, and all host data.</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs text-blue-900">
                    <div class="font-bold flex items-center gap-1.5 mb-1">
                        Event Admin
                    </div>
                    <p class="text-[11px] text-blue-700">Manage hosts, oversee event schedules, invitations, and bookings.</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-xs text-emerald-900">
                    <div class="font-bold flex items-center gap-1.5 mb-1">
                        Staff
                    </div>
                    <p class="text-[11px] text-emerald-700">Verify guest check-ins, scans QR codes, and assists host operations.</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" id="password" required placeholder="Minimum 8 characters"
                               class="form-input-modern text-sm @error('password') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Confirm Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Re-enter password"
                               class="form-input-modern text-sm">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create User Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection