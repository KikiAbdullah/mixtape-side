<!-- Footer -->
<div class="navbar navbar-sm navbar-footer border-top">
    <div class="container">
        <span>&copy; @if (env('APP_TAHUN') == date('Y'))
                {{ env('APP_TAHUN') }}
            @else
                {{ env('APP_TAHUN') . ' - ' . date('Y') }}
            @endif <a href="#">{{ env('APP_NAME') }}</a></span>

        <ul class="nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
                    <div class="d-flex align-items-center mx-md-1">
                        <span class="d-none d-md-inline-block ms-2">Made with <i class="ph-heart text-danger"></i></span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- /footer -->
