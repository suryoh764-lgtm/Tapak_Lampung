@extends('admin.layouts.admin')
@section('title', 'Manajemen Pemesanan')
@section('page-title', 'Manajemen Pemesanan')
@section('page-sub', 'Kelola dan konfirmasi pemesanan dari pengguna')

@section('content')

{{-- Alerts --}}
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

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;">
    @foreach([
        ['total','Total','🗒️','#3b82f6'],
        ['pending','Pending','⏳','#f59e0b'],
        ['paid','Dibayar','💳','#10b981'],
        ['confirmed','Dikonfirmasi','✅','#6366f1'],
        ['cancelled','Dibatalkan','❌','#ef4444'],
    ] as [$key,$label,$icon,$color])
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $color }}20;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">{{ $icon }}</div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--text-1);">{{ $stats[$key] }}</div>
            <div style="font-size:11px;color:var(--text-3);margin-top:2px;">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter & Search --}}
<div class="dash-card" style="margin-bottom:20px;">
    <div class="dash-card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('admin.bookings.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍  Cari nama, email, kode..." style="padding:9px 14px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text-1);font-size:13px;min-width:220px;">
            <select name="status" style="padding:9px 14px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text-1);font-size:13px;">
                <option value="">Semua Status</option>
                <option value="pending"   {{ request('status')=='pending'   ? 'selected':'' }}>⏳ Pending</option>
                <option value="paid"      {{ request('status')=='paid'      ? 'selected':'' }}>💳 Dibayar</option>
                <option value="confirmed" {{ request('status')=='confirmed' ? 'selected':'' }}>✅ Dikonfirmasi</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>❌ Dibatalkan</option>
            </select>
            <select name="type" style="padding:9px 14px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text-1);font-size:13px;">
                <option value="">Semua Tipe</option>
                <option value="trip"    {{ request('type')=='trip'    ? 'selected':'' }}>🏕️ Open Trip</option>
                <option value="kuliner" {{ request('type')=='kuliner' ? 'selected':'' }}>🍽️ Kuliner</option>
            </select>
            <button type="submit" style="padding:9px 18px;border-radius:8px;border:none;background:var(--accent);color:white;font-size:13px;cursor:pointer;font-weight:500;">Terapkan</button>
            @if(request()->anyFilled(['search','status','type']))
            <a href="{{ route('admin.bookings.index') }}" style="font-size:13px;color:var(--text-3);text-decoration:none;">✕ Reset</a>
            @endif
            <span style="margin-left:auto;font-size:12px;color:var(--text-3);">{{ $bookings->total() }} pemesanan</span>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="dash-card">
    <div class="dash-card-header">
        <h3>📋 Daftar Pemesanan</h3>
    </div>
    <div class="dash-card-body" style="padding:0;overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:var(--bg);border-bottom:2px solid var(--border);">
                    <th style="padding:12px 16px;text-align:left;color:var(--text-3);font-weight:600;white-space:nowrap;">Kode</th>
                    <th style="padding:12px 16px;text-align:left;color:var(--text-3);font-weight:600;">Pemesan</th>
                    <th style="padding:12px 16px;text-align:left;color:var(--text-3);font-weight:600;">Item</th>
                    <th style="padding:12px 16px;text-align:left;color:var(--text-3);font-weight:600;white-space:nowrap;">Tgl Pesan</th>
                    <th style="padding:12px 16px;text-align:right;color:var(--text-3);font-weight:600;white-space:nowrap;">Total</th>
                    <th style="padding:12px 16px;text-align:center;color:var(--text-3);font-weight:600;">Status</th>
                    <th style="padding:12px 16px;text-align:center;color:var(--text-3);font-weight:600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr style="border-bottom:1px solid var(--border);" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                    <td style="padding:14px 16px;">
                        <span style="font-family:monospace;font-size:11px;background:var(--bg);padding:3px 8px;border-radius:5px;border:1px solid var(--border);">{{ $booking->booking_code }}</span>
                        <div style="font-size:10px;color:var(--text-3);margin-top:4px;">{{ $booking->type === 'trip' ? '🏕️ Trip' : '🍽️ Kuliner' }}</div>
                    </td>
                    <td style="padding:14px 16px;">
                        <div style="font-weight:500;">{{ $booking->name }}</div>
                        <div style="font-size:11px;color:var(--text-3);">{{ $booking->email }}</div>
                        <div style="font-size:11px;color:var(--text-3);">{{ $booking->phone }}</div>
                    </td>
                    <td style="padding:14px 16px;">
                        <div style="font-weight:500;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $booking->trip->name ?? ($booking->type === 'kuliner' ? 'Reservasi Kuliner' : 'Trip dihapus') }}
                        </div>
                        <div style="font-size:11px;color:var(--text-3);">{{ $booking->participants_count }} peserta · {{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }}</div>
                    </td>
                    <td style="padding:14px 16px;color:var(--text-2);white-space:nowrap;">{{ $booking->created_at->format('d M Y') }}<br><span style="font-size:11px;color:var(--text-3);">{{ $booking->created_at->format('H:i') }}</span></td>
                    <td style="padding:14px 16px;text-align:right;font-weight:700;">
                        {{ $booking->total_price > 0 ? 'Rp '.number_format($booking->total_price,0,',','.') : 'Gratis' }}
                    </td>
                    <td style="padding:14px 16px;text-align:center;">
                        @php $badge = $booking->statusLabel; @endphp
                        <span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;background:{{ $badge[1] }};color:{{ $badge[2] }};border:1px solid {{ $badge[3] }};white-space:nowrap;">
                            {{ $badge[0] }}
                        </span>
                    </td>
                    <td style="padding:14px 16px;">
                        <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('admin.bookings.show', $booking) }}" style="padding:5px 10px;border-radius:6px;border:1px solid var(--border);font-size:11px;text-decoration:none;color:var(--text-2);background:var(--surface);white-space:nowrap;">🔍 Detail</a>

                            @if($booking->status === 'paid')
                            <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" onsubmit="return swalConfirm(event,'Konfirmasi Pemesanan?','Pemesanan {{ $booking->booking_code }} akan dikonfirmasi.','✅ Konfirmasi','#10b981')">
                                @csrf @method('PATCH')
                                <button type="submit" style="padding:5px 12px;border-radius:6px;border:none;font-size:11px;cursor:pointer;background:#10b981;color:white;font-weight:600;white-space:nowrap;">✅ Konfirmasi</button>
                            </form>
                            @endif

                            @if(!in_array($booking->status, ['cancelled','confirmed']))
                            <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking) }}" onsubmit="return swalConfirm(event,'Batalkan Pemesanan?','Pemesanan akan dibatalkan dan tidak dapat dipulihkan.','Batalkan','#ef4444')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" style="padding:5px 10px;border-radius:6px;border:none;font-size:11px;cursor:pointer;background:#fee2e2;color:#dc2626;font-weight:600;white-space:nowrap;">❌ Batalkan</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:60px;text-align:center;color:var(--text-3);">
                        <div style="font-size:48px;margin-bottom:10px;">📭</div>
                        <div style="font-weight:500;">Belum ada data pemesanan</div>
                        <div style="font-size:12px;margin-top:4px;">Pemesanan akan muncul di sini setelah pengguna melakukan booking</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $bookings->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function swalConfirm(e, title, text, confirmText, color) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        title, text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        confirmButtonColor: color,
        cancelButtonColor: '#6b7280',
    }).then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}
</script>
@endpush
