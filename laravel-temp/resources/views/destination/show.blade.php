@extends('layouts.app')

@section('title', $destination->name . ' — Tapak Lampung')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    :root {
        --bg-hover: #f5f5f7;
    }
    [data-theme="dark"] {
        --bg-hover: var(--surface-hover, #1c2420);
    }

    /* Premium Detail Page Styling */
    .detail-hero {
        position: relative;
        height: 55vh;
        min-height: 400px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: flex-end;
        padding-bottom: 60px;
        color: #ffffff;
    }
    .detail-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.85) 100%);
    }
    .detail-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        width: 100%;
    }
    .detail-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }
    .detail-badge.coral { background: var(--coral, #c1553d); color: #fff; }
    .detail-badge.green { background: #2e7d32; color: #fff; }
    .detail-badge.accent { background: var(--accent, #f4b251); color: #1a1a1a; }
    
    .detail-title {
        font-family: 'Outfit', sans-serif;
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 12px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .detail-loc-subtitle {
        display: flex;
        align-items: center;
        font-size: 16px;
        color: rgba(255,255,255,0.9);
        gap: 8px;
    }
    .detail-loc-subtitle svg {
        width: 18px;
        height: 18px;
        stroke: var(--accent, #f4b251);
        fill: none;
        stroke-width: 2;
    }

    .detail-container {
        max-width: 1200px;
        margin: -50px auto 80px;
        padding: 0 24px;
        position: relative;
        z-index: 10;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }
    
    .detail-main {
        background: var(--surface, #ffffff);
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.04);
        border: 1px solid var(--border, #eaeaea);
    }
    
    .detail-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 600;
        color: var(--text, #1a1a1a);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid var(--bg-hover, #f5f5f7);
        padding-bottom: 12px;
    }
    .detail-section-title svg {
        width: 22px;
        height: 22px;
        stroke: var(--coral, #c1553d);
        fill: none;
        stroke-width: 2;
    }
    
    .detail-desc {
        color: var(--text-2, #4a4a4a);
        line-height: 1.8;
        font-size: 16px;
        margin-bottom: 30px;
    }
    
    .detail-sidebar {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    .info-card {
        background: var(--surface, #ffffff);
        border-radius: 24px;
        border: 1px solid var(--border, #eaeaea);
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.04);
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 0;
        border-bottom: 1px solid var(--border, #eaeaea);
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: var(--bg-hover, #f5f5f7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--coral, #c1553d);
        flex-shrink: 0;
    }
    .info-icon svg {
        width: 22px;
        height: 22px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
    }
    .info-content {
        flex: 1;
    }
    .info-label {
        font-size: 11px;
        color: var(--text-3, #999999);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 15px;
        color: var(--text, #1a1a1a);
        font-weight: 600;
    }
    
    .rating-likes-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-badge {
        background: var(--bg-hover, #f5f5f7);
        border-radius: 16px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
        border: 1px solid var(--border, #eaeaea);
    }
    .stat-badge-val {
        font-size: 22px;
        font-weight: 700;
        color: var(--text, #1a1a1a);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .stat-badge-val.star svg { fill: #f4b251; stroke: #f4b251; width: 22px; height: 22px; }
    .stat-badge-val.heart svg { fill: #c1553d; stroke: #c1553d; width: 22px; height: 22px; }
    .stat-badge-label {
        font-size: 12px;
        color: var(--text-3, #999999);
        font-weight: 500;
    }
    
    .back-btn-container {
        max-width: 1200px;
        margin: 40px auto 0;
        padding: 0 24px;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--surface, #ffffff);
        color: var(--text, #1a1a1a);
        border: 1px solid var(--border, #eaeaea);
        border-radius: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .back-btn:hover {
        background: var(--bg-hover, #f5f5f7);
        transform: translateX(-4px);
    }
    .back-btn svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
    }
    
    /* Recommendations */
    .recom-section {
        max-width: 1200px;
        margin: 0 auto 80px;
        padding: 0 24px;
    }
    .recom-title {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--text, #1a1a1a);
        margin-bottom: 24px;
    }
    .recom-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 30px;
    }
    
    /* Custom Map Widget styling */
    .map-widget {
        background: var(--bg-hover, #f5f5f7);
        border-radius: 20px;
        height: 380px;
        margin-top: 30px;
        border: 1px solid var(--border, #eaeaea);
        overflow: hidden;
        position: relative;
    }
    
    .map-widget-overlay {
        position: absolute;
        bottom: 16px;
        left: 16px;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(10px);
        padding: 10px 16px;
        border-radius: 12px;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .map-widget-overlay svg {
        width: 16px;
        height: 16px;
        stroke: var(--accent, #f4b251);
        fill: none;
        stroke-width: 2;
    }

    /* Premium Leaflet Style Overrides */
    .leaflet-popup-content-wrapper {
        background: var(--surface, #ffffff) !important;
        color: var(--text, #1a1a1a) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        border: 1px solid var(--border, #eaeaea) !important;
        padding: 4px !important;
    }
    .leaflet-popup-tip {
        background: var(--surface, #ffffff) !important;
        border: 1px solid var(--border, #eaeaea) !important;
        box-shadow: none !important;
    }
    .leaflet-container a.leaflet-popup-close-button {
        color: var(--text-3, #999) !important;
        padding: 8px 10px 0 0 !important;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    @media (max-width: 992px) {
        .detail-container {
            grid-template-columns: 1fr;
            margin-top: -30px;
        }
        .detail-hero {
            height: 40vh;
        }
        .detail-title {
            font-size: 32px;
        }
        .detail-main {
            padding: 30px 20px;
        }
    }
</style>
@endpush

@section('content')
    <div class="back-btn-container">
        <a href="{{ route('home') }}#gems" class="back-btn">
            <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Beranda
        </a>
    </div>

    <section class="detail-hero" style="background-image: url('{{ str_starts_with($destination->image_path, 'http') || str_starts_with($destination->image_path, 'images/') ? asset($destination->image_path) : asset('storage/' . $destination->image_path) }}');">
        <div class="detail-hero-content">
            <span class="detail-badge {{ $destination->label === 'Surfing' ? 'coral' : ($destination->label === 'Populer' ? 'accent' : 'green') }}">
                {{ $destination->label ?? 'Hidden Gem' }}
            </span>
            <h1 class="detail-title">{{ $destination->name }}</h1>
            <div class="detail-loc-subtitle">
                <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $destination->location }}
            </div>
        </div>
    </section>

    <div class="detail-container">
        <!-- Main Column -->
        <main class="detail-main">
            <h2 class="detail-section-title">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Tentang Destinasi
            </h2>
            <div class="detail-desc">
                {{ $destination->description }}
            </div>
            
            <h2 class="detail-section-title" style="margin-top: 40px;">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="12 8 8 12 12 16 16 12 12 8"/></svg>
                Peta Lokasi Interaktif
            </h2>
            <div class="map-widget">
                <div id="map-interactive" style="width: 100%; height: 100%; z-index: 1;"></div>
                <div class="map-widget-overlay" style="z-index: 1000; pointer-events: none;">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="12" y1="2" x2="12" y2="22"/></svg>
                    <span id="coordinates-indicator">Mendapatkan Koordinat GPS...</span>
                </div>
            </div>
        </main>

        <!-- Sidebar Column -->
        <aside class="detail-sidebar">
            <!-- Ratings and Likes Box -->
            <div class="rating-likes-box">
                <div class="stat-badge">
                    <div class="stat-badge-val star">
                        <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        {{ number_format($destination->rating, 1) }}
                    </div>
                    <span class="stat-badge-label">Rating Pengunjung</span>
                </div>
                <div class="stat-badge">
                    <div class="stat-badge-val heart">
                        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        {{ $destination->likes_count }}
                    </div>
                    <span class="stat-badge-label">Disukai Wisatawan</span>
                </div>
            </div>

            <!-- Detailed Info Card -->
            <div class="info-card">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--text, #1a1a1a); margin-bottom: 20px; border-bottom: 1px solid var(--border, #eaeaea); padding-bottom: 12px;">
                    Informasi Perjalanan
                </h3>
                
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Jarak Dari Kota</div>
                        <div class="info-value">{{ $destination->distance_km ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Waktu Tempuh</div>
                        <div class="info-value">{{ $destination->travel_time ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Waktu Terbaik</div>
                        <div class="info-value">{{ $destination->best_time ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"/><line x1="12" y1="4" x2="12" y2="20"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Tiket Masuk</div>
                        <div class="info-value">{{ $destination->entrance_fee ?? 'Gratis' }}</div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Recommendations Section -->
    <section class="recom-section">
        <h2 class="recom-title">Rekomendasi Permata Lainnya</h2>
        <div class="recom-grid">
            @foreach($related as $rel)
            <a href="{{ route('destinations.show', $rel->id) }}" class="gem reveal" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                <div class="gem-img"
                    style="background-image: url('{{ str_starts_with($rel->image_path, 'http') || str_starts_with($rel->image_path, 'images/') ? asset($rel->image_path) : asset('storage/' . $rel->image_path) }}'); background-size: cover; background-position: center; height: 200px; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <span class="gem-label {{ $rel->label === 'Surfing' ? 'red' : ($rel->label === 'Populer' ? 'accent' : 'green') }}">
                        {{ $rel->label ?? 'Hidden Gem' }}
                    </span>
                </div>
                <div class="gem-body" style="background: var(--surface, #ffffff); border: 1px solid var(--border, #eaeaea); border-top: none; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; padding: 20px;">
                    <div class="gem-loc" style="font-size: 12px; color: var(--coral, #c1553d); font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">{{ $rel->location }}</div>
                    <div class="gem-name" style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--text, #1a1a1a); margin-bottom: 8px;">{{ $rel->name }}</div>
                    <div class="gem-footer" style="display: flex; gap: 16px; border-top: 1px solid var(--border, #eaeaea); padding-top: 12px; margin-top: 12px;">
                        <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 13px; color: var(--text-2, #4a4a4a); font-weight: 500;">
                            ⭐ {{ number_format($rel->rating, 1) }}
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 13px; color: var(--text-2, #4a4a4a); font-weight: 500;">
                            📍 {{ $rel->category }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const coordinatesMap = {
            1: [-5.6728, 105.2183], // Pulau Pahawang Kecil
            2: [-5.7493, 105.1927], // Teluk Kiluan
            3: [-5.2500, 103.9200], // Pantai Mandiri Krui
            4: [-5.4852, 104.6903], // Air Terjun Way Lalaan
            5: [-4.8625, 103.9306], // Danau Ranau
            6: [-5.7500, 105.1900]  // Pulau Kelapa
        };

        const destId = {{ $destination->id }};
        const destName = "{{ addslashes($destination->name) }}";
        const destLoc = "{{ addslashes($destination->location) }}";

        let map = null;
        let currentTileLayer = null;
        let marker = null;

        function getTileUrl(theme) {
            if (theme === 'dark') {
                return 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
            }
            return 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
        }

        function updateMapTheme(theme) {
            if (!map) return;
            if (currentTileLayer) {
                map.removeLayer(currentTileLayer);
            }
            const tileUrl = getTileUrl(theme);
            currentTileLayer = L.tileLayer(tileUrl, {
                maxZoom: 20,
                attribution: '&copy; OpenStreetMap'
            });
            currentTileLayer.addTo(map);
        }

        function initializeMap(lat, lon, zoomLevel) {
            if (map) return;
            
            // Format coordinates indicator text
            const indicator = document.getElementById('coordinates-indicator');
            if (indicator) {
                indicator.textContent = `GPS: ${lat.toFixed(6)}, ${lon.toFixed(6)}`;
            }

            map = L.map('map-interactive', {
                center: [lat, lon],
                zoom: zoomLevel,
                zoomControl: false,
                attributionControl: false
            });

            // Set zoom control top right
            L.control.zoom({ position: 'topright' }).addTo(map);

            // Apply theme
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateMapTheme(currentTheme);

            // Custom pulsing marker icon
            const customIcon = L.divIcon({
                className: 'custom-map-marker',
                html: `
                    <div style="
                        background: var(--coral, #c1553d);
                        width: 16px;
                        height: 16px;
                        border-radius: 50%;
                        border: 2.5px solid white;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                        position: relative;
                    ">
                        <div style="
                            position: absolute;
                            top: -8px;
                            left: -8px;
                            width: 28px;
                            height: 28px;
                            border-radius: 50%;
                            background: rgba(193, 85, 61, 0.4);
                            animation: pulse 1.8s infinite ease-in-out;
                            z-index: -1;
                        "></div>
                    </div>
                `,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });

            marker = L.marker([lat, lon], { icon: customIcon }).addTo(map);

            // Premium popup styling content
            const popupContent = `
                <div style="font-family: 'Outfit', sans-serif; padding: 6px 10px; min-width: 140px;">
                    <h4 style="margin: 0 0 4px; font-weight: 700; font-size: 14px; color: var(--text, #1a1a1a);">${destName}</h4>
                    <p style="margin: 0; font-size: 11px; color: var(--text-3, #999); line-height: 1.3;">${destLoc}</p>
                </div>
            `;
            marker.bindPopup(popupContent).openPopup();
        }

        // Logic coordinate resolver
        if (coordinatesMap[destId]) {
            const coords = coordinatesMap[destId];
            initializeMap(coords[0], coords[1], 13);
        } else {
            // Fallback: search with Nominatim OpenStreetMap geocoding API
            const fallbackLat = -5.4500;
            const fallbackLon = 105.2600;
            const fallbackZoom = 10;
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(destName + ', ' + destLoc)}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        initializeMap(lat, lon, 13);
                    } else {
                        // Attempt fallback to just location search
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(destLoc)}`)
                            .then(res2 => res2.json())
                            .then(data2 => {
                                if (data2 && data2.length > 0) {
                                    const lat = parseFloat(data2[0].lat);
                                    const lon = parseFloat(data2[0].lon);
                                    initializeMap(lat, lon, 11);
                                } else {
                                    initializeMap(fallbackLat, fallbackLon, fallbackZoom);
                                }
                            })
                            .catch(() => initializeMap(fallbackLat, fallbackLon, fallbackZoom));
                    }
                })
                .catch(() => {
                    initializeMap(fallbackLat, fallbackLon, fallbackZoom);
                });
        }

        // Live theme switching MutationObserver
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-theme') {
                    const newTheme = document.documentElement.getAttribute('data-theme') || 'light';
                    updateMapTheme(newTheme);
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true });
    });
</script>
@endpush

@endsection
