@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Digital Invitation Templates - ' . $brandName)

@section('content')
<div class="min-h-screen bg-ambient-mesh-dark text-white relative overflow-hidden pt-10 pb-20">
    <!-- Ambient Floating Glow Orbs -->
    <div class="absolute top-20 left-1/4 -translate-x-1/2 w-[550px] h-[550px] bg-indigo-600/20 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[500px] h-[500px] bg-pink-600/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-10 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 relative z-10">
        
        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="glass-tag-transparent px-3.5 py-1.5 rounded-full text-pink-300 border border-pink-500/30 text-xs font-semibold">Designer Templates</span>
            <h1 class="text-4xl sm:text-5xl font-black font-heading text-white tracking-tight">
                Digital Invitation Cards
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                Choose from dozens of professionally crafted invitation templates ready for automated WhatsApp delivery and instant QR gate passes.
            </p>
        </div>

        <!-- Superadmin Bar if logged in -->
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <div class="glass-card-transparent rounded-2xl p-4 flex items-center justify-between border-indigo-400/30 bg-indigo-950/40">
            <div class="flex items-center space-x-3">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-ping"></span>
                <span class="text-xs font-bold text-white">Admin Mode Active: Manage Templates</span>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.templates.create') }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-500 transition-colors">
                    + Add New Template
                </a>
                <a href="{{ route('admin.templates.index') }}" class="px-3 py-1.5 bg-white/10 text-white border border-white/20 rounded-xl text-xs font-semibold hover:bg-white/20 transition-colors">
                    Admin Panel
                </a>
            </div>
        </div>
        @endif

        <!-- Filter & Search Bar (Transparent Glass Panel) -->
        <div class="glass-card-transparent rounded-3xl p-4 sm:p-6 shadow-xl border border-white/20">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative w-full md:w-80">
                    <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="template-search" type="text" placeholder="Search templates by theme..." class="w-full pl-10 pr-4 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs rounded-xl border border-white/15 focus:outline-none focus:ring-2 focus:ring-pink-400 transition-all">
                </div>

                <!-- Category Filters (Transparent Glass Buttons) -->
                <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
                    <button type="button" class="tab-chip active text-xs whitespace-nowrap filter-tpl-btn" data-cat="all">All Designs</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-tpl-btn" data-cat="wedding">Weddings</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-tpl-btn" data-cat="birthday">Birthdays</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-tpl-btn" data-cat="corporate">Corporate</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-tpl-btn" data-cat="send_off">Send-Off</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-tpl-btn" data-cat="kitchen_party">Kitchen Party</button>
                </div>
            </div>
        </div>

        <!-- Templates Grid (Transparent Glass Cards Containing Images) -->
        @if($templates->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="templates-grid">
                @foreach($templates as $template)
                    @php
                        $catKey = strtolower(str_replace([' ', '-'], '_', $template->category ?? 'wedding'));
                        $categoryFallbacks = [
                            'wedding' => asset('media/categories/wedding.jpg'),
                            'corporate' => asset('media/categories/corporate.jpg'),
                            'birthday' => asset('media/categories/birthday.jpg'),
                            'kitchen_party' => asset('media/categories/kitchen_party.jpg'),
                            'send_off' => asset('media/categories/sendoff.jpg'),
                            'sendoff' => asset('media/categories/sendoff.jpg'),
                            'traditional' => asset('media/categories/wedding.jpg'),
                            'concert' => asset('media/categories/concert.jpg'),
                        ];
                        $fallbackImg = $categoryFallbacks[$catKey] ?? asset('media/categories/wedding.jpg');
                        $cardImg = !empty($template->preview_image) ? storage_url($template->preview_image) : $fallbackImg;
                    @endphp
                    <div class="glass-card-transparent rounded-3xl overflow-hidden group template-item border border-white/15 hover:border-pink-500/50 transition-all duration-300 relative" data-cat="{{ strtolower($template->category ?? 'other') }}">
                        <div class="relative h-64 bg-slate-950 overflow-hidden">
                            <img src="{{ $cardImg }}" alt="{{ $template->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null; this.src='{{ $fallbackImg }}';"
                                 loading="lazy">

                            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                <div class="absolute top-3 left-3 z-20 flex items-center gap-1.5 bg-slate-950/90 backdrop-blur-md px-2.5 py-1 rounded-xl border border-purple-500/40 shadow-lg">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <a href="{{ route('admin.templates.edit', $template->id) }}" class="text-[11px] font-bold text-purple-300 hover:text-white flex items-center gap-1 transition-colors" title="Edit in Admin">
                                        <svg class="w-3 h-3 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            @else
                                <div class="absolute top-3 left-3">
                                    <span class="glass-tag-transparent px-2.5 py-1 rounded-full text-[10px] font-bold">
                                        {{ ucfirst(str_replace('_', ' ', $template->category ?? 'Event')) }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4 backdrop-blur-xs">
                                <a href="{{ route('templates.show', $template) }}" class="px-4 py-2 bg-white text-slate-900 font-bold text-xs rounded-xl shadow-lg hover:bg-slate-100 transition-colors">
                                    Live Preview &rarr;
                                </a>
                            </div>
                        </div>

                        <div class="p-5 space-y-3">
                            <div>
                                <h3 class="font-bold text-white text-sm truncate group-hover:text-pink-400 transition-colors">{{ $template->name }}</h3>
                                <p class="text-slate-300 text-xs line-clamp-2 mt-0.5">
                                    {{ $template->description ?? 'Customizable digital invitation card with RSVP and QR pass.' }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-white/10">
                                <span class="text-[11px] text-emerald-400 font-bold glass-tag-transparent px-2 py-0.5 rounded-md">
                                    ✓ WhatsApp Ready
                                </span>
                                <div class="flex items-center gap-2">
                                    @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                        <a href="{{ route('admin.templates.edit', $template->id) }}" class="text-xs font-semibold text-purple-400 hover:text-purple-300 transition-colors">
                                            Edit &rarr;
                                        </a>
                                    @endif
                                    <a href="{{ route('templates.show', $template->id) }}" class="text-xs font-semibold text-pink-400 hover:text-pink-300 transition-colors">
                                        Use &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $templates->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 glass-card-transparent rounded-3xl border border-white/15 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-3xl mx-auto">
                    🎨
                </div>
                <h3 class="text-xl font-bold text-white">No Templates Found</h3>
                <p class="text-slate-300 text-sm max-w-sm mx-auto">Check back soon for new designer invitation templates.</p>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-tpl-btn');
    const items = document.querySelectorAll('.template-item');
    const searchInput = document.getElementById('template-search');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const cat = this.getAttribute('data-cat');

            items.forEach(item => {
                if (cat === 'all' || item.getAttribute('data-cat').includes(cat)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
