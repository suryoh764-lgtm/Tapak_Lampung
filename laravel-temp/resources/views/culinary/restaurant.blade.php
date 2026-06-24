@extends('layouts.app')

@section('title', $restaurant->name . ' — ' . $restaurant->culinary->name . ' | Tapak Lampung')

@push('styles')
<style>
/* =============================================
   RESTAURANT DETAIL PAGE
   ============================================= */
.resto-detail-wrap {
    min-height: 100vh;
    background: var(--bg);
    padding-top: 70px;
}

/* ── Hero ── */
.resto-hero {
    position: relative;
    height: 380px;
    background-image: url('{{ $restaurant->image_url }}');
    background-size: cover;
    background-position: center;
}
.resto-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,.2) 0%, rgba(0,0,0,.78) 100%);
}
.resto-hero-content {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 2.5rem 2rem;
    max-width: 1100px;
    margin: 0 auto;
    left: 0; right: 0;
}
.rh-status {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .75rem;
    font-weight: 700;
    padding: .3rem .85rem;
    border-radius: 999px;
    margin-bottom: .85rem;
    width: fit-content;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.rh-status.open   { background: #22c55e; color: #fff; }
.rh-status.closed { background: #ef4444; color: #fff; }
.rh-status svg { width: 10px; height: 10px; fill: currentColor; }

.rh-culinary-tag {
    font-size: .78rem;
    color: rgba(255,255,255,.7);
    margin-bottom: .4rem;
}
.rh-culinary-tag a { color: rgba(255,255,255,.85); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
.rh-title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 .5rem;
    line-height: 1.1;
}
.rh-address {
    display: flex; align-items: flex-start; gap: .4rem;
    color: rgba(255,255,255,.8);
    font-size: .88rem;
}
.rh-address svg { width: 15px; height: 15px; stroke: rgba(255,255,255,.7); fill: none; stroke-width: 2; flex-shrink: 0; margin-top: .15rem; }

.rh-back {
    position: absolute;
    top: 1.4rem; left: 1.5rem;
    z-index: 10;
    display: flex; align-items: center; gap: .45rem;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    font-size: .84rem;
    font-weight: 500;
    padding: .5rem 1.1rem;
    border-radius: 999px;
    text-decoration: none;
    transition: background .2s;
}
.rh-back:hover { background: rgba(255,255,255,.28); }
.rh-back svg { width: 16px; height: 16px; fill: none; stroke: #fff; stroke-width: 2.2; }

/* ── Main Layout ── */
.resto-main {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 5rem;
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 2rem;
    align-items: start;
}

/* ── Info Cards ── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.75rem;
}
.info-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: .9rem;
}
.info-card-icon {
    width: 42px; height: 42px;
    border-radius: 11px;
    background: rgba(var(--primary-rgb, 34,197,94),.1);
    display: grid;
    place-items: center;
    flex-shrink: 0;
}
.info-card-icon svg { width: 20px; height: 20px; fill: none; stroke: var(--primary); stroke-width: 2; }
.info-card-label { font-size: .73rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .25rem; }
.info-card-value { font-weight: 700; color: var(--text); font-size: .95rem; line-height: 1.3; }
.info-card-sub { font-size: .75rem; color: var(--text-muted); margin-top: .1rem; }

/* ── Description Block ── */
.desc-block {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.75rem;
}
.desc-block h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 .75rem;
}
.desc-block p {
    font-size: .9rem;
    color: var(--text-muted);
    line-height: 1.75;
    margin: 0;
}

/* ── Sidebar Sticky ── */
.resto-sidebar {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Rating Card */
.rating-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 1.5rem;
    text-align: center;
}
.rating-big {
    font-family: 'Outfit', sans-serif;
    font-size: 3.5rem;
    font-weight: 900;
    color: var(--text);
    line-height: 1;
    margin-bottom: .4rem;
}
.stars {
    display: flex;
    justify-content: center;
    gap: .2rem;
    margin-bottom: .5rem;
}
.stars svg { width: 18px; height: 18px; }
.star-full  { fill: #f59e0b; stroke: #f59e0b; }
.star-empty { fill: var(--border); stroke: var(--border); }
.rating-count { font-size: .82rem; color: var(--text-muted); }
.rating-divider { height: 1px; background: var(--border); margin: 1rem 0; }
.rating-bars { display: flex; flex-direction: column; gap: .5rem; }
.rating-bar-row { display: flex; align-items: center; gap: .6rem; font-size: .75rem; }
.rating-bar-label { width: 10px; color: var(--text-muted); flex-shrink: 0; }
.rating-bar-track { flex: 1; height: 6px; background: var(--border); border-radius: 999px; overflow: hidden; }
.rating-bar-fill { height: 100%; background: #f59e0b; border-radius: 999px; }

/* CTA Sidebar */
.cta-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: .85rem;
}
.cta-card h4 {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .95rem;
    color: var(--text);
    margin: 0;
}
.btn-maps {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    background: linear-gradient(135deg, var(--primary), #16a34a);
    color: #fff;
    font-weight: 700;
    font-size: .9rem;
    padding: .85rem;
    border-radius: 12px;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
}
.btn-maps:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(34,197,94,.3); }
.btn-maps svg { width: 18px; height: 18px; fill: none; stroke: #fff; stroke-width: 2; }
.btn-phone {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    background: transparent;
    color: var(--text);
    font-weight: 600;
    font-size: .88rem;
    padding: .8rem;
    border-radius: 12px;
    text-decoration: none;
    border: 1.5px solid var(--border);
    transition: border-color .2s;
}
.btn-phone:hover { border-color: var(--primary); }
.btn-phone svg { width: 17px; height: 17px; fill: none; stroke: var(--primary); stroke-width: 2; }
.share-tip { font-size: .75rem; color: var(--text-muted); text-align: center; }

/* ── Similar Restos ── */
.similar-section { margin-top: 2.5rem; }
.similar-section h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem; font-weight: 700;
    color: var(--text); margin: 0 0 1rem;
}
.similar-list { display: flex; flex-direction: column; gap: .85rem; }
.similar-item {
    display: flex; align-items: center; gap: 1rem;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: .9rem;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s, transform .2s;
}
.similar-item:hover { border-color: var(--primary); transform: translateX(4px); }
.similar-img { width: 52px; height: 52px; border-radius: 10px; background-size: cover; background-position: center; flex-shrink: 0; }
.similar-name { font-weight: 700; font-size: .88rem; color: var(--text); margin-bottom: .15rem; }
.similar-meta { font-size: .75rem; color: var(--text-muted); }

@media (max-width: 860px) {
    .resto-main { grid-template-columns: 1fr; }
    .resto-sidebar { position: static; order: -1; }
    .info-grid { grid-template-columns: 1fr 1fr; }
    .resto-hero { height: 280px; }
    .rh-title { font-size: 1.6rem; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
    .rh-title { font-size: 1.35rem; }
}
</style>
@endpush

@section('content')
<div class="resto-detail-wrap">

    {{-- ── HERO ── --}}
    <div class="resto-hero">
        <a href="{{ route('culinary.show', $restaurant->culinary_id) }}" class="rh-back">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke {{ $restaurant->culinary->name }}
        </a>
        <div class="resto-hero-content">
            <span class="rh-status {{ $restaurant->is_open_now ? 'open' : 'closed' }}">
                <svg viewBox="0 0 10 10"><circle cx="5" cy="5" r="5"/></svg>
                {{ $restaurant->is_open_now ? 'Sedang Buka' : 'Sedang Tutup' }}
            </span>
            <p class="rh-culinary-tag">
                Menjual ›
                <a href="{{ route('culinary.show', $restaurant->culinary_id) }}">{{ $restaurant->culinary->name }}</a>
            </p>
            <h1 class="rh-title">{{ $restaurant->name }}</h1>
            <div class="rh-address">
                <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $restaurant->address }}, {{ $restaurant->district }}
            </div>
        </div>
    </div>

    <div class="resto-main">
        {{-- ════ LEFT COLUMN ════ --}}
        <div>
            {{-- Info Grid --}}
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="info-card-label">Jam Buka</div>
                        <div class="info-card-value">{{ $restaurant->open_hours }}</div>
                        <div class="info-card-sub">{{ $restaurant->open_days }}</div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div>
                        <div class="info-card-label">Kisaran Harga</div>
                        <div class="info-card-value">{{ $restaurant->price_range }}</div>
                        <div class="info-card-sub">per porsi</div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="info-card-label">Lokasi</div>
                        <div class="info-card-value">{{ $restaurant->district }}</div>
                        <div class="info-card-sub">{{ Str::limit($restaurant->address, 45) }}</div>
                    </div>
                </div>
                @if($restaurant->phone)
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.5a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.09 7.91"/></svg>
                    </div>
                    <div>
                        <div class="info-card-label">Telepon / WA</div>
                        <div class="info-card-value">{{ $restaurant->phone }}</div>
                        <div class="info-card-sub">Hubungi untuk reservasi</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Description --}}
            @if($restaurant->description)
            <div class="desc-block">
                <h3>📖 Tentang {{ $restaurant->name }}</h3>
                <p>{{ $restaurant->description }}</p>
            </div>
            @endif

            {{-- Similar Restos --}}
            @if($similar->count())
            <div class="similar-section">
                <h3>🍽️ Tempat Lain yang Menjual {{ $restaurant->culinary->name }}</h3>
                <div class="similar-list">
                    @foreach($similar as $s)
                    <a class="similar-item" href="{{ route('culinary.restaurant', $s->id) }}">
                        <div class="similar-img" style="background-image:url('{{ $s->image_url }}');"></div>
                        <div style="flex:1;">
                            <div class="similar-name">{{ $s->name }}</div>
                            <div class="similar-meta">{{ $s->district }} · {{ $s->open_hours }} · ⭐ {{ number_format($s->rating,1) }}</div>
                        </div>
                        <div style="color:var(--text-muted);font-size:.8rem;font-weight:600;">
                            {{ $s->is_open_now ? '🟢 Buka' : '🔴 Tutup' }}
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ════ SIDEBAR ════ --}}
        <aside class="resto-sidebar">

            {{-- Rating Card --}}
            <div class="rating-card">
                <div class="rating-big">{{ number_format($restaurant->rating, 1) }}</div>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <svg viewBox="0 0 24 24" class="{{ $i <= round($restaurant->rating) ? 'star-full' : 'star-empty' }}">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    @endfor
                </div>
                <div class="rating-count">{{ $restaurant->reviews_count }} ulasan pengunjung</div>
                <div class="rating-divider"></div>
                <div class="rating-bars">
                    @foreach([5,4,3,2,1] as $star)
                    @php
                        $pct = $restaurant->reviews_count > 0
                            ? min(100, max(5, ($star / 5) * 100 - ($star == 3 ? 20 : ($star < 3 ? 40 : 0))))
                            : 0;
                    @endphp
                    <div class="rating-bar-row">
                        <span class="rating-bar-label">{{ $star }}</span>
                        <div class="rating-bar-track">
                            <div class="rating-bar-fill" style="width:{{ $pct }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- CTA --}}
            <div class="cta-card">
                <h4>📋 Reservasi & Kunjungi</h4>
                <a href="{{ route('culinary.book', $restaurant->id) }}" class="btn-maps" style="background:linear-gradient(135deg,#1a7a4a,#2ecc7a);">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Reservasi & Dapatkan Kwitansi
                </a>
                @if($restaurant->maps_url)
                <a href="{{ $restaurant->maps_url }}" target="_blank" class="btn-phone">
                    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Buka di Google Maps
                </a>
                @endif
                @if($restaurant->phone)
                <a href="tel:{{ preg_replace('/[^0-9]/', '', $restaurant->phone) }}" class="btn-phone">
                    <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.5a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.09 7.91"/></svg>
                    Hubungi via Telepon
                </a>
                @endif
                <p class="share-tip">💡 Reservasi online &amp; terima kwitansi instan</p>
            </div>

        </aside>
    </div>
</div>
@endsection
