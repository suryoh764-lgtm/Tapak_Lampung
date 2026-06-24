    <div class="mob-nav" id="mobNav">
        <button class="mob-close" onclick="mob()"><svg>
                <use href="#s-x" />
            </svg></button>
        
        @php
            $isHome = request()->routeIs('home');
            $base   = route('home');
        @endphp
        
        <a href="{{ $isHome ? '#gems' : $base . '#gems' }}" onclick="mob()" class="{{ request()->routeIs('destinations.*') ? 'nav-active' : '' }}">Hidden Gems</a>
        <a href="{{ $isHome ? '#trips' : $base . '#trips' }}" onclick="mob()" class="{{ request()->routeIs('trips.*') ? 'nav-active' : '' }}">Open Trip</a>
        <a href="{{ $isHome ? '#kuliner' : $base . '#kuliner' }}" onclick="mob()" class="{{ request()->routeIs('culinary.*') ? 'nav-active' : '' }}">Kuliner</a>
        <a href="{{ $isHome ? '#map' : $base . '#map' }}" onclick="mob()">Peta</a>
        
        @auth
            @if(auth()->user()->isWebAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-dark" style="margin-top:12px">Dashboard Web</a>
            @elseif(auth()->user()->isTripAdmin())
                <a href="#" class="btn btn-dark" style="margin-top:12px">Dashboard Trip</a>
            @else
                <a href="#" class="btn btn-dark" style="margin-top:12px">Profil Saya</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="margin-top:12px; display:flex; width: 100%;">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%; text-align:center;">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-dark" style="margin-top:12px">Masuk / Daftar</a>
        @endauth
    </div>
