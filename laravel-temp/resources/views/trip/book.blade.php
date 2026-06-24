@extends('layouts.app')

@section('title', 'Pesan Trip — ' . $trip->name . ' | Tapak Lampung')

@push('styles')
<style>
/* =============================================
   BOOKING PAGE — PREMIUM DESIGN
   ============================================= */
.book-wrapper {
    min-height: 100vh;
    background: var(--bg);
    padding-top: 80px;
}

/* ── Hero Banner ── */
.book-hero {
    position: relative;
    height: 340px;
    background-image: url('{{ $trip->image_url }}');
    background-size: cover;
    background-position: center;
    overflow: hidden;
}
.book-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,.35) 0%, rgba(0,0,0,.72) 100%);
}
.book-hero-content {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 2.5rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
    left: 0; right: 0;
}
.book-hero-tags {
    display: flex;
    gap: .5rem;
    margin-bottom: .75rem;
    flex-wrap: wrap;
}
.book-hero-tag {
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: .72rem;
    font-weight: 600;
    padding: .25rem .75rem;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.3);
    letter-spacing: .04em;
    text-transform: uppercase;
}
.book-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.1rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.15;
    margin: 0 0 .5rem;
}
.book-hero-org {
    display: flex;
    align-items: center;
    gap: .6rem;
}
.book-hero-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    font-size: .8rem;
    display: grid;
    place-items: center;
    border: 2px solid rgba(255,255,255,.5);
}
.book-hero-org-name {
    color: rgba(255,255,255,.9);
    font-size: .88rem;
    font-weight: 500;
}
.book-back-btn {
    position: absolute;
    top: 1.25rem; left: 1.5rem;
    z-index: 10;
    display: flex; align-items: center; gap: .45rem;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    font-size: .85rem;
    font-weight: 500;
    padding: .5rem 1rem;
    border-radius: 999px;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s;
}
.book-back-btn:hover { background: rgba(255,255,255,.28); }
.book-back-btn svg { width: 16px; height: 16px; fill: none; stroke: #fff; stroke-width: 2.2; }

/* ── Main Layout ── */
.book-main {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 5rem;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    align-items: start;
}

/* ── Form Card ── */
.book-form-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 24px rgba(0,0,0,.05);
}
.book-form-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 .35rem;
}
.book-form-sub {
    font-size: .87rem;
    color: var(--text-muted);
    margin: 0 0 1.75rem;
}
.form-divider {
    height: 1px;
    background: var(--border);
    margin: 1.5rem 0;
}
.form-section-label {
    font-size: .75rem;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin: 0 0 1rem;
}

/* Fields */
.field-group {
    display: grid;
    gap: 1.1rem;
}
.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.field {
    display: flex;
    flex-direction: column;
    gap: .4rem;
}
.field label {
    font-size: .82rem;
    font-weight: 600;
    color: var(--text);
}
.field input,
.field textarea,
.field select {
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: .7rem 1rem;
    font-size: .9rem;
    color: var(--text);
    font-family: inherit;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    width: 100%;
    box-sizing: border-box;
}
.field input:focus,
.field textarea:focus,
.field select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 34,197,94), .12);
}
.field textarea { resize: vertical; min-height: 90px; }
.field-hint {
    font-size: .75rem;
    color: var(--text-muted);
}
.field-error {
    font-size: .75rem;
    color: #ef4444;
    display: flex; align-items: center; gap: .3rem;
}

/* Participants counter */
.participants-ctrl {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    background: var(--bg);
    width: fit-content;
}
.participants-ctrl button {
    background: transparent;
    border: none;
    cursor: pointer;
    width: 42px; height: 42px;
    font-size: 1.3rem;
    color: var(--primary);
    font-weight: 700;
    transition: background .15s;
    display: grid; place-items: center;
}
.participants-ctrl button:hover { background: var(--border); }
.participants-ctrl input {
    border: none !important;
    box-shadow: none !important;
    width: 56px;
    text-align: center;
    font-weight: 700;
    font-size: 1rem;
    border-radius: 0 !important;
    padding: 0 !important;
}
.quota-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .78rem;
    color: var(--text-muted);
    background: var(--border);
    padding: .3rem .7rem;
    border-radius: 999px;
    margin-left: .75rem;
}
.quota-badge.low { color: #ef4444; background: rgba(239,68,68,.1); }

/* Submit */
.book-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary) 0%, #16a34a 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 1rem;
    font-size: 1rem;
    font-weight: 700;
    font-family: 'Outfit', sans-serif;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s, opacity .2s;
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    margin-top: 1.75rem;
}
.book-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(34,197,94,.35);
}
.book-submit-btn:active { transform: translateY(0); }
.book-submit-btn svg { width: 20px; height: 20px; fill: none; stroke: #fff; stroke-width: 2.2; }

/* ── Summary Sidebar ── */
.book-summary {
    position: sticky;
    top: 96px;
}
.book-summary-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
.summary-img {
    height: 160px;
    background-image: url('{{ $trip->image_url }}');
    background-size: cover;
    background-position: center;
    position: relative;
}
.summary-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,.5));
}
.summary-body {
    padding: 1.5rem;
}
.summary-trip-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 .75rem;
}
.summary-rows {
    display: flex;
    flex-direction: column;
    gap: .6rem;
    margin-bottom: 1.25rem;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .85rem;
}
.summary-row-label {
    color: var(--text-muted);
    display: flex; align-items: center; gap: .4rem;
}
.summary-row-label svg { width: 14px; height: 14px; stroke: var(--text-muted); fill: none; stroke-width: 2; flex-shrink: 0; }
.summary-row-value { font-weight: 600; color: var(--text); text-align: right; }
.summary-divider {
    height: 1px;
    background: var(--border);
    margin: 1rem 0;
}
.summary-price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.summary-price-label { font-size: .82rem; color: var(--text-muted); }
.summary-total {
    font-family: 'Outfit', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--primary);
    letter-spacing: -.01em;
}
.summary-per {
    font-size: .75rem;
    color: var(--text-muted);
    text-align: right;
    margin-top: .15rem;
}
.summary-guarantee {
    display: flex;
    gap: .6rem;
    align-items: flex-start;
    background: rgba(34,197,94,.08);
    border: 1px solid rgba(34,197,94,.2);
    border-radius: 10px;
    padding: .9rem 1rem;
    margin-top: 1.25rem;
}
.summary-guarantee svg { width: 18px; height: 18px; fill: none; stroke: var(--primary); stroke-width: 2; flex-shrink: 0; margin-top: .05rem; }
.summary-guarantee p { font-size: .78rem; color: var(--text-muted); margin: 0; line-height: 1.5; }
.summary-guarantee strong { color: var(--text); }

/* Alert error */
.alert-error {
    background: rgba(239,68,68,.08);
    border: 1px solid rgba(239,68,68,.25);
    border-radius: 10px;
    padding: .9rem 1rem;
    margin-bottom: 1.5rem;
    font-size: .85rem;
    color: #b91c1c;
}
.alert-error ul { margin: .35rem 0 0 1.2rem; padding: 0; }

/* ── Responsive ── */
@media (max-width: 860px) {
    .book-main {
        grid-template-columns: 1fr;
    }
    .book-summary {
        order: -1;
        position: static;
    }
    .book-hero-title { font-size: 1.55rem; }
    .field-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .book-form-card { padding: 1.25rem; }
    .book-hero { height: 260px; }
}
</style>
@endpush

@section('content')
<div class="book-wrapper">

    {{-- Hero Banner --}}
    <div class="book-hero">
        <a href="{{ url()->previous() }}" class="book-back-btn">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
        <div class="book-hero-content">
            <div class="book-hero-tags">
                @foreach($trip->tags as $tag)
                    <span class="book-hero-tag">{{ $tag->tag }}</span>
                @endforeach
            </div>
            <h1 class="book-hero-title">{{ $trip->name }}</h1>
            <div class="book-hero-org">
                <div class="book-hero-avatar">{{ $trip->organizer_avatar }}</div>
                <span class="book-hero-org-name">{{ $trip->organizer_name }} &bull; Organizer Terverifikasi ✓</span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="book-main">

        {{-- ── FORM CARD ── --}}
        <div class="book-form-card">
            <h2 class="book-form-title">Detail Pemesanan</h2>
            <p class="book-form-sub">Isi data dengan benar. Konfirmasi akan dikirim melalui email atau WhatsApp.</p>

            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="alert-error">
                <strong>⚠️ Perbaiki data berikut:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="booking-form" action="{{ route('trips.store', $trip->id) }}" method="POST">
                @csrf

                {{-- Data Peserta --}}
                <p class="form-section-label">👤 Data Pemesan</p>
                <div class="field-group">
                    <div class="field">
                        <label for="name">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" placeholder="Nama sesuai KTP / Paspor"
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label for="phone">No. WhatsApp *</label>
                            <input type="tel" id="phone" name="phone" placeholder="08xxxxxxxxxx"
                                   value="{{ old('phone') }}" required>
                        </div>
                        <div class="field">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" placeholder="email@kamu.com"
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Jumlah Peserta --}}
                <p class="form-section-label">👥 Jumlah Peserta</p>
                <div class="field">
                    <label>Pilih jumlah peserta</label>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <div class="participants-ctrl">
                            <button type="button" id="dec-btn" onclick="adjustParticipants(-1)">−</button>
                            <input type="number" id="participants" name="participants"
                                   value="{{ old('participants', 1) }}" min="1"
                                   max="{{ $trip->slots_left }}" readonly>
                            <button type="button" id="inc-btn" onclick="adjustParticipants(1)">+</button>
                        </div>
                        @php $slots = $trip->slots_left; @endphp
                        <span class="quota-badge {{ $slots <= 3 ? 'low' : '' }}">
                            {{ $slots <= 3 ? '🔴' : '🟢' }} {{ $slots }} slot tersisa
                        </span>
                    </div>
                    <p class="field-hint">Maksimum {{ $slots }} peserta untuk trip ini.</p>
                </div>

                <div class="form-divider"></div>

                {{-- Catatan --}}
                <p class="form-section-label">📝 Catatan Tambahan</p>
                <div class="field">
                    <label for="notes">Catatan / Permintaan Khusus <span style="font-weight:400;color:var(--text-muted)">(opsional)</span></label>
                    <textarea id="notes" name="notes" placeholder="Contoh: alergi makanan, kebutuhan khusus, dll...">{{ old('notes') }}</textarea>
                </div>

                {{-- Submit --}}
                <button type="submit" class="book-submit-btn" id="submit-btn">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Konfirmasi Pemesanan
                </button>

            </form>
        </div>

        {{-- ── SUMMARY SIDEBAR ── --}}
        <aside class="book-summary">
            <div class="book-summary-card">
                <div class="summary-img"></div>
                <div class="summary-body">
                    <p class="summary-trip-name">{{ $trip->name }}</p>
                    <div class="summary-rows">
                        <div class="summary-row">
                            <span class="summary-row-label">
                                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Jadwal
                            </span>
                            <span class="summary-row-value">{{ \Carbon\Carbon::parse($trip->schedule_date)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-row-label">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Durasi
                            </span>
                            <span class="summary-row-value">{{ $trip->duration }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-row-label">
                                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Organizer
                            </span>
                            <span class="summary-row-value">{{ $trip->organizer_name }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-row-label">
                                <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                Rating
                            </span>
                            <span class="summary-row-value">⭐ {{ $trip->rating }} ({{ $trip->reviews_count }} ulasan)</span>
                        </div>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-price-row">
                        <span class="summary-price-label">Total pembayaran</span>
                        <div>
                            <div class="summary-total" id="summary-total">{{ $trip->formatted_price }}</div>
                            <div class="summary-per" id="summary-per">1 peserta</div>
                        </div>
                    </div>

                    <div class="summary-guarantee">
                        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        <p><strong>Booking Aman & Terpercaya.</strong> Pembayaran dilakukan setelah konfirmasi dari organizer. Tidak ada biaya tersembunyi.</p>
                    </div>
                </div>
            </div>
        </aside>

    </div>{{-- /book-main --}}
</div>
@endsection

@push('scripts')
<script>
const pricePerPerson = {{ $trip->price }};
const maxSlots = {{ $trip->slots_left }};
const participantsInput = document.getElementById('participants');
const totalEl = document.getElementById('summary-total');
const perEl   = document.getElementById('summary-per');

function formatRupiah(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}
function updateSummary() {
    const qty = parseInt(participantsInput.value) || 1;
    totalEl.textContent = formatRupiah(pricePerPerson * qty);
    perEl.textContent   = qty + ' peserta';
}
function adjustParticipants(delta) {
    let v = parseInt(participantsInput.value) || 1;
    v = Math.min(maxSlots, Math.max(1, v + delta));
    participantsInput.value = v;
    updateSummary();
}

participantsInput.addEventListener('input', updateSummary);
updateSummary();

// Prevent double submit
document.getElementById('booking-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" style="animation:spin 1s linear infinite"><circle cx="12" cy="12" r="10" stroke-dasharray="32 10"/></svg> Memproses...';
});
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush
