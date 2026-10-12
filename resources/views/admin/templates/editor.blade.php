@extends('layouts.dashboard')

@section('title', 'AI Visual Template Studio - ' . $template->name)
@section('page-title', 'Visual Template Editor')

@section('content')
<div class="space-y-6 pb-16 max-w-7xl mx-auto" id="editor-app">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-r from-slate-900 via-purple-950 to-indigo-950 p-6 sm:p-8 text-white shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-500/20 border border-purple-400/30 px-3 py-1 text-xs font-semibold text-purple-200">
                        <span class="h-2 w-2 rounded-full {{ $template->approval_status === 'approved' ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse' }}"></span>
                        {{ strtoupper($template->approval_status) }}
                    </span>
                    <span class="text-xs text-slate-300">| {{ ucfirst($template->category) }} &bull; {{ $template->artwork_width }}x{{ $template->artwork_height }}px</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading">{{ $template->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl">
                    Drag and resize the highlighted bounding boxes over the card artwork. Normalized coordinates automatically synchronize with Web, PDF, GD Image, and WhatsApp deliveries.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.templates.reanalyze', $template) }}" method="POST" onsubmit="return confirm('Re-run automated AI layout detection? Unsaved changes will be replaced.')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs font-bold text-white transition-all">
                        <svg class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Re-Analyze AI</span>
                    </button>
                </form>

                <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs font-bold text-white transition-all">
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
                    <span>Save Coordinates</span>
                </button>

                @if($template->approval_status !== 'approved')
                    <button type="button" id="btn-publish-approve" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-950/50 transition-all">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Approve &amp; Publish</span>
                    </button>
                @endif
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

    <!-- Main Workspace: Canvas + Controls -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: The Interactive Artwork Canvas (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            <!-- Canvas Toolbar -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-700">Test Invitee Name:</span>
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
                <div id="canvas-container" class="relative select-none shadow-2xl rounded-2xl overflow-hidden bg-white" style="width: 100%; max-width: 620px; aspect-ratio: {{ $template->aspect_ratio }};">
                    <!-- Artwork Image -->
                    <img id="artwork-image" 
                         src="{{ $template->artwork_url }}" 
                         alt="{{ $template->name }}" 
                         class="absolute inset-0 w-full h-full object-contain pointer-events-none user-select-none">

                    <!-- Overlay Draggable Boxes Layer -->
                    <div id="overlays-layer" class="absolute inset-0 w-full h-full pointer-events-auto"></div>
                </div>
            </div>

            <div class="text-center text-xs text-slate-400">
                <span>Tip: Click and drag any zone to reposition. Drag the corners to adjust bounding box width and height.</span>
            </div>
        </div>

        <!-- Right Column: Field Property Inspector & Settings (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Active Selected Field Inspector Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-md space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Selected Zone</span>
                        <h3 class="text-base font-bold text-slate-900" id="selected-field-title">No field selected</h3>
                    </div>
                    <span id="selected-field-badge" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-100 text-slate-700">--</span>
                </div>

                <div id="inspector-form" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Display Label</label>
                        <input type="text" id="prop-label" class="form-input-modern text-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Font Family</label>
                            <select id="prop-font" class="form-input-modern text-xs">
                                <option value="Playfair Display">Playfair Display (Serif)</option>
                                <option value="Georgia">Georgia (Classic)</option>
                                <option value="Cinzel">Cinzel (Roman Luxe)</option>
                                <option value="Great Vibes">Great Vibes (Script)</option>
                                <option value="Montserrat">Montserrat (Modern)</option>
                                <option value="Inter">Inter (Sans)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Font Size (pt)</label>
                            <input type="number" id="prop-size" min="10" max="72" class="form-input-modern text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="prop-color-picker" class="h-8 w-8 rounded-lg cursor-pointer border border-slate-200 p-0.5">
                                <input type="text" id="prop-color" class="form-input-modern text-xs uppercase font-mono">
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
                            <span>Client Editable</span>
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
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Intelligent QR Placement</h4>
                        <p class="text-[11px] text-slate-500">Automated contrast &amp; non-overlap advisor</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="bottom_right">Bottom Right</button>
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="bottom_left">Bottom Left</button>
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="bottom_center">Bottom Center</button>
                    <button type="button" class="btn-qr-pos p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-100/50 font-semibold text-slate-800" data-pos="top_right">Top Right</button>
                </div>

                <div id="qr-warning-box" class="hidden rounded-xl bg-amber-100/80 border border-amber-300 p-2.5 text-[11px] text-amber-900">
                    <span id="qr-warning-text">QR code may be too small for quick check-in scanning.</span>
                </div>
            </div>

            <!-- Add New Field Button -->
            <div class="flex items-center gap-2">
                <button type="button" id="btn-add-text-field" class="flex-1 py-3 px-4 rounded-2xl bg-white border border-slate-200 hover:border-purple-300 text-xs font-bold text-slate-700 shadow-sm transition-all text-center">
                    + Add Text Zone
                </button>
                <button type="button" id="btn-add-photo-field" class="flex-1 py-3 px-4 rounded-2xl bg-white border border-slate-200 hover:border-purple-300 text-xs font-bold text-slate-700 shadow-sm transition-all text-center">
                    + Add Photo Box
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden CSRF token and update form for saving -->
<form id="save-fields-form" action="{{ route('admin.templates.editor.update', $template) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="fields_json" id="fields-json-input">
    <input type="hidden" name="approve" id="approve-input" value="0">
</form>

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
    padding: 4px;
    box-sizing: border-box;
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
    background-color: rgba(37, 99, 235, 0.15);
}

/* Resize Handles */
.resize-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: white;
    border: 2px solid #7c3aed;
    border-radius: 3px;
    z-index: 60;
}
.resize-handle.nw { top: -5px; left: -5px; cursor: nwse-resize; }
.resize-handle.ne { top: -5px; right: -5px; cursor: nesw-resize; }
.resize-handle.se { bottom: -5px; right: -5px; cursor: nwse-resize; }
.resize-handle.sw { bottom: -5px; left: -5px; cursor: nesw-resize; }

.box-label-badge {
    position: absolute;
    top: -18px;
    left: 4px;
    background-color: #7c3aed;
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 4px;
    white-space: nowrap;
    pointer-events: none;
}
.type-qr .box-label-badge {
    background-color: #059669;
}
.type-image .box-label-badge {
    background-color: #2563eb;
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

            // Text preview inside box
            const previewText = document.createElement('div');
            previewText.className = 'w-full h-full flex items-center justify-center overflow-hidden pointer-events-none px-1 text-xs font-semibold';
            previewText.style.fontFamily = field.font_family || 'Playfair Display';
            previewText.style.color = field.color || '#333333';
            previewText.style.textAlign = field.alignment || 'center';

            if (key === 'guest_name') {
                previewText.textContent = sampleName;
            } else if (key === 'qr_code') {
                previewText.innerHTML = '<span class="text-[10px] bg-emerald-800 text-white px-1.5 py-0.5 rounded">QR PASS [OK]</span>';
            } else if (key === 'couple_photo') {
                previewText.innerHTML = '<span class="text-[10px] text-blue-800">[PHOTO FRAME]</span>';
            } else {
                previewText.textContent = field.label || key;
            }
            box.appendChild(previewText);

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
        propFont.value = f.font_family || 'Playfair Display';
        propSize.value = f.font_size || 22;
        propColor.value = f.color || '#333333';
        propColorPicker.value = f.color ? f.color.substring(0, 7) : '#333333';
        propEditable.checked = f.is_editable !== false;

        coordX.textContent = (f.x || 0).toFixed(3);
        coordY.textContent = (f.y || 0).toFixed(3);
        coordW.textContent = (f.width || 0).toFixed(3);
        coordH.textContent = (f.height || 0).toFixed(3);

        // Highlight align button
        document.querySelectorAll('.btn-align').forEach(btn => {
            if (btn.dataset.align === (f.alignment || 'center')) {
                btn.classList.add('bg-white', 'font-bold');
            } else {
                btn.classList.remove('bg-white', 'font-bold');
            }
        });

        renderBoxes();
    }

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

    // Bind inspector inputs
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
        propColorPicker.value = this.value.substring(0, 7);
        renderBoxes();
    });
    propColorPicker.addEventListener('input', function() {
        if (!selectedKey || !fields[selectedKey]) return;
        fields[selectedKey].color = this.value;
        propColor.value = this.value;
        renderBoxes();
    });
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

    // Add field buttons
    document.getElementById('btn-add-text-field').addEventListener('click', function() {
        const key = 'custom_text_' + Date.now();
        fields[key] = {
            label: 'Custom Text Zone',
            type: 'text',
            x: 0.15,
            y: 0.50,
            width: 0.70,
            height: 0.06,
            font_family: 'Playfair Display',
            font_size: 20,
            color: '#333333',
            alignment: 'center',
            is_editable: true,
        };
        selectField(key);
    });

    document.getElementById('btn-add-photo-field').addEventListener('click', function() {
        const key = 'photo_' + Date.now();
        fields[key] = {
            label: 'Photo Frame Area',
            type: 'image',
            x: 0.35,
            y: 0.12,
            width: 0.30,
            height: 0.18,
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

    // Save and Publish Handlers
    function saveFields(approve = false) {
        const payload = {
            fields: fields,
            approve: approve ? 1 : 0,
            _token: '{{ csrf_token() }}'
        };

        const btn = approve ? document.getElementById('btn-publish-approve') : document.getElementById('btn-save-draft');
        if (btn) btn.disabled = true;

        fetch('{{ route('admin.templates.editor.update', $template) }}', {
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
                alert(data.message);
                if (approve) {
                    window.location.href = '{{ route('admin.templates.index') }}';
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
    const btnPub = document.getElementById('btn-publish-approve');
    if (btnPub) {
        btnPub.addEventListener('click', () => {
            if (confirm('Approve and publish this template for immediate use by event hosts?')) {
                saveFields(true);
            }
        });
    }

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
