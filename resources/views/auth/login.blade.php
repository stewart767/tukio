@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Sign In - ' . $brandName)

@section('content')
<div class="min-h-screen bg-slate-50 pt-10 pb-20 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="text-center space-y-3 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center text-white text-2xl mx-auto shadow-lg shadow-indigo-500/20">
                🔐
            </div>
            <h1 class="text-3xl font-black font-heading text-slate-900 tracking-tight">
                Welcome Back
            </h1>
            <p class="text-slate-600 text-xs sm:text-sm">
                Sign in to manage your events, invitations &amp; guests
            </p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-2xl space-y-6">
            @if (session('status'))
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                    <div class="font-bold">Please check your login details:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ Route::currentRouteName() === 'admin.login' ? route('admin.login') : route('login') }}" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-semibold text-slate-700">
                        Email or Phone Number <span class="text-rose-500">*</span>
                    </label>
                    <input id="email" name="email" type="text" value="{{ old('email') }}" required autocomplete="username" autofocus
                           class="form-input-modern text-sm" placeholder="you@example.com or 0712345678">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="form-input-modern text-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-xs text-slate-600 font-medium">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full text-center text-sm py-3 shadow-lg">
                    <span>Sign In to Account</span>
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500 space-y-2">
                <p>Don't have an account? <a href="{{ route('host.register') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Host an Event Free</a></p>
            </div>
        </div>

    </div>
</div>
@endsection
