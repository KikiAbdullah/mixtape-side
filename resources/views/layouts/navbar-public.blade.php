<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('homepage') }}" class="brand-logo">MIXTAPE<span>SIDE</span></a>
        <ul class="nav-links">
            <li><a href="{{ route('homepage') }}" class="{{ request()->routeIs('homepage') ? 'active' : '' }}">Home</a>
            </li>
            <li><a href="{{ route('public.band.index') }}"
                    class="{{ request()->routeIs('public.band.*') ? 'active' : '' }}">Bands</a></li>
            <li><a href="{{ route('public.release.index') }}"
                    class="{{ request()->routeIs('public.release.index') ? 'active' : '' }}">Releases</a>
            </li>
            <li><a href="{{ route('public.gig.index') }}"
                    class="{{ request()->routeIs('public.gig.*') ? 'active' : '' }}">Gigs</a></li>
            <li><a href="{{ route('public.discovery') }}"
                    class="{{ request()->has('q') ? 'active' : '' }}">Discovery</a></li>
        </ul>
        <div class="nav-actions">
            @auth
                <a href="{{ route('siteurl') }}" class="btn-login">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            @endauth
        </div>
    </div>
</nav>
