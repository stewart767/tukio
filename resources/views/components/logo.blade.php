@props(['variant' => 'default', 'showTagline' => true, 'size' => 'default'])

@php
    $sizeClasses = [
        'small' => ['icon' => 'w-12 h-12', 'card' => 'w-9 h-6', 'text' => 'text-xl', 'tagline' => 'text-[10px]', 'gap' => 'gap-2'],
        'default' => ['icon' => 'w-16 h-16', 'card' => 'w-12 h-8', 'text' => 'text-3xl', 'tagline' => 'text-xs', 'gap' => 'gap-3'],
        'large' => ['icon' => 'w-20 h-20', 'card' => 'w-14 h-9', 'text' => 'text-4xl', 'tagline' => 'text-sm', 'gap' => 'gap-4']
    ];
    
    $sizes = $sizeClasses[$size] ?? $sizeClasses['default'];
    
    $isLight = $variant === 'light' || $variant === 'white';
    $isDark = $variant === 'dark';
    
    $textColor = $isLight ? 'text-white' : ($isDark ? 'text-gray-900' : 'bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent');
    $taglineColor = 'text-black';
@endphp

<div class="flex items-center {{ $sizes['gap'] }} {{ $attributes->get('class') }}">
    <div class="logo-icon relative {{ $sizes['icon'] }} group">
        <!-- Card 1 -->
        <div class="card card-1 absolute {{ $sizes['card'] }} rounded-lg shadow-md transition-all duration-300 {{ $isLight ? 'bg-gradient-to-br from-white/20 to-white/10' : 'bg-gradient-to-br from-white to-gray-100' }}" style="top: 0; left: 8px; transform: rotate(-10deg); z-index: 1;">
            <div class="card-detail absolute bottom-1.5 right-1.5 w-3 h-0.5 {{ $isLight ? 'bg-white/80' : 'bg-gray-400/50' }} rounded"></div>
        </div>
        <!-- Card 2 -->
        <div class="card card-2 absolute {{ $sizes['card'] }} bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg shadow-lg transition-all duration-300 {{ $isLight ? 'from-primary-400 to-primary-500' : '' }}" style="top: 12px; left: 4px; transform: rotate(-5deg); z-index: 2;">
            <div class="card-detail absolute bottom-1.5 right-1.5 w-3 h-0.5 bg-white/80 rounded"></div>
        </div>
        <!-- Card 3 -->
        <div class="card card-3 absolute {{ $sizes['card'] }} bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-lg shadow-lg transition-all duration-300 {{ $isLight ? 'from-secondary-400 to-secondary-500' : '' }}" style="top: 24px; left: 0; transform: rotate(0deg); z-index: 3;">
            <div class="card-detail absolute bottom-1.5 right-1.5 w-3 h-0.5 bg-white/80 rounded"></div>
        </div>
    </div>
    <div class="text flex flex-col gap-0.5">
        <div class="brand-name {{ $sizes['text'] }} font-bold {{ $textColor }} leading-tight tracking-tight">
            Tukio 360
        </div>
        @if($showTagline)
            <div class="tagline {{ $sizes['tagline'] }} {{ $taglineColor }} font-medium uppercase tracking-wider leading-tight">
                Event Management
            </div>
        @endif
    </div>
</div>

<style>
    .logo-icon:hover .card-1 {
        transform: rotate(-15deg) translateX(-2px) !important;
    }
    .logo-icon:hover .card-2 {
        transform: rotate(-5deg) translateY(-2px) !important;
    }
    .logo-icon:hover .card-3 {
        transform: rotate(5deg) translateX(2px) !important;
    }
</style>
