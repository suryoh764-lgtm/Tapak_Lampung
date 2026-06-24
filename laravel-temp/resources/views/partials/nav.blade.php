    @php
        $isHome = request()->routeIs('home');
        $base   = route('home');
    @endphp
    <nav id="nav">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24">
                    <path d="M3 20L12 4L21 20" />
                    <circle cx="12" cy="14" r="2.5" />
                    <line x1="7" y1="17" x2="17" y2="17" />
                </svg>
            </div>
            <span class="logo-text">Tapak Lampung</span>
        </a>
        <div class="nav-right">
            <ul class="nav-links">
                <li><a href="{{ $isHome ? '#gems' : $base . '#gems' }}">Hidden Gems</a></li>
                <li><a href="{{ $isHome ? '#trips' : $base . '#trips' }}">Open Trip</a></li>
                <li><a href="{{ $isHome ? '#kuliner' : $base . '#kuliner' }}">Kuliner</a></li>
                <li><a href="{{ $isHome ? '#map' : $base . '#map' }}">Peta</a></li>
                @auth
                    @if(auth()->user()->isWebAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-btn">Dashboard Web</a></li>
                    @elseif(auth()->user()->isTripAdmin())
                        <li><a href="#" class="nav-btn">Dashboard Trip</a></li>
                    @else
                        <li><a href="#" class="nav-btn">Profil Saya</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="nav-btn">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="nav-btn">Masuk / Daftar</a></li>
                @endauth
            </ul>
            <div class="theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Ganti tema">
                <div class="theme-toggle-knob">
                    <svg class="icon-sun" viewBox="0 0 24 24">
                        <use href="#i-sun" />
                    </svg>
                    <svg class="icon-moon" viewBox="0 0 24 24">
                        <use href="#i-moon" />
                    </svg>
                </div>
            </div>
            <button class="nav-mobile" onclick="mob()"><svg>
                    <use href="#s-menu" />
                </svg></button>
        </div>
    </nav>
