<!doctype html>

<html lang="id" class="light-style layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets_front') }}/" data-template="horizontal-menu-template-no-customizer"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', $title ?? 'Dashboard') | {{ config('app.name', 'Laravel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets_front/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;600&family=Permanent+Marker&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- MixtapeSide Public Theme -->
    <link rel="stylesheet" href="{{ asset('assets_front/css/public-theme-premium.css') }}" />
    <style>
        /* Reset Bootstrap Links */
        a {
            text-decoration: none;
            color: inherit;
        }
        a:hover {
            color: var(--accent-red, #ff3e3e);
        }
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }
        .custom-pagination {
            display: inline-block;
            margin-top: 2rem;
        }

        .pagination-list {
            display: flex;
            list-style: none;
            padding: 0;
            gap: 5px;
        }

        .pagination-item .pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .pagination-item.active .pagination-link {
            background: var(--accent-red, #ff3e3e);
            border-color: var(--accent-red, #ff3e3e);
        }

        .pagination-item .pagination-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .pagination-item.disabled .pagination-link {
            opacity: 0.3;
            cursor: not-allowed;
        }
    </style>

    @yield('customcss')


    <script type="text/javascript">
        const _csrf_token = "{{ csrf_token() }}";
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _csrf_token
                }
            });
        });
    </script>

    <script src="{{ asset('app_local/js/swal.js') }}"></script>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->
            @include('layouts.navbar-public')
            <!-- / Navbar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <main>
                        @yield('content')
                    </main>
                    <!--/ Content -->


                    <footer>
                        <div class="footer-content">
                            <h2 class="main-title">THE WHOLE UNIVERSE <br /><span
                                    class="graffiti-text text-red">RENDERS</span></h2>
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
                                <a href="#">Terms & Condition</a> | <a href="#">Privacy Policy</a> | <a
                                    href="#">Contact Us</a>
                                <p>&copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script> MixtapeSide. All rights reserved.
                                </p>
                            </div>
                        </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>

            <!--/ Layout container -->
        </div>
    </div>

    <!--/ Layout wrapper -->
    @yield('appmodal')


    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
        });
    </script>

    <!-- Page JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    @yield('customjs')

    <!-- Global Notification Handler -->
    <script>
        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#666cff'
                });
            @endif

            // Global AJAX Error Handler
            $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                if (jqXHR.status === 401) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesi Berakhir',
                        text: 'Silakan login kembali.',
                        didClose: () => {
                            window.location.reload();
                        }
                    });
                } else if (jqXHR.status === 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: 'Anda tidak memiliki izin untuk aksi ini.'
                    });
                } else if (jqXHR.status >= 500) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server. Coba lagi nanti.'
                    });
                }
            });
        });
    </script>
</body>

</html>
