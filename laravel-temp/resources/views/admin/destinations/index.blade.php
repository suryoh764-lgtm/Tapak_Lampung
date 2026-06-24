@extends('admin.layouts.admin')
@section('title', 'Destinasi')
@section('page-title', 'Destinasi (Hidden Gems)')
@section('page-sub', 'Kelola data destinasi wisata')

@section('content')
@if(session('success'))
    <div class="alert-flash success">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="data-table-wrap">
    <div class="data-table-header">
        <h3>Semua Destinasi <span class="badge badge-gray" style="margin-left:8px;">{{ $destinations->total() }}</span></h3>
        <a href="{{ route('admin.destinations.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Destinasi
        </a>
    </div>

    @if($destinations->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>Belum ada destinasi. Silakan tambahkan yang pertama!</p>
        </div>
    @else
    <table class="data-table">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Label</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($destinations as $dest)
            <tr>
                <td>
                    @php
                        $imgUrl = str_starts_with($dest->image_path ?? '', 'images/') || str_starts_with($dest->image_path ?? '', 'http')
                            ? asset($dest->image_path)
                            : asset('storage/' . $dest->image_path);
                    @endphp
                    <img src="{{ $imgUrl }}" class="data-table-img" alt="{{ $dest->name }}">
                </td>
                <td>
                    <div class="data-table-name">{{ $dest->name }}</div>
                    <div class="data-table-sub">{{ Str::limit($dest->description, 60) }}</div>
                </td>
                <td><div class="data-table-sub">{{ $dest->location }}</div></td>
                <td>
                    <span class="badge badge-gray">{{ $dest->category }}</span>
                </td>
                <td>
                    @if($dest->label === 'Populer')
                        <span class="badge badge-coral">{{ $dest->label }}</span>
                    @elseif($dest->label === 'Surfing')
                        <span class="badge badge-gold">{{ $dest->label }}</span>
                    @else
                        <span class="badge badge-green">{{ $dest->label }}</span>
                    @endif
                </td>
                <td>⭐ {{ number_format($dest->rating, 1) }}</td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('admin.destinations.edit', $dest) }}" class="btn-edit">Edit</a>
                        <form method="POST" action="{{ route('admin.destinations.destroy', $dest) }}" onsubmit="return confirm('Hapus destinasi \'{{ $dest->name }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($destinations->hasPages())
    <div class="pagination-wrap">
        {{ $destinations->links() }}
    </div>
    @endif
    @endif
</div>
@endsection
