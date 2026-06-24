    <section class="section map-bg" id="map">
        <div class="section-head reveal">
            <div class="section-tag">Peta</div>
            <h2 class="section-title">Eksplorasi <b>Lampung</b> di peta</h2>
            <p class="section-sub">Temukan destinasi, open trip, dan kuliner terdekat. Integrasi Google Maps untuk navigasi
                real-time. Klik lokasi di bawah ini untuk melihat detail tempat.</p>
        </div>

        <div class="map-places-nav reveal" style="display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none;">
            <button class="filter-chip active" onclick="changeMap('Lampung, Indonesia', 8, this)">Provinsi Lampung</button>
            <button class="filter-chip" onclick="changeMap('Teluk Kiluan, Tanggamus, Lampung', 14, this)">Teluk Kiluan</button>
            <button class="filter-chip" onclick="changeMap('Pulau Pahawang, Pesawaran, Lampung', 14, this)">Pulau Pahawang</button>
            <button class="filter-chip" onclick="changeMap('Pantai Mandiri Krui, Pesisir Barat', 14, this)">Pantai Mandiri</button>
            <button class="filter-chip" onclick="changeMap('Air Terjun Way Lalaan, Tanggamus', 15, this)">Way Lalaan</button>
            <button class="filter-chip" onclick="changeMap('Danau Ranau, Lampung', 12, this)">Danau Ranau</button>
            <button class="filter-chip" onclick="changeMap('Pulau Kelapa, Kiluan, Lampung', 15, this)">Pulau Kelapa</button>
        </div>

        <div class="map-card reveal">
            <div class="map-inner">
                <iframe id="gmap-iframe" src="https://maps.google.com/maps?q=Lampung,%20Indonesia&t=&z=8&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <style>
        .map-places-nav::-webkit-scrollbar {
            display: none;
        }
        .map-places-nav .filter-chip {
            white-space: nowrap;
        }
    </style>

    <script>
        function changeMap(query, zoom, btn) {
            const iframe = document.getElementById('gmap-iframe');
            iframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(query)}&t=&z=${zoom}&ie=UTF8&iwloc=&output=embed`;
            
            const buttons = document.querySelectorAll('.map-places-nav .filter-chip');
            buttons.forEach(b => b.classList.remove('active'));
            if(btn) btn.classList.add('active');
        }
    </script>
