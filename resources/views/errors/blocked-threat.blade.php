<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ombi Limezuiwa - Tukio360 Threat Prevention</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-md w-full bg-slate-900 border border-amber-900/40 rounded-3xl p-8 text-center shadow-2xl shadow-amber-950/40">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-500 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-white mb-2">Ombi Limezuiwa / Request Blocked</h1>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Mfumo wetu wa kugundua na kuzuia mashambulizi (Intrusion Prevention System) umegundua maudhui yasiyo salama kwenye ombi hili na kulizuia mara moja.
        </p>

        @if(!empty($incident))
            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs font-mono text-slate-400 mb-6 text-left space-y-1">
                <div>Incident: <span class="text-amber-400 font-bold">{{ $incident->incident_uuid }}</span></div>
                <div>Category: <span class="text-slate-300 uppercase">{{ $incident->threat_type }}</span></div>
                <div>IP: <span class="text-slate-300">{{ $incident->source_ip }}</span></div>
            </div>
        @endif

        <p class="text-[11px] text-slate-500">
            Kumbukumbu ya tukio hili imehifadhiwa kwa ajili ya uchunguzi wa kiusalama.
        </p>
    </div>
</body>
</html>

