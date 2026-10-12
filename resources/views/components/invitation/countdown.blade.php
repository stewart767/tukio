@props([
    'target' => now()->addDays(30)->toIso8601String(),
    'theme' => 'gold', // gold, dark, floral, minimal
    'label' => 'COUNTDOWN TO THE MOMENT'
])

<div x-data="countdownTimer('{{ $target }}')" x-init="initTimer()" class="w-full max-w-xl mx-auto my-8">
    @if($label)
        <div class="text-center mb-4">
            <span class="text-xs uppercase tracking-[0.25em] font-semibold opacity-80 inline-block px-4 py-1 rounded-full border border-current">
                {{ $label }}
            </span>
        </div>
    @endif

    <div class="grid grid-cols-4 gap-3 sm:gap-4 text-center">
        <!-- Days -->
        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg flex flex-col items-center justify-center transition-transform duration-300 hover:scale-105">
            <span class="text-2xl sm:text-4xl font-extrabold tracking-tight" x-text="days">00</span>
            <span class="text-[10px] sm:text-xs uppercase tracking-widest opacity-75 mt-1">Days</span>
        </div>

        <!-- Hours -->
        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg flex flex-col items-center justify-center transition-transform duration-300 hover:scale-105">
            <span class="text-2xl sm:text-4xl font-extrabold tracking-tight" x-text="hours">00</span>
            <span class="text-[10px] sm:text-xs uppercase tracking-widest opacity-75 mt-1">Hours</span>
        </div>

        <!-- Minutes -->
        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg flex flex-col items-center justify-center transition-transform duration-300 hover:scale-105">
            <span class="text-2xl sm:text-4xl font-extrabold tracking-tight" x-text="minutes">00</span>
            <span class="text-[10px] sm:text-xs uppercase tracking-widest opacity-75 mt-1">Mins</span>
        </div>

        <!-- Seconds -->
        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg flex flex-col items-center justify-center transition-transform duration-300 hover:scale-105">
            <span class="text-2xl sm:text-4xl font-extrabold tracking-tight animate-pulse text-amber-300" x-text="seconds">00</span>
            <span class="text-[10px] sm:text-xs uppercase tracking-widest opacity-75 mt-1">Secs</span>
        </div>
    </div>
</div>

<script>
if (typeof countdownTimer === 'undefined') {
    function countdownTimer(targetIso) {
        return {
            targetDate: new Date(targetIso).getTime(),
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            timer: null,
            initTimer() {
                this.update();
                this.timer = setInterval(() => {
                    this.update();
                }, 1000);
            },
            update() {
                const now = new Date().getTime();
                const distance = this.targetDate - now;

                if (distance < 0) {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    if (this.timer) clearInterval(this.timer);
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                this.days = d < 10 ? '0' + d : d.toString();
                this.hours = h < 10 ? '0' + h : h.toString();
                this.minutes = m < 10 ? '0' + m : m.toString();
                this.seconds = s < 10 ? '0' + s : s.toString();
            }
        };
    }
}
</script>
