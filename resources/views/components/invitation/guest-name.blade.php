@props([
    'name' => 'Mpendwa Mgeni Wetu',
    'table' => null,
    'type' => null,
    'prefix' => 'DEAR HONORED GUEST',
    'class' => ''
])

<div class="text-center my-6 {{ $class }}">
    @if($prefix)
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] opacity-75 block mb-1">
            {{ $prefix }}
        </span>
    @endif

    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-heading leading-tight">
        {{ $name }}
    </h2>

    @if($table || $type)
        <div class="flex items-center justify-center gap-2 mt-2">
            @if($type && strtolower($type) === 'vip')
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-400 text-slate-950 tracking-wider shadow-sm">
                    VIP Guest
                </span>
            @endif

            @if($table)
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 backdrop-blur-md border border-white/20 text-white">
                    Table: {{ $table }}
                </span>
            @endif
        </div>
    @endif
</div>
