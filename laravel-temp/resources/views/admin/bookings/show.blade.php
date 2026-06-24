@extends('admin.layouts.admin')
@section('title', 'Detail Pemesanan ' . $booking->booking_code)
@section('page-title', 'Detail Pemesanan')
@section('page-sub', 'Kode: ' . $booking->booking_code)

@section('content')

@if(session('success'))
<div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:14px 20px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <svg viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0;stroke:#059669;fill:none;stroke-width:2;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:14px 20px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <svg viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0;stroke:#dc2626;fill:none;stroke-width:2;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

    {{-- Kiri --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Info Pemesan --}}
        <div class="dash-card">
            <div class="dash-card-header"><h3>👤 Informasi Pemesan</h3></div>
            <div class="dash-card-body">
                <table style="width:100%;font-size:13px;border-collapse:collapse;">
                    <tr><td style="padding:10px 0;color:var(--text-3);width:38%;">Nama Lengkap</td><td style="font-weight:600;">{{ $booking->name }}</td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">Email</td><td>{{ $booking->email }}</td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">No. HP</td><td>{{ $booking->phone }}</td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">Kode Booking</td>
                        <td><span style="font-family:monospace;background:var(--bg);padding:3px 10px;border-radius:5px;border:1px solid var(--border);">{{ $booking->booking_code }}</span></td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">Tipe</td>
                        <td>{{ $booking->type === 'trip' ? '🏕️ Open Trip' : '🍽️ Kuliner' }}</td></tr>
                    @if($booking->notes)
                    <tr><td style="padding:10px 0;color:var(--text-3);">Catatan</td><td style="color:var(--text-2);">{{ $booking->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Info Trip/Kuliner --}}
        <div class="dash-card">
            <div class="dash-card-header"><h3>{{ $booking->type === 'trip' ? '🏕️ Detail Trip' : '🍽️ Detail Kuliner' }}</h3></div>
            <div class="dash-card-body">
                @if($booking->trip)
                @php $img = str_starts_with($booking->trip->image_path??'','images/')||str_starts_with($booking->trip->image_path??'','http') ? asset($booking->trip->image_path) : asset('storage/'.$booking->trip->image_path); @endphp
                <div style="display:flex;gap:14px;align-items:center;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                    <img src="{{ $img }}" style="width:72px;height:54px;border-radius:8px;object-fit:cover;flex-shrink:0;">
                    <div>
                        <div style="font-weight:600;font-size:14px;">{{ $booking->trip->name }}</div>
                        <div style="font-size:12px;color:var(--text-3);margin-top:3px;">Rp {{ number_format($booking->trip->price,0,',','.') }} / orang</div>
                    </div>
                </div>
                @endif
                <table style="width:100%;font-size:13px;border-collapse:collapse;">
                    <tr><td style="padding:10px 0;color:var(--text-3);width:38%;">Tanggal Booking</td><td style="font-weight:500;">{{ $booking->booking_date ? $booking->booking_date->format('d F Y') : '-' }}</td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">Jumlah Peserta</td><td style="font-weight:500;">{{ $booking->participants_count }} orang</td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">Total Pembayaran</td>
                        <td style="font-size:18px;font-weight:700;color:var(--accent);">
                            {{ $booking->total_price > 0 ? 'Rp '.number_format($booking->total_price,0,',','.') : 'Gratis' }}
                        </td></tr>
                    <tr><td style="padding:10px 0;color:var(--text-3);">Dipesan Pada</td><td>{{ $booking->created_at->format('d M Y, H:i') }} WIB</td></tr>
                    @if($booking->confirmed_at)
                    <tr><td style="padding:10px 0;color:var(--text-3);">Dikonfirmasi Pada</td>
                        <td style="color:#059669;font-weight:500;">{{ $booking->confirmed_at->format('d M Y, H:i') }} WIB</td></tr>
                    @endif
                </table>
            </div>
        </div>

    </div>

    {{-- Kanan: Status & Aksi --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Status --}}
        <div class="dash-card">
            <div class="dash-card-header"><h3>📌 Status</h3></div>
            <div class="dash-card-body" style="text-align:center;padding:24px;">
                @php $badge = $booking->statusLabel; @endphp
                <span style="display:inline-block;padding:10px 28px;border-radius:30px;font-size:15px;font-weight:700;background:{{ $badge[1] }};color:{{ $badge[2] }};border:2px solid {{ $badge[3] }};margin-bottom:10px;">
                    {{ $badge[0] }}
                </span>
                <p style="font-size:12px;color:var(--text-3);margin:0;">
                    @if($booking->status === 'paid') Menunggu konfirmasi dari admin
                    @elseif($booking->status === 'confirmed') Pemesanan telah dikonfirmasi ✓
                    @elseif($booking->status === 'cancelled') Pemesanan ini telah dibatalkan
                    @else Menunggu pembayaran dari pemesan
                    @endif
                </p>
            </div>
        </div>

        {{-- ✅ TOMBOL KONFIRMASI UTAMA --}}
        @if($booking->status === 'paid')
        <div class="dash-card" style="border:2px solid #10b981;background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
            <div class="dash-card-body" style="padding:22px;text-align:center;">
                <div style="font-size:36px;margin-bottom:8px;">💳</div>
                <div style="font-weight:700;font-size:14px;color:#065f46;margin-bottom:6px;">Pembayaran Diterima!</div>
                <div style="font-size:12px;color:#047857;margin-bottom:20px;line-height:1.5;">
                    Pengguna telah menyelesaikan pembayaran sebesar<br>
                    <strong>Rp {{ number_format($booking->total_price,0,',','.') }}</strong>
                </div>
                <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" onsubmit="return konfirmasiSwal(event)">
                    @csrf @method('PATCH')
                    <button type="submit" style="width:100%;padding:13px;border-radius:10px;border:none;cursor:pointer;background:linear-gradient(135deg,#10b981,#059669);color:white;font-size:14px;font-weight:700;letter-spacing:.3px;box-shadow:0 4px 14px rgba(16,185,129,.35);">
                        ✅ Konfirmasi Sekarang
                    </button>
                </form>
            </div>
        </div>
        @elseif($booking->status === 'confirmed')
        <div class="dash-card" style="border:2px solid #86efac;">
            <div class="dash-card-body" style="padding:20px;text-align:center;">
                <div style="font-size:32px;margin-bottom:6px;">✅</div>
                <div style="font-weight:600;color:#15803d;">Sudah Dikonfirmasi</div>
                @if($booking->confirmed_at)
                <div style="font-size:11px;color:#6b7280;margin-top:6px;">{{ $booking->confirmed_at->format('d M Y, H:i') }} WIB</div>
                @endif
            </div>
        </div>
        @endif

        {{-- Ubah Status Manual --}}
        <div class="dash-card">
            <div class="dash-card-header"><h3>🔧 Ubah Status</h3></div>
            <div class="dash-card-body">
                <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking) }}">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:10px;">
                        <label style="font-size:12px;color:var(--text-3);display:block;margin-bottom:6px;">Pilih status baru:</label>
                        <select name="status" style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text-1);font-size:13px;">
                            <option value="pending"   {{ $booking->status=='pending'   ? 'selected':'' }}>⏳ Pending</option>
                            <option value="paid"      {{ $booking->status=='paid'      ? 'selected':'' }}>💳 Dibayar</option>
                            <option value="confirmed" {{ $booking->status=='confirmed' ? 'selected':'' }}>✅ Dikonfirmasi</option>
                            <option value="cancelled" {{ $booking->status=='cancelled' ? 'selected':'' }}>❌ Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text-1);font-size:13px;cursor:pointer;font-weight:500;">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Kembali --}}
        <a href="{{ route('admin.bookings.index') }}" style="display:block;text-align:center;padding:11px;border-radius:8px;border:1px solid var(--border);color:var(--text-2);text-decoration:none;font-size:13px;background:var(--surface);">
            ← Kembali ke Daftar Pemesanan
        </a>

    </div>
</div>
@endsection

@push('scripts')
<script>
function konfirmasiSwal(e) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        title: '✅ Konfirmasi Pemesanan?',
        html: `Pemesanan <strong>{{ $booking->booking_code }}</strong> akan ditandai sebagai <strong>Dikonfirmasi</strong>.`,
        icon: 'success',
        showCancelButton: true,
        confirmButtonText: '✅ Ya, Konfirmasi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
    }).then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
