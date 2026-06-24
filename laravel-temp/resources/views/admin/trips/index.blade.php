@extends('admin.layouts.admin')
@section('title', 'Open Trip')
@section('page-title', 'Open Trip')
@section('page-sub', 'Kelola data paket open trip')

@section('content')
@if(session('success'))
    <div class="alert-flash success">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="data-table-wrap">
    <div class="data-table-header">
        <h3>Semua Open Trip <span class="badge badge-gray" style="margin-left:8px;">{{ $trips->total() }}</span></h3>
        <a href="{{ route('admin.trips.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Trip
        </a>
    </div>

    @if($trips->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
            <p>Belum ada open trip. Silakan tambahkan!</p>
        </div>
    @else
    <table class="data-table">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama Trip</th>
                <th>Organizer</th>
                <th>Jadwal</th>
                <th>Kuota</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trips as $trip)
            <tr>
                <td>
                    @php
                        $imgUrl = str_starts_with($trip->image_path ?? '', 'images/') || str_starts_with($trip->image_path ?? '', 'http')
                            ? asset($trip->image_path)
                            : asset('storage/' . $trip->image_path);
                    @endphp
                    <img src="{{ $imgUrl }}" class="data-table-img" alt="{{ $trip->name }}">
                </td>
                <td>
                    <div class="data-table-name">{{ $trip->name }}</div>
                    <div class="data-table-sub">
                        @foreach($trip->tags as $tag)
                            <span class="badge badge-gray" style="margin-right:3px; margin-bottom:2px;">{{ $tag->tag }}</span>
                        @endforeach
                    </div>
                </td>
                <td>
                    <div class="data-table-name">{{ $trip->organizer_name }}</div>
                    <div class="data-table-sub">{{ $trip->duration }}</div>
                </td>
                <td><div class="data-table-sub">{{ \Carbon\Carbon::parse($trip->schedule_date)->format('d M Y') }}</div></td>
                <td>
                    @php $pct = $trip->max_quota > 0 ? ($trip->current_quota / $trip->max_quota) * 100 : 0; @endphp
                    <div class="data-table-name">{{ $trip->current_quota }}/{{ $trip->max_quota }}</div>
                    <div style="height:4px;background:var(--border);border-radius:4px;margin-top:4px;width:80px;">
                        <div style="height:4px;background:var(--accent);border-radius:4px;width:{{ $pct }}%;"></div>
                    </div>
                </td>
                <td><span class="badge badge-green">Rp {{ number_format($trip->price/1000, 0) }}K</span></td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('admin.trips.edit', $trip) }}" class="btn-edit">Edit</a>
                        <form method="POST" action="{{ route('admin.trips.destroy', $trip) }}" onsubmit="return confirm('Hapus trip \'{{ $trip->name }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($trips->hasPages())
    <div class="pagination-wrap">{{ $trips->links() }}</div>
    @endif
    @endif
</div>
@endsection
