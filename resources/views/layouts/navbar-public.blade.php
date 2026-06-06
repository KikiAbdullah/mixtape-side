<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('homepage') }}" class="brand-logo">MIXTAPE<span>SIDE</span></a>

        <!-- Burger Menu (Mobile Only) -->
        <button class="nav-burger" id="sidebar-toggle">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <ul class="nav-links">
            <li><a href="{{ route('homepage') }}" class="{{ request()->routeIs('homepage') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('public.band.index') }}" class="{{ request()->routeIs('public.band.*') ? 'active' : '' }}">Bands</a></li>
            <li><a href="{{ route('public.release.index') }}" class="{{ request()->routeIs('public.release.*') && !request()->has('q') ? 'active' : '' }}">Releases</a></li>
            <li><a href="{{ route('public.zine.index') }}" class="{{ request()->routeIs('public.zine.*') ? 'active' : '' }}">Zine</a></li>
            <li><a href="{{ route('public.gig.index') }}" class="{{ request()->routeIs('public.gig.*') ? 'active' : '' }}">Gigs</a></li>
            <li><a href="{{ route('public.discovery') }}" class="{{ request()->has('q') ? 'active' : '' }}">Discovery</a></li>
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

<!-- Mobile Sidebar -->
<div class="mobile-sidebar-overlay" id="sidebar-overlay"></div>
<aside class="mobile-sidebar" id="mobile-sidebar">
    <div class="sidebar-header">
        <a href="{{ route('homepage') }}" class="brand-logo">MIXTAPE<span>SIDE</span></a>
        <button class="sidebar-close" id="sidebar-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <ul class="sidebar-links">
        <li><a href="{{ route('homepage') }}" class="{{ request()->routeIs('homepage') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('public.band.index') }}" class="{{ request()->routeIs('public.band.*') ? 'active' : '' }}">Bands</a></li>
        <li><a href="{{ route('public.release.index') }}" class="{{ request()->routeIs('public.release.*') && !request()->has('q') ? 'active' : '' }}">Releases</a></li>
        <li><a href="{{ route('public.zine.index') }}" class="{{ request()->routeIs('public.zine.*') ? 'active' : '' }}">Zine</a></li>
        <li><a href="{{ route('public.gig.index') }}" class="{{ request()->routeIs('public.gig.*') ? 'active' : '' }}">Gigs</a></li>
        <li><a href="{{ route('public.discovery') }}" class="{{ request()->has('q') ? 'active' : '' }}">Discovery</a></li>
        <li class="mt-4">
            @auth
                <a href="{{ route('siteurl') }}" class="btn-login" style="width: 100%; text-align: center; display: block;">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login" style="width: 100%; text-align: center; display: block;">Login</a>
            @endauth
        </li>
    </ul>
</aside>

<script>
    $(document).ready(function() {
        $('#sidebar-toggle, #sidebar-overlay, #sidebar-close').on('click', function() {
            $('#mobile-sidebar').toggleClass('active');
            $('#sidebar-overlay').toggleClass('active');
            $('body').toggleClass('overflow-hidden');
        });
    });
</script>
