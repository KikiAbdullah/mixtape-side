<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'MixtapeSide - Rock Archive & Local Scene')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;600&family=Permanent+Marker&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="{{ asset('css/public.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    @yield('styles')
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <a href="/" class="brand-logo">MIXTAPE<span>SIDE</span></a>
        <ul class="nav-links">
          <li><a href="/" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ route('public.band.index') }}" class="{{ Request::is('bands*') ? 'active' : '' }}">Bands</a></li>
          <li><a href="{{ route('public.release.index') }}" class="{{ Request::is('releases*') ? 'active' : '' }}">Releases</a></li>
          <li><a href="{{ route('public.gig.index') }}" class="{{ Request::is('gigs*') ? 'active' : '' }}">Gigs</a></li>
          <li><a href="{{ route('public.discovery') }}" class="{{ Request::is('discovery*') ? 'active' : '' }}">Discovery</a></li>
        </ul>
        <div class="nav-actions">
          <a href="#" class="btn-login">Login</a>
        </div>
      </div>
    </nav>

    @yield('content')

    <footer>
      <div class="footer-content">
        <h2 class="main-title">
          THE WHOLE UNIVERSE <br />
          <span class="graffiti-text text-red">RENDERS</span>
        </h2>

        <div class="footer-links">
          <a href="{{ route('public.discovery') }}" class="glowing-text">JOIN THE MOVEMENT</a>
          <a href="{{ route('public.band.index') }}" class="glowing-text">EXPLORE BANDS</a>
          <a href="{{ route('public.gig.index') }}" class="glowing-text">FIND GIGS</a>
        </div>

        <div class="socials">
          <i class="fa-brands fa-spotify"></i>
          <i class="fa-brands fa-youtube"></i>
          <i class="fa-brands fa-instagram"></i>
          <i class="fa-brands fa-twitter"></i>
        </div>

        <div class="footer-bottom">
          <a href="#">Terms & Condition</a> | <a href="#">Privacy Policy</a> |
          <a href="#">Contact Us</a>
          <p>&copy; 2024 MixtapeSide. All rights reserved.</p>
        </div>
      </div>
    </footer>
    @yield('scripts')
  </body>
</html>
