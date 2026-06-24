@extends('admin.layouts.admin')
@section('title', 'Kuliner')
@section('page-title', 'Kuliner Khas')
@section('page-sub', 'Kelola data kuliner khas Lampung')

@section('content')
@if(session('success'))
    <div class="alert-flash success">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="data-table-wrap">
    <div class="data-table-header">
        <h3>Semua Kuliner <span class="badge badge-gray" style="margin-left:8px;">{{ $culinaries->total() }}</span></h3>
        <a href="{{ route('admin.culinaries.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Kuliner
        </a>
    </div>

    @if($culinaries->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
            <p>Belum ada kuliner. Silakan tambahkan!</p>
        </div>
    @else
    <table class="data-table">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Tingkat Pedas</th>
                <th>Outlet</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($culinaries as $food)
            <tr>
                <td>
                    @php
                        $imgUrl = str_starts_with($food->image_path ?? '', 'images/') || str_starts_with($food->image_path ?? '', 'http')
                            ? asset($food->image_path)
                            : asset('storage/' . $food->image_path);
                    @endphp
                    <img src="{{ $imgUrl }}" class="data-table-img" alt="{{ $food->name }}">
                </td>
                <td>
                    <div class="data-table-name">{{ $food->name }}</div>
                    <div class="data-table-sub">{{ Str::limit($food->description, 65) }}</div>
                </td>
                <td>
                    @if($food->category === 'Makanan')
                        <span class="badge badge-coral">{{ $food->category }}</span>
                    @elseif($food->category === 'Minuman')
                        <span class="badge badge-green">{{ $food->category }}</span>
                    @else
                        <span class="badge badge-gold">{{ $food->category }}</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:3px;">
                        @for($i = 1; $i <= 5; $i++)
                            <div style="width:10px;height:10px;border-radius:2px;background:{{ $i <= $food->spice_level ? 'var(--coral)' : 'var(--border)' }};"></div>
                        @endfor
                        <span style="font-size:12px;color:var(--text-3);margin-left:4px;">{{ $food->spice_level }}/5</span>
                    </div>
                </td>
                <td>
                    <div class="data-table-name">{{ $food->outlet_count }} {{ $food->outlet_type }}</div>
                </td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('admin.culinaries.edit', $food) }}" class="btn-edit">Edit</a>
                        <form method="POST" action="{{ route('admin.culinaries.destroy', $food) }}" onsubmit="return confirm('Hapus kuliner \'{{ $food->name }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($culinaries->hasPages())
    <div class="pagination-wrap">{{ $culinaries->links() }}</div>
    @endif
    @endif
</div>
@endsection
