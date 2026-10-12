@props([
    'images' => [],
    'title' => 'MOMENTS & MEMORIES',
    'class' => ''
])

@if(!empty($images) && count($images) > 0)
<div x-data="{ activeImage: null }" class="w-full max-w-4xl mx-auto my-12 px-4 {{ $class }}">
    @if($title)
        <div class="text-center mb-6">
            <span class="text-xs uppercase tracking-[0.25em] font-extrabold opacity-80 inline-block px-4 py-1 rounded-full border border-current">
                {{ $title }}
            </span>
        </div>
    @endif

    <!-- Image Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
        @foreach($images as $index => $image)
            <div @click="activeImage = '{{ $image }}'" 
                 class="group relative aspect-square overflow-hidden rounded-2xl bg-black/20 cursor-pointer border border-white/20 shadow-md transition-all duration-300 hover:scale-[1.03] hover:shadow-xl">
                <img src="{{ $image }}" alt="Gallery photo" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <span class="p-2.5 rounded-full bg-white/30 backdrop-blur-md text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Lightbox Modal -->
    <div x-show="activeImage" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="activeImage = null"
         class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4">
        
        <button @click="activeImage = null" class="absolute top-6 right-6 text-white text-3xl font-bold bg-white/10 w-12 h-12 rounded-full flex items-center justify-center hover:bg-white/20">
            &times;
        </button>

        <div @click.stop class="max-w-3xl max-h-[85vh] rounded-2xl overflow-hidden shadow-2xl border border-white/20">
            <img :src="activeImage" alt="Enlarged photo" class="w-full h-full object-contain max-h-[80vh]">
        </div>
    </div>
</div>
@endif
