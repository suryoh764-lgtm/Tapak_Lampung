@extends('layouts.app')

@section('title', 'Pemesanan Berhasil! | Tapak Lampung')

@push('styles')
<style>
/* =============================================
   BOOKING SUCCESS PAGE — PREMIUM DESIGN
   ============================================= */
.success-wrapper {
    min-height: 100vh;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6rem 1.5rem 4rem;
}
.success-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 3rem 2.5rem;
    max-width: 560px;
    width: 100%;
    text-align: center;
    box-shadow: 0 12px 48px rgba(0,0,0,.08);
    animation: slideUp .5s cubic-bezier(.25,.46,.45,.94) both;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Icon ── */
.success-icon-wrap {
    width: 90px; height: 90px;
    background: linear-gradient(135deg, #22c55e22, #16a34a22);
    border: 2px solid rgba(34,197,94,.3);
    border-radius: 50%;
    display: grid;
    place-items: center;
    margin: 0 auto 1.5rem;
    position: relative;
}
.success-icon-wrap::before {
    content: '';
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    border: 2px solid rgba(34,197,94,.12);
}
.success-icon-wrap svg {
    width: 42px; height: 42px;
    fill: none;
    stroke: var(--primary);
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    animation: drawCheck .6s .3s ease both;
    stroke-dasharray: 60;
    stroke-dashoffset: 60;
}
@keyframes drawCheck {
    to { stroke-dashoffset: 0; }
}

.success-label {
    display: inline-block;
    background: rgba(34,197,94,.12);
    color: var(--primary);
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    padding: .3rem .9rem;
    border-radius: 999px;
    margin-bottom: 1rem;
}
.success-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text);
    margin: 0 0 .6rem;
    line-height: 1.2;
}
.success-sub {
    font-size: .9rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin: 0 0 2rem;
}

/* Booking Code */
.booking-code-box {
    background: var(--bg);
    border: 2px dashed var(--border);
    border-radius: 14px;
    padding: 1.1rem 1.5rem;
    margin: 0 0 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .3rem;
}
.booking-code-label { font-size: .75rem; color: var(--text-muted); font-weight: 600; letter-spacing: .06em; text-transform: uppercase; }
.booking-code {
    font-family: 'Courier New', monospace;
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--primary);
    letter-spacing: .12em;
}

/* Detail rows */
.success-details {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    text-align: left;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .55rem 0;
    font-size: .87rem;
}
.detail-row:not(:last-child) { border-bottom: 1px solid var(--border); }
.detail-row-label { color: var(--text-muted); }
.detail-row-value { font-weight: 600; color: var(--text); text-align: right; max-width: 60%; }
.detail-row-value.total-val {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--primary);
}

/* Buttons */
.success-actions {
    display: flex;
    flex-direction: column;
    gap: .85rem;
}
.btn-primary-full {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: linear-gradient(135deg, var(--primary) 0%, #16a34a 100%);
    color: #fff;
    font-weight: 700;
    font-size: .95rem;
    font-family: 'Outfit', sans-serif;
    padding: .85rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    border: none;
    cursor: pointer;
}
.btn-primary-full:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(34,197,94,.35);
}
.btn-outline-full {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: transparent;
    color: var(--text);
    font-weight: 600;
    font-size: .9rem;
    padding: .8rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    border: 1.5px solid var(--border);
    transition: border-color .2s, background .2s;
}
.btn-outline-full:hover {
    border-color: var(--primary);
    background: rgba(34,197,94,.05);
}
.btn-primary-full svg,
.btn-outline-full svg {
    width: 18px; height: 18px;
    fill: none; stroke: currentColor; stroke-width: 2;
}

/* Note strip */
.success-note {
    display: flex;
    gap: .6rem;
    align-items: flex-start;
    background: rgba(234,179,8,.07);
    border: 1px solid rgba(234,179,8,.25);
    border-radius: 10px;
    padding: .85rem 1rem;
    text-align: left;
    margin-bottom: 1.5rem;
}
.success-note svg { width: 16px; height: 16px; stroke: #ca8a04; fill: none; flex-shrink: 0; margin-top: .1rem; }
.success-note p { font-size: .78rem; color: var(--text-muted); margin: 0; line-height: 1.55; }
.success-note strong { color: #ca8a04; }

@media (max-width: 480px) {
    .success-card { padding: 2rem 1.25rem; border-radius: 20px; }
    .success-title { font-size: 1.45rem; }
    .booking-code { font-size: 1.15rem; }
}
</style>
@endpush

@section('content')
<div class="success-wrapper">
    <div class="success-card">

        {{-- Animated Check Icon --}}
        <div class="success-icon-wrap">
            <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <span class="success-label">✓ Pemesanan Terkirim</span>
        <h1 class="success-title">Selamat, {{ $booking['name'] }}!</h1>
        <p class="success-sub">Permintaan booking Anda telah kami terima. Tim organizer akan menghubungi Anda dalam waktu <strong>1×24 jam</strong> untuk konfirmasi pembayaran.</p>

        {{-- Booking Code --}}
        <div class="booking-code-box">
            <span class="booking-code-label">Kode Booking Anda</span>
            <span class="booking-code">{{ $booking['booking_code'] }}</span>
        </div>

        {{-- Detail --}}
        <div class="success-details">
            <div class="detail-row">
                <span class="detail-row-label">Trip</span>
                <span class="detail-row-value">{{ $booking['trip_name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">Organizer</span>
                <span class="detail-row-value">{{ $booking['organizer'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">Jadwal</span>
                <span class="detail-row-value">{{ \Carbon\Carbon::parse($booking['schedule'])->locale('id')->isoFormat('D MMMM YYYY') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">Durasi</span>
                <span class="detail-row-value">{{ $booking['duration'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">Peserta</span>
                <span class="detail-row-value">{{ $booking['participants'] }} orang</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">WhatsApp</span>
                <span class="detail-row-value">{{ $booking['phone'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">Email</span>
                <span class="detail-row-value">{{ $booking['email'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-row-label">Total Pembayaran</span>
                <span class="detail-row-value total-val">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Note --}}
        <div class="success-note">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p><strong>Simpan kode booking ini.</strong> Organizer akan menghubungi nomor WhatsApp <strong>{{ $booking['phone'] }}</strong> untuk instruksi pembayaran. Pembayaran dapat dilakukan via transfer atau dompet digital.</p>
        </div>

        {{-- Actions --}}
        <div class="success-actions">
            <a href="{{ route('booking.invoice') }}" class="btn-primary-full" style="background:linear-gradient(135deg,#1a7a4a,#2ecc7a);">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Lihat & Cetak Kwitansi
            </a>
            <a href="https://wa.me/62{{ ltrim($booking['phone'], '0') }}?text={{ urlencode('Halo! Saya sudah booking trip ' . ($booking['trip_name'] ?? '') . ' dengan kode booking: ' . $booking['booking_code']) }}"
               target="_blank" class="btn-outline-full">
                <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Hubungi Organizer via WhatsApp
            </a>
            <a href="{{ route('home') }}#trips" class="btn-outline-full">
                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Lihat Trip Lainnya
            </a>
        </div>

    </div>
</div>
@endsection
