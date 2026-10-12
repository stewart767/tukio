@extends('layouts.dashboard')

@section('title', 'Users Management')
@section('page-title', 'Users & Access Control')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Executive Aura Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-indigo-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-indigo-400/20 to-purple-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-blue-400/15 to-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3.5 py-1 text-xs font-semibold text-indigo-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Identity &amp; Role Management
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">System Users &amp; Permissions</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-xl">Configure administrator roles, manage team staff accounts, and monitor access credentials.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.users.create') }}" class="group inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-600/40">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    <span>Add New User</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Strips -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-slate-200/60 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <span>Total Users</span>
                    <span class="text-indigo-600">👥</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $users->total() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Registered platform accounts</div>
            </div>

            <div class="rounded-2xl border border-purple-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-purple-600">
                    <span>Super Admins</span>
                    <span>👑</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ \App\Models\User::where('role', 'super_admin')->count() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Full system privileges</div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-blue-600">
                    <span>Event Admins</span>
                    <span>⚡</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ \App\Models\User::where('role', 'event_admin')->count() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Operations managers</div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    <span>Staff Members</span>
                    <span>🛡️</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ \App\Models\User::where('role', 'staff')->count() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Field coordinators</div>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-teal-50 to-green-50 px-6 py-4 text-emerald-900 shadow-md">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    </div>
    @endif

    @if($errors->any() || session('error'))
    <div class="relative overflow-hidden rounded-2xl border border-rose-200 bg-gradient-to-r from-rose-50 to-red-50 px-6 py-4 text-rose-900 shadow-md">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="space-y-1">
                <div class="text-sm font-bold">Action Required</div>
                <ul class="text-xs space-y-1 list-disc list-inside text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    @if(session('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Modern Users Table Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 font-heading">User Directory</h2>
                <p class="text-xs text-slate-500 mt-0.5">Showing system administrators and designated event personnel</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Active Directory Synchronized</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs sm:text-sm">
                <thead class="bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 sm:px-8 py-4">User Account</th>
                        <th class="px-6 py-4">Assigned Role</th>
                        <th class="px-6 py-4">Phone / Contact</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date Added</th>
                        <th class="px-6 sm:px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($users as $user)
                    <tr class="group hover:bg-indigo-50/40 transition-colors duration-200">
                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3.5">
                                <div class="relative flex-shrink-0">
                                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr {{ $user->role === 'super_admin' ? 'from-purple-600 to-indigo-600 text-white' : ($user->role === 'event_admin' ? 'from-blue-600 to-cyan-600 text-white' : 'from-emerald-600 to-teal-600 text-white') }} flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-105 transition-transform duration-200">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-400' }} border-2 border-white"></span>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors flex items-center gap-2">
                                        <span>{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="px-1.5 py-0.5 rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-extrabold uppercase">You</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs">
                                    <span>👑</span> Super Admin
                                </span>
                            @elseif($user->role === 'event_admin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs">
                                    <span>⚡</span> Event Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                                    <span>🛡️</span> Staff
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->phone)
                                <div class="font-mono text-xs text-slate-700">{{ $user->phone }}</div>
                            @else
                                <span class="text-xs text-slate-400 italic">Unspecified</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>

                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="View Profile">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                @if($user->id !== auth()->id() || !$user->isSuperAdmin())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete User">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="max-w-sm mx-auto text-center space-y-4">
                                <div class="w-16 h-16 bg-indigo-50 rounded-2xl mx-auto flex items-center justify-center text-indigo-600 text-2xl shadow-inner">
                                    👥
                                </div>
                                <h3 class="text-base font-bold text-slate-900">No users found</h3>
                                <p class="text-xs text-slate-500">No user records currently match the query. Get started by provisioning an administrator or staff member.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn-primary text-xs">
                                    Create First User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
