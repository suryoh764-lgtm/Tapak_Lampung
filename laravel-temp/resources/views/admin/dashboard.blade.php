@extends('admin.layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan data Tapak Lampung')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        @foreach($stats as $stat)
        <div class="stat-card">
            <div class="stat-icon stat-icon--{{ $stat['color'] }}">
                <svg viewBox="0 0 24 24"><use href="#{{ $stat['icon'] }}"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $stat['value'] }}</span>
                <span class="stat-label">{{ $stat['label'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div style="display:grid; grid-template-columns: repeat(3,1fr); gap:20px; margin-bottom:28px;">

        <!-- Destinasi Card -->
        <div class="dash-card">
            <div class="dash-card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h3>🗺️ Destinasi</h3>
                <a href="{{ route('admin.destinations.create') }}" class="btn-add" style="padding:7px 14px;font-size:12px;">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah
                </a>
            </div>
            <div class="dash-card-body" style="padding:0;">
                @forelse($recentDestinations as $dest)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 24px;border-bottom:1px solid var(--border);">
                    @php $img = str_starts_with($dest->image_path??'','images/')||str_starts_with($dest->image_path??'','http') ? asset($dest->image_path) : asset('storage/'.$dest->image_path); @endphp
                    <img src="{{ $img }}" style="width:40px;height:36px;border-radius:6px;object-fit:cover;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $dest->name }}</div>
                        <div style="font-size:11px;color:var(--text-3);">{{ $dest->category }} · {{ $dest->location }}</div>
                    </div>
                    <a href="{{ route('admin.destinations.edit', $dest) }}" class="btn-edit" style="font-size:11px;padding:3px 8px;">Edit</a>
                </div>
                @empty
                <div style="padding:24px;text-align:center;color:var(--text-3);font-size:13px;">Belum ada destinasi</div>
                @endforelse
                <div style="padding:12px 24px;">
                    <a href="{{ route('admin.destinations.index') }}" style="font-size:13px;color:var(--accent);text-decoration:none;">Lihat semua →</a>
                </div>
            </div>
        </div>

        <!-- Open Trip Card -->
        <div class="dash-card">
            <div class="dash-card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h3>🏕️ Open Trip</h3>
                <a href="{{ route('admin.trips.create') }}" class="btn-add" style="padding:7px 14px;font-size:12px;">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah
                </a>
            </div>
            <div class="dash-card-body" style="padding:0;">
                @forelse($recentTrips as $trip)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 24px;border-bottom:1px solid var(--border);">
                    @php $img = str_starts_with($trip->image_path??'','images/')||str_starts_with($trip->image_path??'','http') ? asset($trip->image_path) : asset('storage/'.$trip->image_path); @endphp
                    <img src="{{ $img }}" style="width:40px;height:36px;border-radius:6px;object-fit:cover;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $trip->name }}</div>
                        <div style="font-size:11px;color:var(--text-3);">Rp {{ number_format($trip->price/1000,0) }}K · {{ $trip->current_quota }}/{{ $trip->max_quota }} peserta</div>
                    </div>
                    <a href="{{ route('admin.trips.edit', $trip) }}" class="btn-edit" style="font-size:11px;padding:3px 8px;">Edit</a>
                </div>
                @empty
                <div style="padding:24px;text-align:center;color:var(--text-3);font-size:13px;">Belum ada trip</div>
                @endforelse
                <div style="padding:12px 24px;">
                    <a href="{{ route('admin.trips.index') }}" style="font-size:13px;color:var(--accent);text-decoration:none;">Lihat semua →</a>
                </div>
            </div>
        </div>

        <!-- Kuliner Card -->
        <div class="dash-card">
            <div class="dash-card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h3>🍽️ Kuliner</h3>
                <a href="{{ route('admin.culinaries.create') }}" class="btn-add" style="padding:7px 14px;font-size:12px;">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah
                </a>
            </div>
            <div class="dash-card-body" style="padding:0;">
                @forelse($recentCulinaries as $food)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 24px;border-bottom:1px solid var(--border);">
                    @php $img = str_starts_with($food->image_path??'','images/')||str_starts_with($food->image_path??'','http') ? asset($food->image_path) : asset('storage/'.$food->image_path); @endphp
                    <img src="{{ $img }}" style="width:40px;height:36px;border-radius:6px;object-fit:cover;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $food->name }}</div>
                        <div style="font-size:11px;color:var(--text-3);">{{ $food->category }} · {{ $food->outlet_count }} {{ $food->outlet_type }}</div>
                    </div>
                    <a href="{{ route('admin.culinaries.edit', $food) }}" class="btn-edit" style="font-size:11px;padding:3px 8px;">Edit</a>
                </div>
                @empty
                <div style="padding:24px;text-align:center;color:var(--text-3);font-size:13px;">Belum ada kuliner</div>
                @endforelse
                <div style="padding:12px 24px;">
                    <a href="{{ route('admin.culinaries.index') }}" style="font-size:13px;color:var(--accent);text-decoration:none;">Lihat semua →</a>
                </div>
            </div>
        </div>

    </div>

    <!-- SVG Defs -->
    <svg style="display:none" xmlns="http://www.w3.org/2000/svg">
        <symbol id="s-compass" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></symbol>
        <symbol id="s-map" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></symbol>
        <symbol id="s-utensils" viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/></symbol>
        <symbol id="s-users" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    </svg>
@endsection
