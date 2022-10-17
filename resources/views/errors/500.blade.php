<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <title>{{ env('APP_NAME') }}</title>

    <link rel="icon" href="{{ asset('assets/images/logo_icon.svg') }}" />

    <!-- Global stylesheets -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">

    <!-- Core JS files -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('app_local/js/theme.js') }}"></script>
    <script src="{{ asset('app_local/js/settings.js') }}"></script>
</head>

<body>

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Content area -->
                <div class="content d-flex justify-content-center align-items-center">

                    <!-- Container -->
                    <div class="flex-fill">

                        <!-- Error title -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/error_bg.svg') }}" class="img-fluid mb-3" height="230" alt="">
                            <h1 class="display-3 fw-semibold lh-1 mb-3">500</h1>
                            <h6 class="w-md-25 mx-md-auto">Oops, an error has occurred. <br> The server encountered an internal error and was unable to complete your request.</h6>
                        </div>
                        <!-- /error title -->


                        <!-- Error content -->
                        <div class="text-center">
                            <a href="#" onclick="history.back()" class="btn btn-light">
                                <i class="ph-arrow-u-down-left me-2"></i>
                                Kembali
                            </a>
                            <a href="{{ route('siteurl') }}" class="btn btn-primary">
                                <i class="ph-house"></i>
                            </a>
                        </div>
                        <!-- /error wrapper -->

                    </div>
                    <!-- /container -->

                </div>
                <!-- /content area -->


            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->
</body>

</html>
