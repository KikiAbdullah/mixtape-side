<!doctype html>

<html lang="id" class="light-style layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="horizontal-menu-template-no-customizer" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', $title ?? 'Dashboard') | {{ config('app.name', 'Laravel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;600&family=Permanent+Marker&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- MixtapeSide Public Theme -->
    <link rel="stylesheet" href="{{ asset('assets/css/public-theme-premium.css') }}" />

    @yield('customcss')

    <!-- Helpers -->
    <script src="{{ asset('assets') }}/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets') }}/js/config.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('assets') }}/vendor/js/bootstrap.js"></script>

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

    <!-- Core JS -->
    <script src="{{ asset('assets') }}/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('assets') }}/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>


    <!-- Main JS -->
    <script src="{{ asset('assets') }}/js/main.js"></script>

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
