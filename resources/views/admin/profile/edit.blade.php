@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Modern Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-purple-50 to-pink-50 rounded-3xl shadow-xl border border-purple-100 p-8">
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-200/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-pink-200/20 to-transparent rounded-full translate-y-12 -translate-x-12"></div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
                        <p class="text-gray-600">Update your account information</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-xl border border-gray-300 shadow-sm hover:bg-gray-50 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
            <p class="text-gray-600 text-sm mt-1">Update your personal details</p>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('phone') border-red-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Account Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Role</label>
                                <p class="text-purple-900 font-semibold">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                                <p class="text-xs text-purple-600 mt-1">Your role cannot be changed here</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Member Since</label>
                                <p class="text-purple-900 font-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Password</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>To change your password, go to your <a href="{{ route('admin.profile.show') }}" class="font-semibold underline">profile page</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.profile.show') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
