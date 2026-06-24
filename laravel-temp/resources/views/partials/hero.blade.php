<div class="hero-wrapper" style="position: relative; width: 100%; overflow: hidden; background: var(--bg); min-height: 85vh; display: flex; align-items: center; justify-content: center; transition: background var(--tr);">
    <!-- Background Image with Blur -->
    <div class="hero-bg-blur" style="position: absolute; inset: -40px; background-image: url('{{ asset('images/wisata/pulau_pahawang.jpg') }}'); background-size: cover; background-position: center; filter: blur(30px) saturate(1.2); opacity: 0.35; z-index: 1; transition: opacity var(--tr);"></div>
    <!-- Overlay to blend with the theme background color -->
    <div class="hero-overlay" style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 0%, var(--bg) 100%), linear-gradient(to right, var(--bg) 0%, transparent 40%, transparent 60%, var(--bg) 100%); z-index: 2; pointer-events: none; transition: background var(--tr);"></div>
    
    <!-- Hero Content -->
    <section class="hero" style="position: relative; z-index: 3; margin: 0 auto; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div class="hero-tag" style="margin-left: auto; margin-right: auto; margin-bottom: 1rem;">Platform Pariwisata Lampung</div>
        <h1 style="margin-left: auto; margin-right: auto;">Jelajahi keindahan<br><strong>tersembunyi</strong> Lampung</h1>
        <p style="margin-left: auto; margin-right: auto; max-width: 650px;">Temukan hidden gems, pesan open trip, dan nikmati kuliner khas &mdash; semua dalam satu platform. Dari Pahawang
            hingga Krui.</p>
        <div class="hero-actions" style="justify-content: center; display: flex; width: 100%;">
            <a href="#gems" class="btn btn-dark">
                Jelajahi
                <svg>
                    <use href="#s-arrow" />
                </svg>
            </a>
            <a href="#trips" class="btn btn-outline">Lihat Open Trip</a>
        </div>
    </section>
</div>
