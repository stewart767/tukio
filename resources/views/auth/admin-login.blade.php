@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Admin Login - ' . $brandName)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Admin Login
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Sign in to access the admin dashboard
            </p>
        </div>

        <!-- Login Form Card -->
        <div class="max-w-md mx-auto">
            <div class="card-3d card-3d-hero p-8 md:p-12">
                <!-- Success Message -->
                @if(session('status'))
                <div class="alert alert-success mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                <div class="alert alert-error mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold mb-1">Sign in failed</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="form-input @error('email') form-input-error @enderror" 
                               placeholder="admin@example.com"
                               value="{{ old('email') }}"
                               autocomplete="email">
                        @error('email')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="form-label">
                                Password <span class="text-red-500">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="form-input @error('password') form-input-error @enderror" 
                               placeholder="Enter your password"
                               autocomplete="current-password">
                        @error('password')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    
                    <button type="submit" class="submit-button">
                        <span>Sign in</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-600">
                        Need help? 
                        <a href="{{ route('contact') }}" class="font-medium text-purple-600 hover:text-purple-700 transition-colors">
                            Contact support
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Form Styles */
    .form-group {
        position: relative;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #f9fafb;
    }

    .form-input:focus {
        outline: none;
        border-color: #9333ea;
        background: white;
        box-shadow: 0 0 0 4px rgba(147, 51, 234, 0.1);
        transform: translateY(-2px);
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .submit-button {
        width: 100%;
        padding: 16px 24px;
        background: linear-gradient(135deg, #9333ea 0%, #db2777 50%, #c026d3 100%);
        color: white;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 
            0 6px 20px rgba(147, 51, 234, 0.3),
            0 3px 10px rgba(219, 39, 119, 0.2);
        position: relative;
        overflow: hidden;
    }

    .submit-button::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .submit-button:hover {
        transform: translateY(-3px);
        box-shadow: 
            0 10px 30px rgba(147, 51, 234, 0.4),
            0 5px 15px rgba(219, 39, 119, 0.3);
    }

    .submit-button:hover::before {
        opacity: 1;
    }

    .submit-button:active {
        transform: translateY(-1px);
    }

    .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Alert Messages */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        border: 2px solid;
        display: flex;
        align-items: flex-start;
    }

    .alert-success {
        background: #f0fdf4;
        border-color: #86efac;
        color: #166534;
    }

    .alert-error {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #991b1b;
    }

    /* Form Error Messages */
    .form-input-error {
        border-color: #ef4444 !important;
        background: #fef2f2 !important;
    }

    .form-error-message {
        margin-top: 6px;
        font-size: 0.875rem;
        color: #ef4444;
        display: flex;
        align-items: center;
    }

    .form-error-message::before {
        content: '⚠';
        margin-right: 4px;
    }
</style>
@endpush
@endsection
