@extends('layouts.app')

@section('title', 'Warung & Resto — ' . $culinary->name . ' | Tapak Lampung')

@push('styles')
<style>
/* =============================================
   CULINARY SHOW PAGE — Restaurant List
   ============================================= */

/* ── Hero ── */
.cul-hero {
    position: relative;
    height: 320px;
    background-image: url('{{ $culinary->image_url }}');
    background-size: cover;
    background-position: center;
    margin-top: 0;
}
.cul-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,.3), rgba(0,0,0,.75));
}
.cul-hero-content {
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
.cul-hero-tag {
    display: inline-block;
    background: var(--primary);
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    padding: .3rem .9rem;
    border-radius: 999px;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: .75rem;
    width: fit-content;
}
.cul-hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 .5rem;
    line-height: 1.15;
}
.cul-hero-desc {
    color: rgba(255,255,255,.85);
    font-size: .92rem;
    max-width: 560px;
    line-height: 1.6;
}
.cul-back-btn {
    position: absolute;
    top: 1.5rem; left: 1.5rem;
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
.cul-back-btn:hover { background: rgba(255,255,255,.28); }
.cul-back-btn svg { width: 16px; height: 16px; fill: none; stroke: #fff; stroke-width: 2.2; }

/* Spice indicator in hero */
.cul-hero-spice {
    display: flex; align-items: center; gap: .5rem;
    margin-top: .6rem;
}
.cul-hero-spice-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    background: rgba(255,255,255,.3);
}
.cul-hero-spice-dot.on { background: #f97316; }
.cul-hero-spice-label { font-size: .78rem; color: rgba(255,255,255,.75); }

/* ── Main Layout ── */
.cul-main {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 5rem;
}

/* ── Section header ── */
.cul-section-head {
    margin-bottom: 1.75rem;
}
.cul-section-label {
    font-size: .75rem;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin: 0 0 .4rem;
}
.cul-section-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text);
    margin: 0;
}
.cul-section-sub {
    font-size: .88rem;
    color: var(--text-muted);
    margin: .3rem 0 0;
}

/* ── Restaurant Grid ── */
.resto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3.5rem;
}
.resto-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: transform .25s, box-shadow .25s, border-color .25s;
    display: flex;
    flex-direction: column;
    cursor: pointer;
}
.resto-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 40px rgba(0,0,0,.1);
    border-color: var(--primary);
}
.resto-card-img {
    height: 175px;
    background-size: cover;
    background-position: center;
    position: relative;
    flex-shrink: 0;
}
.resto-open-badge {
    position: absolute;
    top: .75rem; right: .75rem;
    font-size: .7rem;
    font-weight: 700;
    padding: .28rem .7rem;
    border-radius: 999px;
    letter-spacing: .04em;
}
.resto-open-badge.open   { background: #22c55e; color: #fff; }
.resto-open-badge.closed { background: #ef4444; color: #fff; }

.resto-card-body {
    padding: 1.25rem 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.resto-card-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 .35rem;
}
.resto-card-district {
    font-size: .78rem;
    color: var(--text-muted);
    display: flex; align-items: center; gap: .35rem;
    margin-bottom: .75rem;
}
.resto-card-district svg { width: 13px; height: 13px; stroke: var(--text-muted); fill: none; stroke-width: 2; }
.resto-card-rows {
    display: flex;
    flex-direction: column;
    gap: .45rem;
    margin-bottom: .9rem;
}
.resto-card-row {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .82rem;
    color: var(--text-muted);
}
.resto-card-row svg { width: 14px; height: 14px; stroke: var(--primary); fill: none; stroke-width: 2; flex-shrink: 0; }
.resto-card-row span { color: var(--text); font-weight: 500; }
.resto-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: .75rem;
    border-top: 1px solid var(--border);
}
.resto-rating {
    display: flex; align-items: center; gap: .3rem;
    font-size: .82rem;
    font-weight: 700;
    color: var(--text);
}
.resto-rating svg { width: 14px; height: 14px; fill: #f59e0b; stroke: #f59e0b; }
.resto-rating span { color: var(--text-muted); font-weight: 400; }
.resto-price {
    font-size: .78rem;
    color: var(--primary);
    font-weight: 600;
}
.resto-arrow {
    display: flex;
    align-items: center;
    gap: .35rem;
    font-size: .8rem;
    font-weight: 600;
    color: var(--primary);
}
.resto-arrow svg { width: 15px; height: 15px; fill: none; stroke: var(--primary); stroke-width: 2.5; }

/* ── Other Culinaries ── */
.other-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 1rem;
}
.other-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: .9rem;
    transition: border-color .2s, transform .2s;
}
.other-card:hover { border-color: var(--primary); transform: translateY(-2px); }
.other-card-img {
    width: 54px; height: 54px;
    border-radius: 10px;
    background-size: cover;
    background-position: center;
    flex-shrink: 0;
}
.other-card-name {
    font-weight: 700;
    font-size: .88rem;
    color: var(--text);
}
.other-card-cat {
    font-size: .75rem;
    color: var(--text-muted);
}

@media (max-width: 640px) {
    .cul-hero { height: 260px; }
    .cul-hero-title { font-size: 1.6rem; }
    .resto-grid { grid-template-columns: 1fr; }
    .other-grid { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@section('content')
<div style="padding-top:70px;">

    {{-- ── HERO ── --}}
    <div class="cul-hero">
        <a href="{{ route('home') }}#kuliner" class="cul-back-btn">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Beranda
        </a>
        <div class="cul-hero-content">
            <span class="cul-hero-tag">{{ $culinary->category }}</span>
            <h1 class="cul-hero-title">{{ $culinary->name }}</h1>
            <p class="cul-hero-desc">{{ $culinary->description }}</p>
            <div class="cul-hero-spice">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="cul-hero-spice-dot {{ $i <= $culinary->spice_level ? 'on' : '' }}"></div>
                @endfor
                <span class="cul-hero-spice-label">Tingkat pedas {{ $culinary->spice_level }}/5</span>
            </div>
        </div>
    </div>

    <div class="cul-main">

        {{-- ── RESTAURANT LIST ── --}}
        <div class="cul-section-head">
            <p class="cul-section-label">🍽️ Tempat Makan</p>
            <h2 class="cul-section-title">Warung & Restoran yang Menjual {{ $culinary->name }}</h2>
            <p class="cul-section-sub">{{ $restaurants->count() }} tempat ditemukan di Bandar Lampung dan sekitarnya</p>
        </div>

        <div class="resto-grid">
            @forelse($restaurants as $resto)
            <a class="resto-card" href="{{ route('culinary.restaurant', $resto->id) }}">
                <div class="resto-card-img" style="background-image:url('{{ $resto->image_url }}');">
                    <span class="resto-open-badge {{ $resto->is_open_now ? 'open' : 'closed' }}">
                        {{ $resto->is_open_now ? '● Buka' : '● Tutup' }}
                    </span>
                </div>
                <div class="resto-card-body">
                    <div class="resto-card-name">{{ $resto->name }}</div>
                    <div class="resto-card-district">
                        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $resto->district }}
                    </div>
                    <div class="resto-card-rows">
                        <div class="resto-card-row">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>{{ $resto->open_hours }}</span>
                            &bull; {{ $resto->open_days }}
                        </div>
                        <div class="resto-card-row">
                            <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            <span>{{ $resto->price_range }}</span>
                        </div>
                        @if($resto->phone)
                        <div class="resto-card-row">
                            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.5 19.79 19.79 0 013 .84 2 2 0 015 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L9.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
                            <span>{{ $resto->phone }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="resto-card-footer">
                        <div class="resto-rating">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            {{ number_format($resto->rating, 1) }}
                            <span>({{ $resto->reviews_count }} ulasan)</span>
                        </div>
                        <div class="resto-arrow">
                            Detail
                            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <p style="color:var(--text-muted);padding:2rem 0;">Belum ada restoran terdaftar.</p>
            @endforelse
        </div>

        {{-- ── OTHER CULINARIES ── --}}
        @if($others->count())
        <div class="cul-section-head">
            <p class="cul-section-label">🍜 Kuliner Lainnya</p>
            <h2 class="cul-section-title" style="font-size:1.2rem;">Cicipi Kuliner Khas Lampung Lainnya</h2>
        </div>
        <div class="other-grid">
            @foreach($others as $other)
            <a class="other-card" href="{{ route('culinary.show', $other->id) }}">
                <div class="other-card-img" style="background-image:url('{{ $other->image_url }}');"></div>
                <div>
                    <div class="other-card-name">{{ $other->name }}</div>
                    <div class="other-card-cat">{{ $other->category }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>{{-- /cul-main --}}
</div>
@endsection
