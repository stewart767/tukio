@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Register - ' . $brandName)

@section('content')
<div class="min-h-screen bg-slate-50 pt-10 pb-20 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="text-center space-y-3 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center text-white text-2xl mx-auto shadow-lg shadow-indigo-500/20">
                🚀
            </div>
            <h1 class="text-3xl font-black font-heading text-slate-900 tracking-tight">
                Create an Account
            </h1>
            <p class="text-slate-600 text-xs sm:text-sm">
                Join {{ $brandName }} and get started today
            </p>
        </div>

        <!-- Registration Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-2xl space-y-6">
            @if ($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                    <div class="font-bold">Please check the following errors:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-semibold text-slate-700">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" autofocus
                           class="form-input-modern text-sm" placeholder="e.g. John Doe">
                    @error('name')
                        <p class="text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="email" class="block text-xs font-semibold text-slate-700">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                           class="form-input-modern text-sm" placeholder="you@example.com">
                    @error('email')
                        <p class="text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-xs font-semibold text-slate-700">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="form-input-modern text-sm" placeholder="Create a secure password">
                    @error('password')
                        <p class="text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="form-input-modern text-sm" placeholder="Confirm your password">
                </div>

                <div class="pt-2">
                    <label class="flex items-start space-x-2 cursor-pointer">
                        <input id="terms" name="terms" type="checkbox" required class="mt-0.5 w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-xs text-slate-600">
                            I agree to the <a href="{{ route('page.show', 'terms-of-use') }}" class="text-indigo-600 underline">Terms of Service</a> and <a href="{{ route('page.show', 'privacy-policy') }}" class="text-indigo-600 underline">Privacy Policy</a>
                        </span>
                    </label>
                    @error('terms')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full text-center text-sm py-3.5 shadow-lg">
                    <span>Create Account</span>
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                <p>Already have an account? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Sign in here</a></p>
            </div>
        </div>

    </div>
</div>
@endsection
