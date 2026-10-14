@extends('layouts.dashboard')

@section('title', 'Edit User - ' . $user->name)
@section('page-title', 'Edit User Account')

@section('content')
<div class="space-y-8 pb-12 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                User Management
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Edit User: {{ $user->name }}</h1>
            <p class="text-sm text-slate-500">Update account credentials, contact information, and role assignments.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary text-xs sm:text-sm">
                View Profile
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Users
            </a>
        </div>
    </div>

    <!-- Edit User Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 font-heading">Account Information</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage permissions and communication details</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                {{ $user->is_active ? 'Active Account' : 'Inactive Account' }}
            </span>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
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
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
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
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
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
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+255 7XX XXX XXX"
                               class="form-input-modern pl-10 text-sm @error('phone') border-rose-300 ring-1 ring-rose-300 @enderror">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Role -->
                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Role Access Level <span class="text-rose-500">*</span></label>
                    @if($user->id === auth()->id() && $user->isSuperAdmin())
                        <select name="role" id="role" required disabled
                                class="form-input-modern text-sm bg-slate-100 text-slate-500 cursor-not-allowed">
                            <option value="super_admin" selected>Super Admin (Your Account)</option>
                        </select>
                        <input type="hidden" name="role" value="super_admin">
                        <p class="mt-1 text-xs font-semibold text-amber-600">You cannot demote your own active super admin account.</p>
                    @else
                        <select name="role" id="role" required
                                class="form-input-modern text-sm @error('role') border-rose-300 ring-1 ring-rose-300 @enderror">
                            <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="event_admin" {{ old('role', $user->role) == 'event_admin' ? 'selected' : '' }}>Event Admin</option>
                            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    @endif
                    @error('role')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Toggle & Notice -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 items-center">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Account Status</label>
                    @if($user->id === auth()->id() && $user->isSuperAdmin())
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-100 border border-slate-200 text-xs text-slate-600">
                            <input type="checkbox" checked disabled class="rounded border-slate-300 text-indigo-600">
                            <span>Active (Protected Self-Account)</span>
                            <input type="hidden" name="is_active" value="1">
                        </div>
                    @else
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <div class="text-xs font-bold text-slate-800">Allow System Login</div>
                                <div class="text-[11px] text-slate-500">Enable or disable account access without deleting user data.</div>
                            </div>
                        </label>
                    @endif
                </div>

                <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900">
                    <div class="font-bold flex items-center gap-1.5 mb-1">
                        Password Management
                    </div>
                    <p class="text-[11px] text-indigo-700">Users can reset their own password via the login screen or request a security link from an administrator.</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection