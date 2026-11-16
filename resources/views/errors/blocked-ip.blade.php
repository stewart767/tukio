<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ufikiaji Umezuiwa - Tukio360 Security</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-md w-full bg-slate-900 border border-rose-900/40 rounded-3xl p-8 text-center shadow-2xl shadow-rose-950/50">
        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-white mb-2">Ufikiaji Umezuiwa / Access Denied</h1>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Anwani yako ya mtandao (IP) imezuiwa kwa muda na mfumo wetu wa usalama wa mtandao kutokana na maombi yanayokiuka sera za ulinzi za mfumo.
        </p>

        <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs font-mono text-slate-400 mb-6">
            Source IP: <span class="text-rose-400 font-bold">{{ $ip ?? request()->ip() }}</span>
        </div>

        <p class="text-[11px] text-slate-500">
            Kama unadhani hili ni kosa, tafadhali wasiliana na msimamizi wa mfumo kupitia msaada rasmi wa Tukio360 ukitaja anwani yako ya IP hapo juu.
        </p>
    </div>
</body>
</html>

