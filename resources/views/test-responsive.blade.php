@extends('layouts.app')

@section('title', 'Responsive Test')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 font-display mb-6">
                Responsive Design Test
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Testing responsive breakpoints and mobile-first design principles
            </p>
        </div>

        <!-- Breakpoint Test Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="bg-white rounded-2xl p-6 shadow-lg card-hover-enhanced">
                <div class="text-2xl font-bold text-gray-900 mb-2">Mobile</div>
                <div class="text-gray-600">sm: 640px</div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg card-hover-enhanced">
                <div class="text-2xl font-bold text-gray-900 mb-2">Tablet</div>
                <div class="text-gray-600">md: 768px</div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg card-hover-enhanced">
                <div class="text-2xl font-bold text-gray-900 mb-2">Laptop</div>
                <div class="text-gray-600">lg: 1024px</div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg card-hover-enhanced">
                <div class="text-2xl font-bold text-gray-900 mb-2">Desktop</div>
                <div class="text-gray-600">xl: 1280px</div>
            </div>
        </div>

        <!-- Flexbox Test -->
        <div class="bg-white rounded-2xl p-8 shadow-lg mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Flexbox Responsive Layout</h2>
            
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Column 1</h3>
                    <p class="text-gray-600">
                        This column will stack on mobile and become a row on medium screens and above.
                    </p>
                </div>
                
                <div class="flex-1 bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Column 2</h3>
                    <p class="text-gray-600">
                        Flexible container that adapts to screen size changes with responsive design.
                    </p>
                </div>
                
                <div class="flex-1 bg-gradient-to-br from-green-50 to-teal-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Column 3</h3>
                    <p class="text-gray-600">
                        Modern responsive design ensures optimal viewing experience across all devices.
                    </p>
                </div>
            </div>
        </div>

        <!-- Grid Test -->
        <div class="bg-white rounded-2xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">CSS Grid Responsive Layout</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Grid Item 1</h3>
                    <p class="text-gray-600">
                        Responsive grid that adapts from 1 column on mobile to 3 columns on large screens.
                    </p>
                </div>
                
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Grid Item 2</h3>
                    <p class="text-gray-600">
                        Modern CSS Grid provides powerful layout capabilities for complex designs.
                    </p>
                </div>
                
                <div class="bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Grid Item 3</h3>
                    <p class="text-gray-600">
                        Flexible grid system that maintains proper spacing and alignment on all devices.
                    </p>
                </div>
                
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Grid Item 4</h3>
                    <p class="text-gray-600">
                        Responsive design ensures content remains readable and accessible on any screen size.
                    </p>
                </div>
                
                <div class="bg-gradient-to-br from-violet-50 to-purple-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Grid Item 5</h3>
                    <p class="text-gray-600">
                        Modern web design principles applied to create beautiful and functional layouts.
                    </p>
                </div>
                
                <div class="bg-gradient-to-br from-fuchsia-50 to-pink-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Grid Item 6</h3>
                    <p class="text-gray-600">
                        Mobile-first approach ensures optimal performance and user experience on all devices.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection