@extends('layouts.dashboard')

@section('title', 'System Settings')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Hero Banner with Ambient Glow -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 text-white shadow-2xl border border-indigo-500/20">
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-purple-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-4">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Superadmin Control Center
                </div>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight font-heading">
                    Unified Settings & Engine
                </h1>
                <p class="text-slate-300 mt-2 max-w-2xl text-sm lg:text-base leading-relaxed">
                    Configure global identities, transactional communications, payment collections, and infrastructure automation across Tukio 360.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center space-x-2 px-4 py-2.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-semibold backdrop-blur-md">
                    <span class="inline-flex p-1 rounded-full bg-emerald-500/20 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span>Realtime Sync Active</span>
                </div>
            </div>
        </div>

        <!-- Settings Navigation Tabs -->
        <div class="mt-8 pt-6 border-t border-slate-700/50 flex flex-wrap gap-2.5" id="settings-tab-bar">
            <button type="button" class="settings-nav-tab active group" data-target="system">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>System & Brand</span>
            </button>
            <button type="button" class="settings-nav-tab group" data-target="email">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Email (SMTP)</span>
            </button>
            <button type="button" class="settings-nav-tab group" data-target="payment">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Payments</span>
            </button>
            <button type="button" class="settings-nav-tab group" data-target="sms">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>SMS Gateway</span>
            </button>
            <button type="button" class="settings-nav-tab group" data-target="whatsapp">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <span>WhatsApp Engine</span>
            </button>
            <button type="button" class="settings-nav-tab group" data-target="contact-footer">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <span>Contact & Footer</span>
            </button>
            <button type="button" class="settings-nav-tab group" data-target="backup">
                <svg class="w-4 h-4 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                <span>Backup & Snapshots</span>
            </button>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-5 text-emerald-900 shadow-sm backdrop-blur-md flex items-center gap-4 animate-fadeIn">
            <div class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-emerald-950 text-sm">Configuration Updated</p>
                <p class="text-emerald-800 text-xs mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-5 text-rose-900 shadow-sm backdrop-blur-md flex items-center gap-4 animate-fadeIn">
            <div class="p-2.5 rounded-xl bg-rose-500/20 text-rose-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-rose-950 text-sm">Action Failed</p>
                <p class="text-rose-800 text-xs mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- TAB 1: SYSTEM & BRAND IDENTITY -->
    <div class="settings-panel active" data-panel="system">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-indigo-50/40 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800 mb-1">
                        Brand & Identity
                    </span>
                    <h2 class="text-2xl font-bold text-slate-900 font-heading">System Core Configuration</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Customize global platform branding, regional formats, and social links.</p>
                </div>
                <div class="hidden lg:flex items-center px-4 py-2 bg-indigo-50/80 rounded-2xl text-indigo-700 text-xs font-bold border border-indigo-100/80">
                    <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Africa/Dar_es_Salaam (EAT)
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.system') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Column 1: Core Info -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Application Identity</h3>
                        
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">System Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="system_name" value="{{ old('system_name', $data['system']['name'] ?? 'Tukio 360') }}" class="input-field" required placeholder="Tukio 360">
                        </div>

                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Tagline / Slogan</label>
                            <input type="text" name="system_tagline" value="{{ old('system_tagline', $data['system']['tagline']) }}" class="input-field" placeholder="Complete Event & Mobility Operations">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Timezone</label>
                                <select name="timezone" class="input-field">
                                    @foreach($timezones as $zone)
                                        <option value="{{ $zone }}" @selected(old('timezone', $data['system']['timezone']) === $zone)>{{ $zone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Default Currency</label>
                                <select name="currency" class="input-field">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency }}" @selected(old('currency', $data['system']['currency']) === $currency)>{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Support / Contact Email</label>
                                <input type="email" name="email" value="{{ old('email', $data['system']['email']) }}" class="input-field" placeholder="admin@tukio360.com">
                            </div>
                            <div>
                                <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Official Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $data['system']['phone']) }}" class="input-field" placeholder="+255 700 000 000">
                            </div>
                        </div>

                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block flex items-center justify-between">
                                <span>WhatsApp Business / Support Number</span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    Direct Chat Enabled
                                </span>
                            </label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $data['system']['whatsapp'] ?? '') }}" class="input-field" placeholder="+255 700 000 000 (e.g. 0712345678)">
                            <p class="text-[11px] text-slate-500 mt-1.5">This number powers floating WhatsApp widgets, car hire bookings, and support inquiries across Tukio 360.</p>
                        </div>

                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Physical HQ Address</label>
                            <textarea name="address" class="input-field" rows="3" placeholder="P.O. Box 1234, Dar es Salaam, Tanzania">{{ old('address', $data['system']['address']) }}</textarea>
                        </div>
                    </div>

                    <!-- Column 2: Logo, Favicon & Social -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Media & Presence</h3>

                        <!-- Logo Upload Section -->
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80">
                            <label class="input-label font-bold text-slate-700 text-sm mb-1 block">Primary System Logo</label>
                            <p class="text-xs text-slate-500 mb-4">Visible on navbar, login screens, and invitation headers. Max 2MB.</p>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-4">
                                <div class="relative flex-shrink-0">
                                    @if($data['system']['logo'])
                                        <div class="relative w-36 h-20 rounded-xl border border-slate-200 bg-white p-2 flex items-center justify-center overflow-hidden shadow-sm group">
                                            <img src="{{ storage_url($data['system']['logo']) }}" alt="Current Logo" id="logo-preview" class="max-w-full max-h-full object-contain transition-transform group-hover:scale-105">
                                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span class="text-white text-[11px] font-bold">Active Logo</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="relative w-36 h-20 rounded-xl border-2 border-dashed border-slate-300 bg-white flex items-center justify-center overflow-hidden">
                                            <img src="" alt="Preview" id="logo-preview" class="max-w-full max-h-full object-contain hidden">
                                            <div id="no-logo-placeholder" class="text-center">
                                                <svg class="w-6 h-6 text-slate-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <p class="text-[10px] text-slate-400 font-semibold">No Logo</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 w-full">
                                    <label for="logo-upload" class="cursor-pointer block">
                                        <div class="border-2 border-dashed border-indigo-200 hover:border-indigo-500 transition-all bg-indigo-50/40 hover:bg-indigo-50/80 rounded-xl text-center py-4 px-3">
                                            <svg class="w-5 h-5 text-indigo-600 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <span class="block text-xs font-bold text-indigo-700">Choose PNG / SVG / JPG</span>
                                            <span class="text-[10px] text-slate-500">Suggested ratio 4:1 (e.g. 200x50px)</span>
                                        </div>
                                    </label>
                                    <input type="file" name="logo" id="logo-upload" accept="image/png,image/jpeg,image/jpg,image/svg+xml" class="hidden" onchange="previewLogo(this)">
                                    @if($data['system']['logo'])
                                    <div class="mt-2.5">
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="remove_logo" value="1" id="remove-logo-checkbox" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span class="text-xs text-rose-600 hover:text-rose-700 font-semibold">Remove existing logo on save</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Favicon Upload Section -->
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80">
                            <label class="input-label font-bold text-slate-700 text-sm mb-1 block">Browser Favicon</label>
                            <p class="text-xs text-slate-500 mb-4">Displayed on browser tabs and bookmarks. (32x32px or 16x16px).</p>
                            
                            <div class="flex items-center gap-4">
                                @if($data['system']['favicon'])
                                    <div class="w-12 h-12 rounded-xl border border-slate-200 bg-white p-1.5 flex items-center justify-center overflow-hidden shadow-sm flex-shrink-0">
                                        <img src="{{ storage_url($data['system']['favicon']) }}" alt="Favicon" id="favicon-preview" class="w-full h-full object-contain">
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-xl border-2 border-dashed border-slate-300 bg-white flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        <img src="" alt="Favicon" id="favicon-preview" class="w-full h-full object-contain hidden">
                                        <svg id="no-favicon-placeholder" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <label for="favicon-upload" class="cursor-pointer block">
                                        <div class="border-2 border-dashed border-indigo-200 hover:border-indigo-500 transition-all bg-indigo-50/40 hover:bg-indigo-50/80 rounded-xl text-center py-3 px-3">
                                            <span class="text-xs font-bold text-indigo-700">Upload Favicon (.ico, .png)</span>
                                        </div>
                                    </label>
                                    <input type="file" name="favicon" id="favicon-upload" accept="image/png,image/x-icon,image/jpeg" class="hidden" onchange="previewFavicon(this)">
                                    @if($data['system']['favicon'])
                                    <div class="mt-2">
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="remove_favicon" value="1" id="remove-favicon-checkbox" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span class="text-xs text-rose-600 hover:text-rose-700 font-semibold">Remove favicon on save</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Accounts -->
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-3 block">Social Links & Messengers</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(['facebook','instagram','twitter','linkedin','youtube','whatsapp'] as $platform)
                                    <div>
                                        <label class="text-xs font-semibold capitalize text-slate-500 mb-1 block flex items-center justify-between">
                                            <span>{{ $platform }}</span>
                                            @if($platform === 'whatsapp')
                                                <span class="text-[10px] text-emerald-600 font-bold">wa.me link</span>
                                            @endif
                                        </label>
                                        <input type="{{ $platform === 'whatsapp' ? 'text' : 'url' }}" name="social[{{ $platform }}]" value="{{ old("social.$platform", $data['system']['social'][$platform] ?? '') }}" class="input-field text-sm" placeholder="{{ $platform === 'whatsapp' ? 'https://wa.me/255662199071' : 'https://' . $platform . '.com/tukio360' }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary-gradient px-8 py-3.5 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save System Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: EMAIL (SMTP) -->
    <div class="settings-panel" data-panel="email">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-blue-50/40 border-b border-slate-100">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800 mb-1">
                    Outbound Communication
                </span>
                <h2 class="text-2xl font-bold text-slate-900 font-heading">SMTP Email Gateway</h2>
                <p class="text-slate-500 text-sm mt-0.5">Configure transactional mail server credentials for invites, OTPs, and notifications.</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.email') }}" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">SMTP Host <span class="text-rose-500">*</span></label>
                        <input type="text" name="host" value="{{ old('host', $data['mail']['host']) }}" class="input-field" required placeholder="smtp.mailgun.org">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">SMTP Port <span class="text-rose-500">*</span></label>
                        <input type="number" name="port" value="{{ old('port', $data['mail']['port']) }}" class="input-field" required placeholder="587">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Encryption Protocol</label>
                        <select name="encryption" class="input-field">
                            <option value="">None (Plain)</option>
                            <option value="tls" @selected(old('encryption', $data['mail']['encryption']) === 'tls')>TLS (Recommended)</option>
                            <option value="ssl" @selected(old('encryption', $data['mail']['encryption']) === 'ssl')>SSL</option>
                        </select>
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Username / Account</label>
                        <input type="text" name="username" value="{{ old('username', $data['mail']['username']) }}" class="input-field" placeholder="postmaster@yourdomain.com">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Password / Secret Key</label>
                        <input type="password" name="password" value="{{ old('password', $data['mail']['password']) }}" class="input-field" placeholder="••••••••••••••••">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Sender Display Name</label>
                        <input type="text" name="from_name" value="{{ old('from_name', $data['mail']['from_name']) }}" class="input-field" placeholder="Tukio 360 Concierge">
                    </div>
                    <div class="md:col-span-2">
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">From Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="from_address" value="{{ old('from_address', $data['mail']['from_address']) }}" class="input-field" required placeholder="no-reply@tukio360.com">
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-primary-gradient px-8 py-3.5 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Email Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 3: PAYMENTS -->
    <div class="settings-panel" data-panel="payment">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-emerald-50/40 border-b border-slate-100">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 mb-1">
                    Revenue & Gateways
                </span>
                <h2 class="text-2xl font-bold text-slate-900 font-heading">Payment Collection Settings</h2>
                <p class="text-slate-500 text-sm mt-0.5">Configure Selcom Pesa Lipa Namba, payment QR codes, pricing per guest, and Flutterwave gateway.</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.payment') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- Section 1: Selcom Pesa (Primary Local Mobile Money) -->
                <div>
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                        <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">Primary Channel</span>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Selcom Pesa / Direct QR &amp; Lipa Namba</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Selcom Lipa / Account Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="selcom_number" value="{{ old('selcom_number', $data['payment']['selcom_number'] ?? '5525108767899') }}" class="input-field font-mono font-bold text-base tracking-wider" required placeholder="5525108767899">
                            <p class="text-xs text-slate-400 mt-1">Displayed to hosts on their dashboard & billing page</p>
                        </div>

                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Beneficiary / Account Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="selcom_beneficiary" value="{{ old('selcom_beneficiary', $data['payment']['selcom_beneficiary'] ?? 'Stewart Amiri Seifu') }}" class="input-field font-bold" required placeholder="Stewart Amiri Seifu">
                            <p class="text-xs text-slate-400 mt-1">Beneficiary name that hosts verify when paying</p>
                        </div>

                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Price Per Guest (TZS) <span class="text-rose-500">*</span></label>
                            <input type="number" name="price_per_guest" value="{{ old('price_per_guest', $data['payment']['price_per_guest'] ?? 2000) }}" class="input-field font-mono font-bold" required min="0" step="100" placeholder="2000">
                            <p class="text-xs text-slate-400 mt-1">Rate multiplied by guest count during host event calculation</p>
                        </div>

                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Selcom QR Code Image</label>
                            <div class="flex items-center gap-4">
                                @if(!empty($data['payment']['selcom_qr_code']))
                                    <div class="w-16 h-16 rounded-xl border border-slate-200 bg-slate-50 p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset($data['payment']['selcom_qr_code']) }}" alt="QR" class="w-full h-full object-contain rounded-lg">
                                    </div>
                                @endif
                                <input type="file" name="selcom_qr" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Upload new QR code image to replace the current one</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Flutterwave Online Card/Bank Gateway -->
                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                        <span class="px-3 py-1 rounded-lg bg-indigo-100 text-indigo-800 text-xs font-bold uppercase tracking-wider">Online Gateway</span>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Flutterwave Integration (Optional)</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Public Key</label>
                            <input type="text" name="public_key" value="{{ old('public_key', $data['payment']['public_key']) }}" class="input-field font-mono text-sm" placeholder="FLWPUBK_TEST-...">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Secret Key</label>
                            <input type="text" name="secret_key" value="{{ old('secret_key', $data['payment']['secret_key']) }}" class="input-field font-mono text-sm" placeholder="FLWSECK_TEST-...">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Encryption Key</label>
                            <input type="text" name="encryption_key" value="{{ old('encryption_key', $data['payment']['encryption_key']) }}" class="input-field font-mono text-sm" placeholder="FLWSECK_TEST...">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Settlement Currency</label>
                            <input type="text" name="currency" value="{{ old('currency', $data['payment']['currency'] ?? 'TZS') }}" class="input-field" placeholder="TZS">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Environment Mode</label>
                            <select name="environment" class="input-field">
                                <option value="sandbox" @selected(old('environment', $data['payment']['environment']) === 'sandbox')>Sandbox (Testing)</option>
                                <option value="production" @selected(old('environment', $data['payment']['environment']) === 'production')>Production (Live)</option>
                            </select>
                        </div>
                        <div class="flex items-center pt-8">
                            <label class="inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="enabled" value="1" class="sr-only peer" {{ old('enabled', $data['payment']['enabled']) ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-600 relative"></div>
                                <span class="ml-3 text-sm font-bold text-slate-800">Enable Live Flutterwave Collections</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-primary-gradient px-8 py-3.5 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Payment Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 4: SMS GATEWAY -->
    <div class="settings-panel" data-panel="sms">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-amber-50/40 border-b border-slate-100">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-800 mb-1">
                    Telco Messaging
                </span>
                <h2 class="text-2xl font-bold text-slate-900 font-heading">Beem SMS Gateway</h2>
                <p class="text-slate-500 text-sm mt-0.5">Integrate Beem Africa credentials for cellular SMS broadcasts and OTP dispatch.</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.sms') }}" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">API Key <span class="text-rose-500">*</span></label>
                        <input type="text" name="api_key" value="{{ old('api_key', $data['sms']['api_key']) }}" class="input-field font-mono text-sm" required placeholder="beem_api_key_...">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Secret Key <span class="text-rose-500">*</span></label>
                        <input type="text" name="secret_key" value="{{ old('secret_key', $data['sms']['secret_key']) }}" class="input-field font-mono text-sm" required placeholder="beem_secret_key_...">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Sender ID / Mask <span class="text-rose-500">*</span></label>
                        <input type="text" name="sender_id" value="{{ old('sender_id', $data['sms']['sender_id']) }}" class="input-field" required placeholder="TUKIO360">
                    </div>
                    <div>
                        <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Delivery Callback Webhook URL</label>
                        <input type="url" name="callback_url" value="{{ old('callback_url', $data['sms']['callback_url']) }}" class="input-field" placeholder="https://yourdomain.com/api/webhooks/beem">
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-primary-gradient px-8 py-3.5 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save SMS Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 5: WHATSAPP ENGINES -->
    <div class="settings-panel" data-panel="whatsapp">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-emerald-50/40 border-b border-slate-100">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 mb-1">
                    Instant Messaging
                </span>
                <h2 class="text-2xl font-bold text-slate-900 font-heading">WhatsApp Realtime Gateways</h2>
                <p class="text-slate-500 text-sm mt-0.5">Configure Meta Cloud API and Beem WhatsApp for QR invites, RSVP confirmations, and chatbot routing.</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.whatsapp') }}" class="p-8 space-y-8">
                @csrf

                <!-- Primary Support / Chat WhatsApp Number Card -->
                <div class="p-6 rounded-3xl border border-emerald-500/20 bg-gradient-to-r from-emerald-50/50 via-teal-50/30 to-indigo-50/30">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 border-b border-emerald-100">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 mb-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Primary WhatsApp Touchpoint
                            </span>
                            <h3 class="text-lg font-bold text-slate-900">Direct Support & Chat WhatsApp Number</h3>
                            <p class="text-xs text-slate-600 mt-0.5">Used for the floating WhatsApp button, wedding car hire inquiries, and public contact pages.</p>
                        </div>
                        @if(!empty($data['whatsapp']['support_phone']))
                            @php
                                $cleanPreview = preg_replace('/[^0-9]/', '', $data['whatsapp']['support_phone']);
                                if (str_starts_with($cleanPreview, '0')) {
                                    $cleanPreview = '255' . substr($cleanPreview, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPreview }}?text=Habari+Tukio+360" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition-all self-start md:self-auto">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                <span>Test Live Chat Link</span>
                            </a>
                        @endif
                    </div>
                    <div class="mt-4 max-w-lg">
                        <label class="input-label font-bold text-slate-700 text-sm mb-1.5 block">Official WhatsApp Support Number</label>
                        <div class="relative">
                            <input type="text" name="support_phone" value="{{ old('support_phone', $data['whatsapp']['support_phone'] ?? '') }}" class="input-field font-mono text-sm pl-10" placeholder="+255 700 000 000 or 0712345678">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Accepts local formats (e.g. 0712...) or full international (+255...). Automatically normalized for wa.me links.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Meta Cloud API Card -->
                    <div class="p-6 rounded-2xl border border-indigo-100 bg-indigo-50/30 space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-indigo-100">
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                                Meta Cloud API (Official)
                            </h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-indigo-100 text-indigo-700">Direct Graph</span>
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Permanent Access Token</label>
                            <textarea name="meta[access_token]" class="input-field font-mono text-xs" rows="3" placeholder="EAA... (System User Token)">{{ old('meta.access_token', $data['whatsapp']['meta']['access_token']) }}</textarea>
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Phone Number ID</label>
                            <input type="text" name="meta[phone_number_id]" value="{{ old('meta.phone_number_id', $data['whatsapp']['meta']['phone_number_id']) }}" class="input-field font-mono text-sm" placeholder="1029384756...">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Graph API Version</label>
                            <input type="text" name="meta[graph_api_version]" value="{{ old('meta.graph_api_version', $data['whatsapp']['meta']['graph_api_version'] ?? 'v19.0') }}" class="input-field font-mono text-sm" placeholder="v19.0">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Webhook Verify Token</label>
                            <input type="text" name="meta[webhook_verify_token]" value="{{ old('meta.webhook_verify_token', $data['whatsapp']['meta']['webhook_verify_token']) }}" class="input-field font-mono text-sm" placeholder="tukio_secret_webhook_key">
                        </div>
                    </div>

                    <!-- Beem WhatsApp Card -->
                    <div class="p-6 rounded-2xl border border-emerald-100 bg-emerald-50/30 space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-emerald-100">
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                                Beem WhatsApp Gateway
                            </h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">East Africa Hub</span>
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">API Key</label>
                            <input type="text" name="beem[api_key]" value="{{ old('beem.api_key', $data['whatsapp']['beem']['api_key']) }}" class="input-field font-mono text-sm" placeholder="beem_whatsapp_key_...">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Secret Key</label>
                            <input type="text" name="beem[secret_key]" value="{{ old('beem.secret_key', $data['whatsapp']['beem']['secret_key']) }}" class="input-field font-mono text-sm" placeholder="beem_whatsapp_secret_...">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Sender / From Number</label>
                            <input type="text" name="beem[from]" value="{{ old('beem.from', $data['whatsapp']['beem']['from']) }}" class="input-field font-mono text-sm" placeholder="255662199071">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Broadcast Base URL</label>
                            <input type="url" name="beem[broadcast_base]" value="{{ old('beem.broadcast_base', $data['whatsapp']['beem']['broadcast_base']) }}" class="input-field text-sm" placeholder="https://whatsapp.beem.africa/v1/broadcast">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-xs mb-1.5 block">Chat API Base URL</label>
                            <input type="url" name="beem[chat_base]" value="{{ old('beem.chat_base', $data['whatsapp']['beem']['chat_base']) }}" class="input-field text-sm" placeholder="https://whatsapp.beem.africa/v1/chat">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-primary-gradient px-8 py-3.5 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save WhatsApp Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 6: CONTACT & FOOTER -->
    <div class="settings-panel" data-panel="contact-footer">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-purple-50/40 border-b border-slate-100">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-100 text-purple-800 mb-1">
                    Public Portal Content
                </span>
                <h2 class="text-2xl font-bold text-slate-900 font-heading">Contact Information & Footer Links</h2>
                <p class="text-slate-500 text-sm mt-0.5">Manage live contact touchpoints and customizable bottom-bar menu items.</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.contact-footer') }}" class="p-8 space-y-8">
                @csrf
                
                <!-- Contact Information Section -->
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Public Contact Details</h3>
                    <p class="text-xs text-slate-500 mb-5">These details are surfaced on the `/contact` page and customer support dialogues.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Display Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $data['contact']['email'] ?? '') }}" class="input-field" placeholder="support@tukio360.com">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Display Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $data['contact']['phone'] ?? '') }}" class="input-field" placeholder="+255 123 456 789">
                        </div>
                        <div>
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block flex items-center justify-between">
                                <span>WhatsApp Contact</span>
                                <span class="text-[10px] text-emerald-600 font-bold">wa.me</span>
                            </label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $data['contact']['whatsapp'] ?? '') }}" class="input-field" placeholder="+255 700 000 000">
                        </div>
                        <div class="md:col-span-3">
                            <label class="input-label font-bold text-slate-700 text-sm mb-2 block">Display Office / Physical Address</label>
                            <textarea name="contact_address" rows="2" class="input-field" placeholder="Floor 4, Golden Heights Tower, Dar es Salaam, Tanzania">{{ old('contact_address', $data['contact']['address'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Links Section -->
                <div class="border-t border-slate-100 pt-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Custom Footer Links</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Links rendered dynamically in the public portal footer bar.</p>
                        </div>
                        <button type="button" id="add-footer-link" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition-colors text-xs border border-indigo-200/80">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Link
                        </button>
                    </div>
                    
                    <div id="footer-links-container" class="space-y-3">
                        @if(!empty($data['footer']['links']))
                            @foreach($data['footer']['links'] as $index => $link)
                                <div class="footer-link-item flex gap-4 items-center p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 transition-all hover:border-slate-300">
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-600 mb-1 block">Link Label</label>
                                            <input type="text" name="footer_links[{{ $index }}][label]" value="{{ old("footer_links.$index.label", $link['label'] ?? '') }}" class="input-field text-sm" placeholder="Privacy Policy" required>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-600 mb-1 block">Destination URL / Path</label>
                                            <input type="text" name="footer_links[{{ $index }}][url]" value="{{ old("footer_links.$index.url", $link['url'] ?? '') }}" class="input-field text-sm font-mono" placeholder="/privacy-policy" required>
                                        </div>
                                    </div>
                                    <button type="button" class="remove-footer-link p-2.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors text-xs font-bold mt-5" title="Remove Link">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-primary-gradient px-8 py-3.5 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Contact & Footer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 7: BACKUP & RESTORE -->
    <div class="settings-panel" data-panel="backup">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-indigo-50/40 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800 mb-1">
                        System Resilience
                    </span>
                    <h2 class="text-2xl font-bold text-slate-900 font-heading">Database Snapshots & Recovery</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Generate hot database backups, restore previous archives, or download historical snapshots.</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.backup.create') }}">
                    @csrf
                    <button type="submit" class="btn-primary-gradient px-6 py-3 rounded-2xl text-white font-bold shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:shadow-indigo-500/40 transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Fresh Snapshot
                    </button>
                </form>
            </div>
            
            <div class="p-8 space-y-8">
                <!-- Restore Section -->
                <div class="p-6 rounded-2xl border border-amber-200/70 bg-amber-50/40">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 rounded-xl bg-amber-100 text-amber-700">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-amber-950">Restore Database Snapshot</h3>
                            <p class="text-xs text-amber-800">Restoring overwrites active application data. Only execute during planned maintenance.</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.settings.backup.restore') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 mt-4" onsubmit="return confirm('WARNING: Restoring will overwrite all current system data with the backup file. Proceed?');">
                        @csrf
                        <input type="file" name="backup_file" accept=".sqlite" class="input-field file flex-1 bg-white" required>
                        <button type="submit" class="px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-md transition-all whitespace-nowrap">
                            Upload & Restore
                        </button>
                    </form>
                </div>

                <!-- Existing Backups List -->
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Available Server Archives</h3>
                            <p class="text-xs text-slate-500">Stored in encrypted local repository `storage/app/backups`</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 font-bold text-xs">{{ count($backups) }} archives</span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($backups as $backup)
                            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm font-mono">{{ $backup['name'] }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $backup['date'] }} &bull; <span class="font-semibold text-slate-600">{{ $backup['size'] }}</span></p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.settings.backup.download', $backup['name']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-colors border border-indigo-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Archive
                                </a>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">No Historical Backups Found</p>
                                <p class="text-xs text-slate-500 mt-1">Generate your initial database snapshot above to safeguard system integrity.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.settings-nav-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 700;
    color: #cbd5e1;
    background-color: rgba(30, 41, 59, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.settings-nav-tab:hover {
    color: #ffffff;
    background-color: rgba(51, 65, 85, 0.9);
    border-color: rgba(129, 140, 248, 0.4);
    transform: translateY(-1px);
}
.settings-nav-tab.active {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.25);
    box-shadow: 0 10px 25px -3px rgba(79, 70, 229, 0.45);
}
.settings-panel {
    display: none;
    animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.settings-panel.active {
    display: block;
}
.input-label {
    display: block;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.375rem;
}
.input-field {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 0.75rem 1.125rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background-color: #ffffff;
    color: #0f172a;
}
.input-field:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.input-field.file {
    padding: 0.5rem 0.875rem;
}
.btn-primary-gradient {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.settings-nav-tab');
    const panels = document.querySelectorAll('.settings-panel');

    function activateTab(target) {
        tabs.forEach(tab => {
            tab.classList.toggle('active', tab.getAttribute('data-target') === target);
        });

        panels.forEach(panel => {
            panel.classList.toggle('active', panel.getAttribute('data-panel') === target);
        });

        // Store active tab in URL hash
        if (history.pushState) {
            history.pushState(null, null, '#' + target);
        } else {
            location.hash = '#' + target;
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => activateTab(tab.getAttribute('data-target')));
    });

    // Check URL hash on page load
    const currentHash = window.location.hash.replace('#', '');
    if (currentHash) {
        const matchingTab = document.querySelector(`.settings-nav-tab[data-target="${currentHash}"]`);
        if (matchingTab) {
            activateTab(currentHash);
        }
    }

    // Footer Links Dynamic Repeater
    const addLinkBtn = document.getElementById('add-footer-link');
    const linksContainer = document.getElementById('footer-links-container');
    
    if (addLinkBtn && linksContainer) {
        let linkIndex = linksContainer.children.length;
        
        addLinkBtn.addEventListener('click', function() {
            const linkItem = document.createElement('div');
            linkItem.className = 'footer-link-item flex gap-4 items-center p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 transition-all hover:border-slate-300';
            linkItem.innerHTML = `
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-600 mb-1 block">Link Label</label>
                        <input type="text" name="footer_links[${linkIndex}][label]" class="input-field text-sm" placeholder="Terms of Service" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-600 mb-1 block">Destination URL / Path</label>
                        <input type="text" name="footer_links[${linkIndex}][url]" class="input-field text-sm font-mono" placeholder="/terms" required>
                    </div>
                </div>
                <button type="button" class="remove-footer-link p-2.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors text-xs font-bold mt-5" title="Remove Link">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            linksContainer.appendChild(linkItem);
            linkIndex++;
            
            linkItem.querySelector('.remove-footer-link').addEventListener('click', function() {
                linkItem.remove();
            });
        });
        
        linksContainer.querySelectorAll('.remove-footer-link').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.footer-link-item').remove();
            });
        });
    }

    // Logo Preview & Removal Handlers
    const removeLogoCheckbox = document.getElementById('remove-logo-checkbox');
    const removeFaviconCheckbox = document.getElementById('remove-favicon-checkbox');
    
    if (removeLogoCheckbox) {
        removeLogoCheckbox.addEventListener('change', function() {
            const logoUpload = document.getElementById('logo-upload');
            const preview = document.getElementById('logo-preview');
            if (this.checked) {
                logoUpload.disabled = true;
                logoUpload.value = '';
                if (preview) {
                    preview.style.opacity = '0.25';
                    preview.style.filter = 'grayscale(100%)';
                }
            } else {
                logoUpload.disabled = false;
                if (preview) {
                    preview.style.opacity = '1';
                    preview.style.filter = 'none';
                }
            }
        });
    }
    
    if (removeFaviconCheckbox) {
        removeFaviconCheckbox.addEventListener('change', function() {
            const faviconUpload = document.getElementById('favicon-upload');
            const preview = document.getElementById('favicon-preview');
            if (this.checked) {
                faviconUpload.disabled = true;
                faviconUpload.value = '';
                if (preview) {
                    preview.style.opacity = '0.25';
                    preview.style.filter = 'grayscale(100%)';
                }
            } else {
                faviconUpload.disabled = false;
                if (preview) {
                    preview.style.opacity = '1';
                    preview.style.filter = 'none';
                }
            }
        });
    }
});

// Global Image Preview Functions
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let previewImg = document.getElementById('logo-preview');
            let placeholder = document.getElementById('no-logo-placeholder');
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                previewImg.style.opacity = '1';
                previewImg.style.filter = 'none';
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFavicon(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let previewImg = document.getElementById('favicon-preview');
            let placeholder = document.getElementById('no-favicon-placeholder');
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                previewImg.style.opacity = '1';
                previewImg.style.filter = 'none';
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
