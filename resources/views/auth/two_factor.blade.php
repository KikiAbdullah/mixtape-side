@php
$value = Cookie::get('nhTheme');
if (empty($value)) {
    $theme = 'default';
} else {
    $theme = 'dark';
}
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Two Factor Auth - {{ env('APP_NAME') }}</title>
    @if ($theme == 'default')
        <link rel="icon" href="{{ asset('global_assets/images/logo_favicon_dark.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('global_assets/images/logo_favicon_dark.png') }}" sizes="192x192" />
        <link rel="shortcut apple-touch-icon" href="{{ asset('global_assets/images/logo_favicon_dark.png') }}">
        <meta name="msapplication-TileImage" content="{{ asset('global_assets/images/logo_favicon_dark.png') }}" />
    @else
        <link rel="icon" href="{{ asset('global_assets/images/logo_favicon_light.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('global_assets/images/logo_favicon_light.png') }}" sizes="192x192" />
        <link rel="shortcut apple-touch-icon" href="{{ asset('global_assets/images/logo_favicon_light.png') }}">
        <meta name="msapplication-TileImage" content="{{ asset('global_assets/images/logo_favicon_light.png') }}" />
    @endif

    <!-- Global stylesheets -->
    <link href="{{ asset('global_assets/css/fontnya.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($theme . '/assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <style>
        input {
            letter-spacing: 8px;
            word-spacing: 16px;
        }

    </style>
    <!-- Core JS files -->
    <script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

</head>

<body>

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Content area -->
                <div class="content d-flex justify-content-center mt-lg-5">

                    {!! Form::open(['route' => 'verifyTwoFactor', 'class' => 'login-form']) !!}
                    <!-- Unlock form -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="card-img-actions d-inline-block mb-3">
                                    <img class="rounded-circle"
                                        src="{{ asset('global_assets/images/placeholders/placeholder.jpg') }}"
                                        width="160" height="160" alt="Avatar">
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <h6 class="font-weight-semibold mb-0">Two Factor Authentication</h6>
                                @if (!empty($nowa))
                                    <span class="d-block">Please enter OTP Code that we've sent to
                                        <b>{{ $nowa ?? '' }}</b></span>
                                @endif
                            </div>

                            <div class="text-center mb-3">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            {!! $error . '<br/>' !!}
                                        @endforeach
                                    </div>
                                @endif
                                @if (empty($nowa))
                                    <div class="alert alert-danger">
                                        WhatsApp number not found, please contact Administrator.
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control text-center font-weight-bold" name="otp"
                                    placeholder="______" autofocus>
                                <input type="hidden" name="redirect" value="{{ request()['redirect'] }}">
                            </div>

                            <button type="submit" class="btn btn-primary btn-block"><i class="icon-unlocked mr-2"></i>
                                Confirm</button>
                            <a href="#!"
                                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                class="btn btn-secondary btn-block"> Back to Login</a>
                        </div>
                    </div>
                    <!-- /unlock form -->
                    {!! Form::close() !!}
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

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
