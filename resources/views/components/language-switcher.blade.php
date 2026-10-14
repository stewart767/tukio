@php
    $currentLocale = app()->getLocale();
    $otherLocale = $currentLocale === 'en' ? 'sw' : 'en';
    $currentLanguage = $currentLocale === 'en' ? __('common.english') : __('common.kiswahili');
    $otherLanguage = $otherLocale === 'en' ? __('common.english') : __('common.kiswahili');
    $switcherId = 'lang-switcher-' . uniqid();
@endphp

<div class="language-switcher relative" id="{{ $switcherId }}">
    <button 
        onclick="toggleLanguageSwitcher(this)"
        class="flex items-center space-x-1.5 h-10 px-3 rounded-xl bg-slate-100/80 hover:bg-slate-200/70 border border-slate-200/80 text-slate-700 hover:text-slate-950 transition-all duration-200 text-xs font-semibold whitespace-nowrap shadow-2xs"
        aria-label="{{ __('common.switch_language') }}"
        aria-expanded="false"
    >
        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
        </svg>
        <span class="tracking-wider uppercase font-bold">{{ strtoupper($currentLocale) }}</span>
        <svg class="w-3 h-3 text-slate-500 transition-transform duration-200 language-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div 
        class="language-switcher-dropdown absolute right-0 mt-2 w-40 bg-white rounded-2xl shadow-xl border border-slate-200 py-1.5 z-50 hidden"
    >
        <a 
            href="{{ route('language.switch', ['locale' => 'en']) }}"
            class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-slate-950 rounded-xl mx-1 transition-colors {{ $currentLocale === 'en' ? 'bg-emerald-50 text-emerald-900 font-bold' : '' }}"
        >
            <div class="flex items-center space-x-2">
                <span class="text-[10px] font-bold px-1 py-0.5 rounded bg-slate-100 text-slate-600">EN</span>
                <span>English</span>
            </div>
            @if($currentLocale === 'en')
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            @endif
        </a>
        <a 
            href="{{ route('language.switch', ['locale' => 'sw']) }}"
            class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-slate-950 rounded-xl mx-1 transition-colors {{ $currentLocale === 'sw' ? 'bg-emerald-50 text-emerald-900 font-bold' : '' }}"
        >
            <div class="flex items-center space-x-2">
                <span class="text-[10px] font-bold px-1 py-0.5 rounded bg-slate-100 text-slate-600">SW</span>
                <span>Kiswahili</span>
            </div>
            @if($currentLocale === 'sw')
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            @endif
        </a>
    </div>
</div>

<script>
if (typeof toggleLanguageSwitcher === 'undefined') {
    function toggleLanguageSwitcher(button) {
        const switcher = button.closest('.language-switcher');
        const dropdown = switcher.querySelector('.language-switcher-dropdown');
        const arrow = switcher.querySelector('.language-arrow');
        const isOpen = !dropdown.classList.contains('hidden');
        
        // Close all other dropdowns
        document.querySelectorAll('.language-switcher-dropdown').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('.language-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
        
        if (isOpen) {
            dropdown.classList.add('hidden');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
            button.setAttribute('aria-expanded', 'false');
        } else {
            dropdown.classList.remove('hidden');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
            button.setAttribute('aria-expanded', 'true');
        }
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.language-switcher')) {
            document.querySelectorAll('.language-switcher-dropdown').forEach(d => d.classList.add('hidden'));
            document.querySelectorAll('.language-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
        }
    });
}
</script>
