@extends('layouts.app')

@section('title', 'Hasil Pencarian - Tapak Lampung')

@section('content')
<div style="padding: 120px 48px; max-width: 1200px; margin: 0 auto; min-height: 80vh;">
    <a href="{{ route('home') }}" class="btn btn-outline" style="margin-bottom: 30px;">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg> Kembali ke Beranda
    </a>

    <h1 style="font-family: 'Outfit', sans-serif; font-size: 36px; margin-bottom: 10px;">
        Hasil Pencarian: @if($query) "<b>{{ $query }}</b>" @else Kategori @endif
    </h1>
    <p style="color: var(--text-2); margin-bottom: 40px; font-size: 16px;">
        Menemukan {{ $destinations->count() }} destinasi, {{ $trips->count() }} open trip, dan {{ $culinaries->count() }} kuliner.
    </p>

    {{-- Kategori Filter Ulang --}}
    <form action="{{ route('search') }}" method="GET" style="display: flex; gap: 10px; margin-bottom: 40px; flex-wrap: wrap;">
        <input type="hidden" name="q" value="{{ $query }}">
        
        <button type="submit" name="category" value="Semua" class="filter-chip {{ ($category ?? 'Semua') === 'Semua' ? 'active' : '' }}">Semua</button>
        <button type="submit" name="category" value="Pantai" class="filter-chip {{ $category === 'Pantai' ? 'active' : '' }}">Pantai & Laut</button>
        <button type="submit" name="category" value="Pulau" class="filter-chip {{ $category === 'Pulau' ? 'active' : '' }}">Pulau</button>
        <button type="submit" name="category" value="Air Terjun" class="filter-chip {{ $category === 'Air Terjun' ? 'active' : '' }}">Air Terjun</button>
        <button type="submit" name="category" value="Danau" class="filter-chip {{ $category === 'Danau' ? 'active' : '' }}">Danau</button>
        <button type="submit" name="category" value="Gunung" class="filter-chip {{ $category === 'Gunung' ? 'active' : '' }}">Gunung</button>
    </form>

    @if($destinations->isNotEmpty())
        <h2 style="font-family: 'Outfit'; font-size: 24px; margin-bottom: 20px;">Destinasi Wisata</h2>
        <div class="gems" style="margin-bottom: 50px;">
            @foreach($destinations as $dest)
                <a href="{{ route('destinations.show', $dest->id) }}" class="gem" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                    <div class="gem-img" style="background-image: url('{{ str_starts_with($dest->image_path, 'http') || str_starts_with($dest->image_path, 'images/') ? asset($dest->image_path) : asset('storage/' . $dest->image_path) }}'); background-size: cover; background-position: center;">
                        <span class="gem-label green">{{ $dest->label ?? 'Hidden Gem' }}</span>
                    </div>
                    <div class="gem-body">
                        <div class="gem-loc">{{ $dest->location }}</div>
                        <div class="gem-name">{{ $dest->name }}</div>
                        <div class="gem-footer" style="margin-top: auto;">
                            <span class="gem-stat"><svg><use href="#s-pin" /></svg> {{ $dest->category }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    @if($trips->isNotEmpty())
        <h2 style="font-family: 'Outfit'; font-size: 24px; margin-bottom: 20px;">Open Trip</h2>
        <div class="trips" style="margin-bottom: 50px;">
            @foreach($trips as $trip)
                <a href="{{ route('trips.book', $trip->id) }}" class="trip" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                    <div class="trip-img" style="background-image: url('{{ str_starts_with($trip->image_path ?? '', 'http') || str_starts_with($trip->image_path ?? '', 'images/') ? asset($trip->image_path) : asset('storage/' . $trip->image_path) }}'); background-size: cover; background-position: center;">
                        <div class="trip-org">
                            <div class="trip-org-avatar">{{ substr($trip->organizer_name, 0, 1) }}</div>
                            <span class="trip-org-name">{{ $trip->organizer_name }}</span>
                        </div>
                    </div>
                    <div class="trip-body">
                        <div class="trip-name">{{ $trip->name }}</div>
                        <div class="trip-price">Rp {{ number_format($trip->price, 0, ',', '.') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    @if($culinaries->isNotEmpty())
        <h2 style="font-family: 'Outfit'; font-size: 24px; margin-bottom: 20px;">Kuliner Khas</h2>
        <div class="foods" style="margin-bottom: 50px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @foreach($culinaries as $food)
                <a href="{{ route('culinary.show', $food->id) }}" class="food" style="text-decoration: none; color: inherit; display: block;">
                    <div class="food-img" style="background-image: url('{{ str_starts_with($food->image_path ?? '', 'http') || str_starts_with($food->image_path ?? '', 'images/') ? asset($food->image_path) : asset('storage/' . $food->image_path) }}'); background-size: cover; background-position: center; height: 180px;">
                        <span class="food-cat">{{ $food->category }}</span>
                    </div>
                    <div class="food-body" style="padding: 20px; border: 1px solid var(--border); border-top: none; border-radius: 0 0 16px 16px;">
                        <div class="food-name" style="font-family: 'Outfit'; font-size: 18px; font-weight: 600;">{{ $food->name }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    @if($destinations->isEmpty() && $trips->isEmpty() && $culinaries->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <h3 style="font-family: 'Outfit'; font-size: 20px; margin-bottom: 10px;">Pencarian Tidak Ditemukan</h3>
            <p style="color: var(--text-2);">Coba gunakan kata kunci atau kategori yang lain.</p>
        </div>
    @endif
</div>
@endsection
