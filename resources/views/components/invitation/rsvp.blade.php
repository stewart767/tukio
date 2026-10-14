@props([
    'submitUrl' => '#',
    'currentStatus' => 'pending',
    'rsvpAt' => null,
    'guestName' => 'Mgeni Wetu',
    'class' => ''
])

<div x-data="rsvpHandler('{{ $submitUrl }}', '{{ $currentStatus }}')" id="rsvp" class="w-full max-w-xl mx-auto my-10 p-6 sm:p-8 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl text-center relative overflow-hidden {{ $class }}">
    <div class="text-xs uppercase tracking-[0.25em] font-extrabold opacity-80 mb-2">
        R. S. V. P.
    </div>
    <h3 class="text-2xl sm:text-3xl font-black font-heading mb-2">
        Je, Utajumuika Nasi?
    </h3>
    <p class="text-xs sm:text-sm opacity-90 mb-6 max-w-md mx-auto">
        Tafadhali thibitisha uwepo wako mapema ili kurahisisha maandalizi ya mapokezi.
    </p>

    <!-- Success Toast -->
    <div x-show="submitted" x-transition class="mb-6 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-400 text-emerald-100 text-sm font-bold flex items-center justify-center gap-2">
        <svg class="w-5 h-5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="successMessage">Taarifa yako ya RSVP imepokelewa kikamilifu!</span>
    </div>

    <!-- Active Status Badge -->
    <div class="mb-6 inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold"
         :class="{
            'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40': status === 'attending',
            'bg-rose-500/20 text-rose-300 border border-rose-400/40': status === 'declined',
            'bg-amber-500/20 text-amber-300 border border-amber-400/40': status === 'pending'
         }">
        <span class="w-2 h-2 rounded-full"
              :class="{
                'bg-emerald-400': status === 'attending',
                'bg-rose-400': status === 'declined',
                'bg-amber-400': status === 'pending'
              }"></span>
        <span x-text="statusLabel()">Status: Pending Response</span>
    </div>

    <form @submit.prevent="submitRsvp" class="space-y-4 text-left">
        <!-- Choice Buttons -->
        <div class="grid grid-cols-2 gap-3">
            <button type="button" 
                    @click="status = 'attending'"
                    class="p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-1.5 text-center"
                    :class="status === 'attending' ? 'bg-emerald-600/30 border-emerald-400 text-white shadow-lg' : 'bg-white/5 border-white/20 text-white/80 hover:bg-white/10'">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold text-sm">Ndiyo, Nitafika</span>
                <span class="text-[10px] opacity-75">I will attend</span>
            </button>

            <button type="button" 
                    @click="status = 'declined'"
                    class="p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-1.5 text-center"
                    :class="status === 'declined' ? 'bg-rose-600/30 border-rose-400 text-white shadow-lg' : 'bg-white/5 border-white/20 text-white/80 hover:bg-white/10'">
                <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="font-bold text-sm">Sitoweza Kufika</span>
                <span class="text-[10px] opacity-75">Cannot attend</span>
            </button>
        </div>

        <!-- Plus Ones & Wishes -->
        <div x-show="status === 'attending'" x-transition class="space-y-3 pt-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider opacity-80 mb-1">Idadi ya Watu (Attendees)</label>
                <select x-model="plusOnes" class="w-full bg-slate-900/60 border border-white/20 rounded-xl px-3.5 py-2.5 text-sm text-white focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    <option value="0">Mimi pekee (1 Person)</option>
                    <option value="1">Mimi + 1 Mgeni (2 People)</option>
                    <option value="2">Mimi + 2 Wageni (3 People)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider opacity-80 mb-1">Ujumbe / Hongera (Wishes / Notes)</label>
                <textarea x-model="notes" rows="2" placeholder="Ujumbe mfupi kwa wenye sherehe..." 
                          class="w-full bg-slate-900/60 border border-white/20 rounded-xl px-3.5 py-2 text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-amber-400 focus:outline-none"></textarea>
            </div>
        </div>

        <div x-show="status === 'declined'" x-transition class="space-y-3 pt-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider opacity-80 mb-1">Ujumbe / Salamu (Wishes)</label>
                <textarea x-model="notes" rows="2" placeholder="Salamu na pongezi kwa maharusi/wenye sherehe..." 
                          class="w-full bg-slate-900/60 border border-white/20 rounded-xl px-3.5 py-2 text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-amber-400 focus:outline-none"></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                :disabled="loading"
                class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 text-slate-950 font-extrabold text-sm tracking-wide shadow-xl shadow-amber-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 disabled:opacity-50">
            <span x-show="!loading">Thibitisha RSVP Sasa</span>
            <span x-show="loading" class="animate-spin">⏳</span>
            <span x-show="loading">Inatuma taarifa...</span>
        </button>
    </form>
</div>

<script>
if (typeof rsvpHandler === 'undefined') {
    function rsvpHandler(submitUrl, initialStatus) {
        return {
            submitUrl: submitUrl,
            status: initialStatus || 'pending',
            plusOnes: '0',
            notes: '',
            loading: false,
            submitted: false,
            successMessage: 'Asante sana! RSVP yako imerekodiwa kikamilifu.',
            statusLabel() {
                if (this.status === 'attending') return 'Thibitisho: Utafika (Attending)';
                if (this.status === 'declined') return 'Thibitisho: Hautofika (Declined)';
                return 'Hujathibitisha bado (Pending)';
            },
            async submitRsvp() {
                this.loading = true;

                if (!this.submitUrl || this.submitUrl === '#' || this.submitUrl === '') {
                    this.submitted = true;
                    this.successMessage = 'Asante sana! RSVP yako imerekodiwa kikamilifu (Hali ya Majaribio).';
                    this.loading = false;
                    setTimeout(() => this.submitted = false, 5000);
                    return;
                }

                try {
                    const res = await fetch(this.submitUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            rsvp_status: this.status,
                            plus_one_count: parseInt(this.plusOnes) || 0,
                            rsvp_notes: this.notes
                        })
                    });

                    const json = await res.json().catch(() => ({}));
                    if (res.ok && json.success) {
                        this.submitted = true;
                        this.successMessage = json.message || 'RSVP imepokelewa kikamilifu!';
                        setTimeout(() => this.submitted = false, 5000);
                    } else {
                        alert(json.message || 'Imeshindikana kutuma RSVP. Tafadhali jaribu tena.');
                    }
                } catch (e) {
                    alert('Hitilafu ya mawasiliano. Tafadhali jaribu tena.');
                } finally {
                    this.loading = false;
                }
            }
        };
    }
}
</script>
