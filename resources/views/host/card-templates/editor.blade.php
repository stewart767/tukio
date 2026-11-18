@extends('layouts.host')

@section('title', 'Visual Card Studio - ' . $template->name)
@section('page-title', 'Card Template Visual Editor')

@section('content')
<div class="space-y-6 pb-16 max-w-7xl mx-auto" id="editor-app">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-r from-slate-900 via-purple-950 to-indigo-950 p-6 sm:p-8 text-white shadow-xl shadow-purple-950/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-500/20 border border-purple-400/30 px-3 py-1 text-xs font-semibold text-purple-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        CUSTOM HOST TEMPLATE
                    </span>
                    <span class="text-xs text-slate-300">| {{ ucfirst($template->category) }} &bull; {{ $template->artwork_width }}&times;{{ $template->artwork_height }}px</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading">{{ $template->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl">
                    Position your text zones, upload and fit your couple picture, and place the digital entrance QR pass. Coordinates are normalized and saved for automated WhatsApp and PDF delivery.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('host.card-templates.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 px-3.5 py-2.5 text-xs font-bold text-white transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Card Studio</span>
                </a>

                <form action="{{ route('host.card-templates.reanalyze', $template) }}" method="POST" onsubmit="return confirm('Re-run automated AI layout detection? Unsaved changes will be replaced.')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs font-bold text-white transition-all">
                        <svg class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Re-Analyze AI</span>
                    </button>
                </form>

                <a href="{{ route('host.card-templates.preview', $template) }}" target="_blank" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs font-bold text-white transition-all">
                    <svg class="h-4 w-4 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Live Preview</span>
                </a>

                <button type="button" id="btn-save-draft" class="inline-flex items-center gap-2 rounded-2xl bg-purple-600 hover:bg-purple-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-purple-900/50 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span>Save Layout</span>
                </button>

                <button type="button" id="btn-save-and-dispatch" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-950/50 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>Send Invitations</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Status Notice -->
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-800 text-sm">
            <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 flex items-center gap-3 text-amber-800 text-sm">
            <svg class="w-5 h-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <!-- Save Notification Toast (Hidden by default) -->
    <div id="save-toast" class="hidden fixed bottom-6 right-6 z-50 rounded-2xl bg-emerald-600 text-white px-5 py-3 shadow-2xl flex items-center gap-3 animate-fade-in text-xs font-bold">
        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="save-toast-message">Layout saved successfully!</span>
    </div>

    <!-- Main Workspace: Canvas + Controls -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: The Interactive Artwork Canvas (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            <!-- Canvas Toolbar -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-700">Test Guest Name:</span>
                    <button type="button" class="btn-sample-name px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 hover:bg-purple-50 hover:border-purple-300 font-semibold text-slate-700" data-name="{{ $sampleNames['short'] }}">Short</button>
                    <button type="button" class="btn-sample-name px-2.5 py-1 rounded-lg border border-purple-300 bg-purple-50 text-purple-700 font-bold" data-name="{{ $sampleNames['standard'] }}">Standard</button>
                    <button type="button" class="btn-sample-name px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 hover:bg-purple-50 hover:border-purple-300 font-semibold text-slate-700" data-name="{{ $sampleNames['long'] }}">Long &amp; Formal</button>
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-1.5 cursor-pointer text-slate-600 font-semibold">
                        <input type="checkbox" id="toggle-grid" checked class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        <span>Show Guidelines</span>
                    </label>
                    <button type="button" id="btn-reset-original" class="text-rose-600 hover:text-rose-700 font-bold">Reset Defaults</button>
                </div>
            </div>

            <!-- Canvas Viewport with Artwork Background -->
            <div class="relative bg-slate-900/5 rounded-3xl p-4 sm:p-8 border border-slate-200 shadow-inner flex justify-center overflow-hidden">
                <div id="canvas-container" class="relative select-none shadow-2xl rounded-2xl overflow-hidden bg-white" style="width: 100%; max-width: {{ $template->aspect_ratio > 1 ? '840px' : '580px' }}; aspect-ratio: {{ $template->aspect_ratio }};">
                    <!-- Artwork Image -->
                    <img id="artwork-image" 
                         src="{{ $template->artwork_url }}" 
                         alt="{{ $template->name }}" 
                         class="absolute inset-0 w-full h-full object-contain pointer-events-none user-select-none">

                    <!-- Overlay Draggable Boxes Layer -->
                    <div id="overlays-layer" class="absolute inset-0 w-full h-full pointer-events-auto"></div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 bg-purple-50/50 border border-purple-100 rounded-2xl p-3">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Click and drag any zone to reposition. Drag corner handles to resize.</span>
                </span>
                <span class="font-bold text-purple-700">Normalized coordinates ensure exact fit on WhatsApp &amp; Print</span>
            </div>
        </div>

        <!-- Right Column: Field Property Inspector & Settings (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Active Selected Field Inspector Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-md space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Selected Zone</span>
                        <h3 class="text-base font-bold text-slate-900" id="selected-field-title">No zone selected</h3>
                    </div>
                    <span id="selected-field-badge" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-100 text-slate-700">--</span>
                </div>

                <div id="inspector-form" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Display Label</label>
                        <input type="text" id="prop-label" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <!-- Photo Zone Controls (Visible only when an image/photo zone is selected) -->
                    <div id="photo-controls-section" class="space-y-4 pt-2 border-t border-slate-100 hidden">
                        <div class="rounded-2xl bg-purple-50/70 border border-purple-200 p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-purple-900 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Couple Picture Upload</span>
                                </span>
                            </div>

                            <input type="file" id="photo-file-input" accept="image/jpeg,image/png,image/webp" class="sr-only">
                            <button type="button" id="btn-trigger-photo-upload" class="w-full py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-xs font-bold text-white shadow-sm transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span id="photo-upload-label">Upload Couple Photo</span>
                            </button>

                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Image Fit</label>
                                    <select id="prop-img-fit" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500">
                                        <option value="cover">Fill Frame (Cover)</option>
                                        <option value="contain">Fit Full Photo (Contain)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Corner Shape</label>
                                    <select id="prop-img-radius" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500">
                                        <option value="0px">Square (0px)</option>
                                        <option value="8px">Slightly Soft (8px)</option>
                                        <option value="16px">Rounded (16px)</option>
                                        <option value="28px">Pill / Soft (28px)</option>
                                        <option value="9999px">Circle / Oval Cameo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Frame Border</label>
                                    <select id="prop-img-border-w" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500">
                                        <option value="0">No Border</option>
                                        <option value="1">1px Thin</option>
                                        <option value="2">2px Normal</option>
                                        <option value="3">3px Prominent</option>
                                        <option value="4">4px Bold Frame</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Border Color</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" id="prop-img-border-color-picker" value="#c59b27" class="h-8 w-8 rounded-lg cursor-pointer border border-slate-200 p-0.5">
                                        <input type="text" id="prop-img-border-color" value="#c59b27" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono uppercase">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Typography Controls (Visible for text zones) -->
                    <div id="text-controls-section" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Font Family</label>
                                <select id="prop-font" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="Playfair Display">Playfair Display (Serif)</option>
                                    <option value="Cinzel">Cinzel (Roman Luxe)</option>
                                    <option value="Great Vibes">Great Vibes (Calligraphy)</option>
                                    <option value="Cormorant Garamond">Cormorant Garamond</option>
                                    <option value="Georgia">Georgia (Classic)</option>
                                    <option value="Montserrat">Montserrat (Modern)</option>
                                    <option value="Inter">Inter (Sans)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Font Size (pt)</label>
                                <input type="number" id="prop-size" min="10" max="72" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Text Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" id="prop-color-picker" class="h-8 w-8 rounded-lg cursor-pointer border border-slate-200 p-0.5">
                                    <input type="text" id="prop-color" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono uppercase focus:ring-2 focus:ring-purple-500">
                                </div>
                                <!-- Luxury Color Presets -->
                                <div class="flex items-center gap-1.5 pt-2">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">Preset:</span>
                                    <button type="button" class="btn-color-swatch h-5 w-5 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110" style="background-color: #D4AF37;" data-color="#D4AF37" title="Gold"></button>
                                    <button type="button" class="btn-color-swatch h-5 w-5 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110" style="background-color: #FFFFFF;" data-color="#FFFFFF" title="White"></button>
                                    <button type="button" class="btn-color-swatch h-5 w-5 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110" style="background-color: #F5E6C8;" data-color="#F5E6C8" title="Warm Cream"></button>
                                    <button type="button" class="btn-color-swatch h-5 w-5 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110" style="background-color: #B76E79;" data-color="#B76E79" title="Rose Gold"></button>
                                    <button type="button" class="btn-color-swatch h-5 w-5 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110" style="background-color: #111827;" data-color="#111827" title="Dark"></button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Alignment</label>
                                <div class="grid grid-cols-3 gap-1 bg-slate-100 p-1 rounded-xl">
                                    <button type="button" class="btn-align p-1.5 rounded-lg text-slate-600 hover:bg-white" data-align="left" title="Left">
                                        <svg class="w-3.5 h-3.5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14"/></svg>
                                    </button>
                                    <button type="button" class="btn-align p-1.5 rounded-lg text-slate-600 bg-white font-bold" data-align="center" title="Center">
                                        <svg class="w-3.5 h-3.5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"/></svg>
                                    </button>
                                    <button type="button" class="btn-align p-1.5 rounded-lg text-slate-600 hover:bg-white" data-align="right" title="Right">
                                        <svg class="w-3.5 h-3.5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Text Content / Source Definition Section -->
                        <div id="text-source-section" class="pt-2 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700">Text Content / Source</label>
                                <span id="prop-source-badge" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">Dynamic</span>
                            </div>
                            <div id="dynamic-guest-banner" class="hidden rounded-xl bg-purple-50/80 border border-purple-200 p-2.5 text-[11px] text-purple-900 leading-tight">
                                <span class="font-bold">👥 Dynamic Guest Name:</span> Automatically populated with each guest's name from your Guest List when sending WhatsApp or generating entrance passes.
                            </div>
                            <div id="custom-text-wrapper" class="space-y-1">
                                <textarea id="prop-custom-text" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500" placeholder="Type custom text to override event default..."></textarea>
                                <p class="text-[10px] text-slate-500" id="prop-source-hint">Leave blank to use default information from Event Settings.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Coordinates Display -->
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80 grid grid-cols-4 gap-2 text-center text-[11px]">
                        <div><span class="text-slate-400 block">X</span><span id="coord-x" class="font-mono font-bold text-slate-800">0.00</span></div>
                        <div><span class="text-slate-400 block">Y</span><span id="coord-y" class="font-mono font-bold text-slate-800">0.00</span></div>
                        <div><span class="text-slate-400 block">W</span><span id="coord-w" class="font-mono font-bold text-slate-800">0.00</span></div>
                        <div><span class="text-slate-400 block">H</span><span id="coord-h" class="font-mono font-bold text-slate-800">0.00</span></div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                            <input type="checkbox" id="prop-editable" checked class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                            <span>Dynamic Guest Field</span>
                        </label>
                        <button type="button" id="btn-delete-field" class="text-rose-600 hover:text-rose-700 text-xs font-bold">Remove Zone</button>
                    </div>
                </div>
            </div>

            <!-- Intelligent QR Placement Assistant Card -->
            <div class="rounded-3xl border border-purple-100 bg-gradient-to-br from-purple-50/60 to-indigo-50/40 p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-purple-600 text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">QR Gate Pass Position</h4>
                        <p class="text-[11px] text-slate-500">Fast placement presets</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="bottom_right">Bottom Right</button>
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="bottom_left">Bottom Left</button>
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="bottom_center">Bottom Center</button>
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="top_right">Top Right</button>
                </div>

                <div id="qr-warning-box" class="hidden rounded-xl bg-amber-100/80 border border-amber-300 p-2.5 text-[11px] text-amber-900">
                    <span id="qr-warning-text">QR code may be too small for swift entrance scanning.</span>
                </div>
            </div>

            <!-- Add New Field Buttons -->
            <div class="space-y-2">
                <button type="button" id="btn-double-card-preset" class="w-full py-2.5 px-3 rounded-2xl bg-amber-50 hover:bg-amber-100/80 border border-amber-300 text-xs font-bold text-amber-900 shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    <span>Double Card Layout (Photo Left, Card Right)</span>
                </button>

                <button type="button" id="btn-add-couple-photo" class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-xs font-bold text-white shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>+ Add Couple Photo Frame</span>
                </button>

                <div class="flex items-center gap-2">
                    <button type="button" id="btn-add-text-field" class="flex-1 py-3 px-4 rounded-2xl bg-white border border-slate-200 hover:border-purple-300 text-xs font-bold text-slate-700 shadow-sm transition-all text-center">
                        + Add Text Line
                    </button>
                    <button type="button" id="btn-add-photo-field" class="flex-1 py-3 px-4 rounded-2xl bg-white border border-slate-200 hover:border-purple-300 text-xs font-bold text-slate-700 shadow-sm transition-all text-center">
                        + Secondary Photo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Draggable Overlay Box Styles */
.overlay-box {
    position: absolute;
    border: 2px dashed rgba(147, 51, 234, 0.7);
    background-color: rgba(168, 85, 247, 0.12);
    border-radius: 8px;
    cursor: move;
    transition: box-shadow 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 0;
    box-sizing: border-box;
    overflow: hidden;
}

.overlay-box:hover {
    border-color: rgba(147, 51, 234, 1);
    background-color: rgba(168, 85, 247, 0.20);
    box-shadow: 0 4px 14px rgba(147, 51, 234, 0.25);
}

.overlay-box.selected {
    border: 2px solid #7c3aed !important;
    background-color: rgba(124, 58, 237, 0.22) !important;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.35) !important;
    z-index: 50 !important;
}

.overlay-box.type-qr {
    border-color: #059669;
    background-color: rgba(16, 185, 129, 0.15);
}
.overlay-box.type-qr.selected {
    border-color: #047857 !important;
    background-color: rgba(16, 185, 129, 0.25) !important;
}

.overlay-box.type-image {
    border-color: #2563eb;
    background-color: rgba(37, 99, 235, 0.12);
}
.overlay-box.type-image.selected {
    border-color: #1d4ed8 !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.35) !important;
}

/* Resize Handles */
.resize-handle {
    position: absolute;
    width: 12px;
    height: 12px;
    background-color: white;
    border: 2px solid #7c3aed;
    border-radius: 3px;
    z-index: 60;
}
.resize-handle.nw { top: -6px; left: -6px; cursor: nwse-resize; }
.resize-handle.ne { top: -6px; right: -6px; cursor: nesw-resize; }
.resize-handle.se { bottom: -6px; right: -6px; cursor: nwse-resize; }
.resize-handle.sw { bottom: -6px; left: -6px; cursor: nesw-resize; }

.box-label-badge {
    position: absolute;
    top: 4px;
    left: 4px;
    background-color: rgba(124, 58, 237, 0.9);
    backdrop-filter: blur(4px);
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 4px;
    white-space: nowrap;
    pointer-events: none;
    z-index: 40;
}
.type-qr .box-label-badge {
    background-color: rgba(5, 150, 105, 0.9);
}
.type-image .box-label-badge {
    background-color: rgba(37, 99, 235, 0.9);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const initialFields = @json($fields);
    let fields = JSON.parse(JSON.stringify(initialFields));
    let selectedKey = null;
    let sampleName = 'Hon. Josephat M. Mwita';

    const container = document.getElementById('canvas-container');
    const layer = document.getElementById('overlays-layer');

    // Inspector inputs
    const propTitle = document.getElementById('selected-field-title');
    const propBadge = document.getElementById('selected-field-badge');
    const propLabel = document.getElementById('prop-label');
    const propFont = document.getElementById('prop-font');
    const propSize = document.getElementById('prop-size');
    const propColor = document.getElementById('prop-color');
    const propColorPicker = document.getElementById('prop-color-picker');
    const propEditable = document.getElementById('prop-editable');
    const coordX = document.getElementById('coord-x');
    const coordY = document.getElementById('coord-y');
    const coordW = document.getElementById('coord-w');
    const coordH = document.getElementById('coord-h');

    // Photo specific inputs
    const photoControls = document.getElementById('photo-controls-section');
    const textControls = document.getElementById('text-controls-section');
    const propImgFit = document.getElementById('prop-img-fit');
    const propImgRadius = document.getElementById('prop-img-radius');
    const propImgBorderW = document.getElementById('prop-img-border-w');
    const propImgBorderColor = document.getElementById('prop-img-border-color');
    const propImgBorderColorPicker = document.getElementById('prop-img-border-color-picker');
    const photoFileInput = document.getElementById('photo-file-input');
    const btnTriggerPhoto = document.getElementById('btn-trigger-photo-upload');

    // Render all bounding boxes
    function renderBoxes() {
        layer.innerHTML = '';

        for (const [key, field] of Object.entries(fields)) {
            const box = document.createElement('div');
            box.className = 'overlay-box type-' + (field.type || 'text');
            if (key === selectedKey) box.classList.add('selected');
            box.dataset.key = key;

            // Position with percentages
            box.style.left = (field.x * 100) + '%';
            box.style.top = (field.y * 100) + '%';
            box.style.width = (field.width * 100) + '%';
            box.style.height = (field.height * 100) + '%';

            // Badge
            const badge = document.createElement('div');
            badge.className = 'box-label-badge';
            badge.textContent = field.label || key;
            box.appendChild(badge);

            // Inner content rendering based on field type
            if (field.type === 'image' || key.includes('photo')) {
                if (field.photo_url) {
                    const img = document.createElement('img');
                    img.src = field.photo_url;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = field.fit || 'cover';
                    img.style.borderRadius = field.border_radius || '8px';
                    if (field.border_width && field.border_width > 0) {
                        img.style.border = `${field.border_width}px solid ${field.border_color || '#c59b27'}`;
                    }
                    img.className = 'pointer-events-none user-select-none';
                    box.appendChild(img);
                } else {
                    const placeholder = document.createElement('div');
                    placeholder.className = 'w-full h-full flex flex-col items-center justify-center p-2 text-center pointer-events-none text-purple-700 bg-purple-50/60';
                    placeholder.innerHTML = `
                        <svg class="w-6 h-6 mb-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-[11px] font-bold">Couple Photo Frame</span>
                        <span class="text-[9px] text-purple-500">Click to upload photo</span>
                    `;
                    box.appendChild(placeholder);
                }
            } else if (key === 'qr_code' || field.type === 'qr') {
                const qrDiv = document.createElement('div');
                qrDiv.className = 'w-full h-full p-1 bg-white rounded-lg shadow-sm border border-emerald-500 flex flex-col items-center justify-center text-center';
                qrDiv.innerHTML = `
                    <div class="w-full h-full flex items-center justify-center bg-slate-900 text-emerald-400 font-mono text-[9px] font-black rounded">
                        <span>QR PASS [OK]</span>
                    </div>
                `;
                box.appendChild(qrDiv);
            } else {
                const previewText = document.createElement('div');
                previewText.className = 'w-full h-full flex items-center justify-center overflow-hidden pointer-events-none px-2 text-xs font-semibold';
                previewText.style.fontFamily = field.font_family || 'Playfair Display';
                previewText.style.color = field.color || '#333333';
                previewText.style.textAlign = field.alignment || 'center';

                if (key === 'guest_name') {
                    previewText.textContent = sampleName;
                } else if (field.custom_text && field.custom_text.trim() !== '') {
                    previewText.textContent = field.custom_text;
                } else {
                    previewText.textContent = field.label || key;
                }
                box.appendChild(previewText);
            }

            // Resize handles if selected
            if (key === selectedKey) {
                ['nw', 'ne', 'se', 'sw'].forEach(pos => {
                    const h = document.createElement('div');
                    h.className = 'resize-handle ' + pos;
                    h.dataset.direction = pos;
                    box.appendChild(h);
                });
            }

            // Click to select
            box.addEventListener('mousedown', function(e) {
                if (e.target.classList.contains('resize-handle')) {
                    startResize(e, key, e.target.dataset.direction);
                } else {
                    selectField(key);
                    startDrag(e, key);
                }
            });

            layer.appendChild(box);
        }

        checkQrScannability();
    }

    // Select field and populate inspector
    function selectField(key) {
        selectedKey = key;
        const f = fields[key];
        if (!f) return;

        propTitle.textContent = f.label || key;
        propBadge.textContent = f.type ? f.type.toUpperCase() : 'TEXT';
        propLabel.value = f.label || key;
        propEditable.checked = f.is_editable !== false;

        const isPhotoZone = (f.type === 'image' || key.includes('photo'));
        if (isPhotoZone) {
            photoControls.classList.remove('hidden');
            textControls.classList.add('hidden');

            propImgFit.value = f.fit || 'cover';
            propImgRadius.value = f.border_radius || '8px';
            propImgBorderW.value = f.border_width !== undefined ? f.border_width : 2;
            const bColor = f.border_color || '#c59b27';
            propImgBorderColor.value = bColor;
            propImgBorderColorPicker.value = bColor.startsWith('#') ? bColor.substring(0, 7) : '#c59b27';

            document.getElementById('photo-upload-label').textContent = f.photo_url ? 'Replace Couple Photo' : 'Upload Couple Photo';
        } else {
            photoControls.classList.add('hidden');
            textControls.classList.remove('hidden');

            propFont.value = f.font_family || 'Playfair Display';
            propSize.value = f.font_size || 22;
            propColor.value = f.color || '#333333';
            propColorPicker.value = (f.color && f.color.startsWith('#')) ? f.color.substring(0, 7) : '#333333';

            document.querySelectorAll('.btn-align').forEach(btn => {
                if (btn.dataset.align === (f.alignment || 'center')) {
                    btn.classList.add('bg-white', 'font-bold');
                } else {
                    btn.classList.remove('bg-white', 'font-bold');
                }
            });

            // Populate custom text / source section
            const banner = document.getElementById('dynamic-guest-banner');
            const customWrap = document.getElementById('custom-text-wrapper');
            const badge = document.getElementById('prop-source-badge');
            const customInput = document.getElementById('prop-custom-text');

            if (key === 'guest_name') {
                if (banner) banner.classList.remove('hidden');
                if (customWrap) customWrap.classList.add('hidden');
                if (badge) {
                    badge.textContent = 'Dynamic Guest';
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700';
                }
            } else {
                if (banner) banner.classList.add('hidden');
                if (customWrap) customWrap.classList.remove('hidden');
                if (customInput) customInput.value = f.custom_text || '';
                
                if (badge) {
                    if (f.custom_text && f.custom_text.trim() !== '') {
                        badge.textContent = 'Custom Override';
                        badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
                    } else {
                        badge.textContent = 'Event Settings';
                        badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600';
                    }
                }
            }
        }

        coordX.textContent = (f.x || 0).toFixed(3);
        coordY.textContent = (f.y || 0).toFixed(3);
        coordW.textContent = (f.width || 0).toFixed(3);
        coordH.textContent = (f.height || 0).toFixed(3);

        renderBoxes();
    }

    // Photo file upload handler
    btnTriggerPhoto.addEventListener('click', function() {
        photoFileInput.click();
    });

    photoFileInput.addEventListener('change', function() {
        if (!this.files || !this.files[0] || !selectedKey) return;
        const file = this.files[0];
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('field_key', selectedKey);
        formData.append('_token', '{{ csrf_token() }}');

        const label = document.getElementById('photo-upload-label');
        label.textContent = 'Uploading & Fitting...';

        fetch('{{ route('host.card-templates.upload-photo', $template) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fields[selectedKey].photo_url = data.photo_url;
                fields[selectedKey].type = 'image';
                label.textContent = 'Replace Couple Photo';
                showToast('Couple photo uploaded and fitted successfully!');
                renderBoxes();
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
                label.textContent = 'Upload Couple Photo';
            }
        })
        .catch(err => {
            alert('Upload error: ' + err.message);
            label.textContent = 'Upload Couple Photo';
        });
    });

    // Photo fit / radius / border listeners
    propImgFit.addEventListener('change', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].fit = this.value;
        renderBoxes();
    });
    propImgRadius.addEventListener('change', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].border_radius = this.value;
        renderBoxes();
    });
    propImgBorderW.addEventListener('change', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].border_width = parseInt(this.value) || 0;
        renderBoxes();
    });
    propImgBorderColor.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].border_color = this.value;
        propImgBorderColorPicker.value = this.value.startsWith('#') ? this.value.substring(0, 7) : '#c59b27';
        renderBoxes();
    });
    propImgBorderColorPicker.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].border_color = this.value;
        propImgBorderColor.value = this.value;
        renderBoxes();
    });

    // Start drag
    function startDrag(e, key) {
        e.preventDefault();
        const f = fields[key];
        const rect = container.getBoundingClientRect();
        const startMouseX = e.clientX;
        const startMouseY = e.clientY;
        const startX = f.x;
        const startY = f.y;

        function onMouseMove(ev) {
            const dx = (ev.clientX - startMouseX) / rect.width;
            const dy = (ev.clientY - startMouseY) / rect.height;

            f.x = Math.max(0, Math.min(1 - f.width, +(startX + dx).toFixed(4)));
            f.y = Math.max(0, Math.min(1 - f.height, +(startY + dy).toFixed(4)));

            coordX.textContent = f.x.toFixed(3);
            coordY.textContent = f.y.toFixed(3);
            renderBoxes();
        }

        function onMouseUp() {
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', onMouseUp);
        }

        window.addEventListener('mousemove', onMouseMove);
        window.addEventListener('mouseup', onMouseUp);
    }

    // Start resize
    function startResize(e, key, dir) {
        e.preventDefault();
        e.stopPropagation();
        const f = fields[key];
        const rect = container.getBoundingClientRect();
        const startMouseX = e.clientX;
        const startMouseY = e.clientY;
        const origX = f.x;
        const origY = f.y;
        const origW = f.width;
        const origH = f.height;

        function onMouseMove(ev) {
            const dx = (ev.clientX - startMouseX) / rect.width;
            const dy = (ev.clientY - startMouseY) / rect.height;

            if (dir.includes('e')) {
                f.width = Math.max(0.05, Math.min(1 - origX, +(origW + dx).toFixed(4)));
            }
            if (dir.includes('s')) {
                f.height = Math.max(0.02, Math.min(1 - origY, +(origH + dy).toFixed(4)));
            }
            if (dir.includes('w')) {
                const newW = Math.max(0.05, +(origW - dx).toFixed(4));
                const newX = +(origX + dx).toFixed(4);
                if (newX >= 0 && newW >= 0.05) {
                    f.x = newX;
                    f.width = newW;
                }
            }
            if (dir.includes('n')) {
                const newH = Math.max(0.02, +(origH - dy).toFixed(4));
                const newY = +(origY + dy).toFixed(4);
                if (newY >= 0 && newH >= 0.02) {
                    f.y = newY;
                    f.height = newH;
                }
            }

            coordX.textContent = f.x.toFixed(3);
            coordY.textContent = f.y.toFixed(3);
            coordW.textContent = f.width.toFixed(3);
            coordH.textContent = f.height.toFixed(3);
            renderBoxes();
        }

        function onMouseUp() {
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', onMouseUp);
        }

        window.addEventListener('mousemove', onMouseMove);
        window.addEventListener('mouseup', onMouseUp);
    }

    // Bind typography inputs
    propLabel.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].label = this.value;
        renderBoxes();
    });
    propFont.addEventListener('change', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].font_family = this.value;
        renderBoxes();
    });
    propSize.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].font_size = parseInt(this.value) || 20;
        renderBoxes();
    });
    propColor.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].color = this.value;
        propColorPicker.value = this.value.startsWith('#') ? this.value.substring(0, 7) : '#333333';
        renderBoxes();
    });
    propColorPicker.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].color = this.value;
        propColor.value = this.value;
        renderBoxes();
    });

    // Custom color swatch buttons
    document.querySelectorAll('.btn-color-swatch').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!selectedKey || !fields[selectedKey]) return;
            const c = this.dataset.color;
            fields[selectedKey].color = c;
            propColor.value = c;
            propColorPicker.value = c;
            renderBoxes();
        });
    });

    // Custom text override listener
    const propCustomText = document.getElementById('prop-custom-text');
    if (propCustomText) {
        propCustomText.addEventListener('input', function() {
            if (!selectedKey || !fields[selectedKey]) return;
            fields[selectedKey].custom_text = this.value;
            const badge = document.getElementById('prop-source-badge');
            if (badge) {
                if (this.value.trim() !== '') {
                    badge.textContent = 'Custom Override';
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
                } else {
                    badge.textContent = 'Event Settings';
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600';
                }
            }
            renderBoxes();
        });
    }
    propEditable.addEventListener('change', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].is_editable = this.checked;
    });

    document.querySelectorAll('.btn-align').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!selectedKey || !fields[selectedKey]) return;
            fields[selectedKey].alignment = this.dataset.align;
            selectField(selectedKey);
        });
    });

    // Sample invitee name toggle
    document.querySelectorAll('.btn-sample-name').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-sample-name').forEach(b => {
                b.classList.remove('bg-purple-50', 'text-purple-700', 'border-purple-300', 'font-bold');
                b.classList.add('bg-slate-50', 'text-slate-700', 'border-slate-200');
            });
            this.classList.add('bg-purple-50', 'text-purple-700', 'border-purple-300', 'font-bold');
            this.classList.remove('bg-slate-50');
            sampleName = this.dataset.name;
            renderBoxes();
        });
    });

    // QR preset positions
    document.querySelectorAll('.btn-qr-pos').forEach(btn => {
        btn.addEventListener('click', function() {
            const pos = this.dataset.pos;
            const size = 0.16;
            if (!fields['qr_code']) {
                fields['qr_code'] = { label: 'QR Pass Code', type: 'qr', width: size, height: size };
            }
            if (pos === 'bottom_right') {
                fields['qr_code'].x = 0.76;
                fields['qr_code'].y = 0.77;
            } else if (pos === 'bottom_left') {
                fields['qr_code'].x = 0.08;
                fields['qr_code'].y = 0.77;
            } else if (pos === 'bottom_center') {
                fields['qr_code'].x = 0.42;
                fields['qr_code'].y = 0.77;
            } else if (pos === 'top_right') {
                fields['qr_code'].x = 0.76;
                fields['qr_code'].y = 0.08;
            }
            fields['qr_code'].width = size;
            fields['qr_code'].height = size;
            selectField('qr_code');
        });
    });

    // Check QR scannability warning
    function checkQrScannability() {
        const qr = fields['qr_code'];
        const warnBox = document.getElementById('qr-warning-box');
        if (!qr || !warnBox) return;

        if (qr.width < 0.12) {
            warnBox.classList.remove('hidden');
            document.getElementById('qr-warning-text').textContent = 'Warning: QR width (' + Math.round(qr.width * 100) + '%) is below recommended minimum 12%. It may be hard to scan at entrance.';
        } else {
            warnBox.classList.add('hidden');
        }
    }

    // Add Dedicated Couple Photo Frame Button
    document.getElementById('btn-add-couple-photo').addEventListener('click', function() {
        const key = 'couple_photo';
        fields[key] = {
            label: 'Couple Photo Frame',
            type: 'image',
            x: 0.06,
            y: 0.12,
            width: 0.42,
            height: 0.50,
            fit: 'cover',
            border_radius: '16px',
            border_width: 2,
            border_color: '#c59b27',
            is_editable: true,
        };
        selectField(key);
    });

    // Double Card Preset Handler (Split side-by-side landscape card)
    const btnDoublePreset = document.getElementById('btn-double-card-preset');
    if (btnDoublePreset) {
        btnDoublePreset.addEventListener('click', function() {
            if (!confirm('Apply Split / Double Card preset? This organizes the couple photo on the left panel, guest name and details on the right panel, and the QR pass on the bottom right.')) return;
            
            const existingPhotoUrl = fields['couple_photo'] ? fields['couple_photo'].photo_url : null;
            const existingPhotoPath = fields['couple_photo'] ? fields['couple_photo'].photo_path : null;

            // 1. Couple photo fills left half (0% to 48%)
            fields['couple_photo'] = {
                field_key: 'couple_photo',
                label: 'Couple Photo (Left Panel)',
                type: 'image',
                x: 0.015,
                y: 0.015,
                width: 0.47,
                height: 0.97,
                fit: 'cover',
                border_radius: '8px',
                border_width: 0,
                border_color: '#c59b27',
                is_editable: true,
                photo_url: existingPhotoUrl,
                photo_path: existingPhotoPath
            };

            // 2. Invitee Name centered on the right panel
            if (!fields['guest_name']) fields['guest_name'] = {};
            fields['guest_name'] = Object.assign(fields['guest_name'], {
                field_key: 'guest_name',
                label: 'Guest Name',
                type: 'text',
                x: 0.52,
                y: 0.20,
                width: 0.44,
                height: 0.08,
                font_family: 'Playfair Display',
                font_size: 26,
                color: '#d4af37',
                alignment: 'center',
                is_editable: true
            });

            // 3. QR entrance pass on bottom-right corner of right panel
            fields['qr_code'] = {
                field_key: 'qr_code',
                label: 'QR Gate Pass',
                type: 'qr',
                x: 0.82,
                y: 0.74,
                width: 0.14,
                height: 0.20,
                confidence: 1.0,
                status: 'approved'
            };

            selectField('couple_photo');
            showToast('Applied Double Card layout preset!');
        });
    }

    // Add field buttons
    document.getElementById('btn-add-text-field').addEventListener('click', function() {
        const key = 'custom_text_' + Date.now();
        fields[key] = {
            label: 'Custom Text Zone',
            type: 'text',
            x: 0.50,
            y: 0.35,
            width: 0.45,
            height: 0.06,
            font_family: 'Playfair Display',
            font_size: 20,
            color: '#1e1b4b',
            alignment: 'center',
            is_editable: true,
        };
        selectField(key);
    });

    document.getElementById('btn-add-photo-field').addEventListener('click', function() {
        const key = 'photo_' + Date.now();
        fields[key] = {
            label: 'Secondary Photo Box',
            type: 'image',
            x: 0.06,
            y: 0.65,
            width: 0.42,
            height: 0.28,
            fit: 'cover',
            border_radius: '12px',
            border_width: 2,
            border_color: '#c59b27',
            is_editable: true,
        };
        selectField(key);
    });

    // Delete field button
    document.getElementById('btn-delete-field').addEventListener('click', function() {
        if (!selectedKey) return;
        if (confirm('Delete this bounding box zone?')) {
            delete fields[selectedKey];
            selectedKey = Object.keys(fields)[0] || null;
            if (selectedKey) selectField(selectedKey);
            else renderBoxes();
        }
    });

    // Toast helper
    function showToast(msg) {
        const toast = document.getElementById('save-toast');
        const toastMsg = document.getElementById('save-toast-message');
        toastMsg.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    // Save Handlers
    function saveFields(redirectAfter = false) {
        const payload = {
            fields: fields,
            _token: '{{ csrf_token() }}'
        };

        const btn = redirectAfter ? document.getElementById('btn-save-and-dispatch') : document.getElementById('btn-save-draft');
        if (btn) btn.disabled = true;

        fetch('{{ route('host.card-templates.editor.update', $template) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (btn) btn.disabled = false;
            if (data.success) {
                showToast(data.message || 'Layout saved successfully!');
                if (redirectAfter) {
                    window.location.href = '{{ route('host.invitations.create') }}?template_id={{ $template->id }}';
                }
            } else {
                alert('Save failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            if (btn) btn.disabled = false;
            alert('Request error: ' + err.message);
        });
    }

    document.getElementById('btn-save-draft').addEventListener('click', () => saveFields(false));
    document.getElementById('btn-save-and-dispatch').addEventListener('click', () => saveFields(true));

    // Select first field initially
    const firstKey = Object.keys(fields)[0];
    if (firstKey) {
        selectField(firstKey);
    } else {
        renderBoxes();
    }
});
</script>
@endsection
