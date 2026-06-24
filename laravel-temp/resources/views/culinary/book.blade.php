@extends('layouts.app')
@section('title', 'Reservasi ' . $restaurant->name . ' — Tapak Lampung')

@push('styles')
<style>
.book-wrapper{min-height:100vh;background:var(--bg);padding:6rem 1.5rem 4rem;display:flex;justify-content:center;}
.book-container{width:100%;max-width:600px;}
.book-back{display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);font-size:.85rem;text-decoration:none;margin-bottom:1.5rem;transition:color .2s;}
.book-back:hover{color:var(--primary);}
.book-back svg{width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;}

.book-card{background:var(--card-bg);border:1px solid var(--border);border-radius:22px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.07);}
.book-card-hero{background:linear-gradient(135deg,#0f4a2c,#1a7a4a);padding:2rem 2rem 1.5rem;position:relative;overflow:hidden;}
.book-card-hero::before{content:'';position:absolute;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,.05);top:-80px;right:-60px;}
.book-resto-icon{font-size:2.5rem;margin-bottom:.5rem;}
.book-resto-name{font-family:'Outfit',sans-serif;font-size:1.3rem;font-weight:700;color:#fff;margin-bottom:.25rem;}
.book-resto-addr{font-size:.8rem;color:rgba(255,255,255,.65);}
.book-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);padding:.25rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600;margin-top:.6rem;}

.book-body{padding:2rem;}
.book-title{font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:1.5rem;color:var(--text);}

.form-group{margin-bottom:1.1rem;}
.form-label{display:block;font-size:.8rem;font-weight:600;color:var(--text-muted);margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.05em;}
.form-input{width:100%;padding:11px 14px;background:var(--bg);border:1.5px solid var(--border);border-radius:10px;font-family:'DM Sans',sans-serif;font-size:.9rem;color:var(--text);outline:none;transition:all .2s;}
.form-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(34,197,94,.1);}
.form-input::placeholder{color:var(--text-muted);opacity:.7;}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.form-hint{font-size:.75rem;color:var(--text-muted);margin-top:.3rem;}

.book-info{background:var(--bg);border:1px solid var(--border);border-radius:12px;padding:1rem 1.2rem;margin-bottom:1.5rem;}
.book-info-row{display:flex;justify-content:space-between;font-size:.82rem;padding:.3rem 0;}
.book-info-row:not(:last-child){border-bottom:1px dashed var(--border);}
.book-info-row .k{color:var(--text-muted);}
.book-info-row .v{font-weight:600;color:var(--text);}

.btn-book{width:100%;padding:14px;background:linear-gradient(135deg,#1a7a4a,#2ecc7a);color:#fff;border:none;border-radius:12px;font-family:'Outfit',sans-serif;font-size:1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:all .25s;box-shadow:0 4px 16px rgba(26,122,74,.25);}
.btn-book:hover{transform:translateY(-2px);box-shadow:0 6px 22px rgba(26,122,74,.35);}
.btn-book svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;}

@media(max-width:500px){.form-row-2{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div class="book-wrapper">
    <div class="book-container">
        <a href="{{ route('culinary.restaurant', $restaurant->id) }}" class="book-back">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke {{ $restaurant->name }}
        </a>

        <div class="book-card">
            <div class="book-card-hero">
                <div class="book-resto-icon">🍽️</div>
                <div class="book-resto-name">{{ $restaurant->name }}</div>
                <div class="book-resto-addr">📍 {{ $restaurant->address }}</div>
                <div class="book-badge">
                    <span>🕐</span>
                    {{ $restaurant->open_time }} – {{ $restaurant->close_time }} · {{ $restaurant->open_days }}
                </div>
            </div>

            <div class="book-body">
                <div class="book-title">Form Reservasi Meja</div>

                <div class="book-info">
                    <div class="book-info-row"><span class="k">Kuliner</span><span class="v">{{ $restaurant->culinary->name ?? '-' }}</span></div>
                    <div class="book-info-row"><span class="k">Harga Estimasi</span><span class="v">{{ $restaurant->price_range }}</span></div>
                    <div class="book-info-row"><span class="k">Rating</span><span class="v">⭐ {{ $restaurant->rating }} ({{ $restaurant->reviews_count }} ulasan)</span></div>
                </div>

                <form method="POST" action="{{ route('culinary.book.store', $restaurant->id) }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                        @error('name')<span style="color:#e53e3e;font-size:.75rem;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp *</label>
                            <input type="tel" name="phone" class="form-input" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-input" placeholder="email@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Tanggal Kunjungan *</label>
                            <input type="date" name="date" class="form-input" min="{{ date('Y-m-d') }}" value="{{ old('date') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Tamu *</label>
                            <input type="number" name="pax" class="form-input" placeholder="2" min="1" max="100" value="{{ old('pax', 2) }}" required>
                            <div class="form-hint">Orang / pax</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Khusus</label>
                        <input type="text" name="notes" class="form-input" placeholder="Alergi, preferensi tempat duduk, dll." value="{{ old('notes') }}">
                    </div>

                    <button type="submit" class="btn-book">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Konfirmasi Reservasi & Dapatkan Kwitansi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
