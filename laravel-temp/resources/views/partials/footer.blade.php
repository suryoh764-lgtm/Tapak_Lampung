    <footer>
        <div class="footer-grid">
            <div>
                <a href="#" class="logo">
                    <div class="logo-mark">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 20L12 4L21 20" />
                            <circle cx="12" cy="14" r="2.5" />
                            <line x1="7" y1="17" x2="17" y2="17" />
                        </svg>
                    </div>
                    <span class="logo-text">Tapak Lampung</span>
                </a>
                <p class="footer-desc">Platform pariwisata terintegrasi untuk Provinsi Lampung. Menghubungkan wisatawan
                    dengan hidden gems, organizer, dan kuliner lokal.</p>
            </div>
            <div class="footer-col">
                <h5>Jelajahi</h5>
                <ul>
                    <li><a href="#">Hidden Gems</a></li>
                    <li><a href="#">Open Trip</a></li>
                    <li><a href="#">Kuliner Lokal</a></li>
                    <li><a href="#">Peta</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Platform</h5>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Daftar Organizer</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Privasi</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Kontak</h5>
                <ul>
                    <li><a href="#">hello@tapaklampung.id</a></li>
                    <li><a href="#">+62 857 698 161 73</a></li>
                    <li><a href="#">Bandar Lampung</a></li>
                </ul>
            </div>
        </div>

        {{-- Logo Kampus Section --}}
        <div class="footer-campus" style="border-top: 1px solid rgba(255,255,255,0.08); padding: 28px 0; margin-top: 8px; text-align: center;">
            <p style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.35); margin-bottom: 16px; font-weight: 600;">Didukung Oleh</p>
            <div style="display: flex; justify-content: center; align-items: center; gap: 24px; flex-wrap: wrap;">
                {{-- Ganti src di bawah dengan path logo kampus Anda, contoh: asset('images/logo-kampus.png') --}}
                <div class="campus-logo-wrapper" style="display: flex; align-items: center; gap: 16px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 14px 28px; backdrop-filter: blur(8px); transition: all .25s; cursor: default;" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.borderColor='rgba(255,255,255,0.22)';" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.borderColor='rgba(255,255,255,0.12)';">
                    <div style="width: 64px; height: 64px; border-radius: 12px; overflow: hidden; background: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 4px; box-shadow: 0 2px 12px rgba(0,0,0,0.25);">
                        <img src="{{ asset('images/logo-teknokrat.png') }}" alt="Logo Universitas Teknokrat Indonesia" style="width:100%;height:100%;object-fit:contain;">
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.92); line-height: 1.3; letter-spacing: 0.01em;">Universitas Teknokrat Indonesia</div>
                        <div style="font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 3px; letter-spacing: 0.03em;">Fakultas Teknik &amp; Ilmu Komputer</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span class="footer-copy">2026 Tapak Lampung</span>
            <div class="footer-stack">
                <span>Laravel</span>
                <span>MySQL</span>
                <span>Python</span>
                <span>NLP</span>
            </div>
        </div>
    </footer>
