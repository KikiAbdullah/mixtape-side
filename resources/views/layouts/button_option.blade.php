@if (array_key_exists('vedit', $url))
    <a href="{{ route($url['vedit'], $id) }}" class="action-link-icon-text editBtn">
        <i class="ri-edit-line"></i>
        <span class="fw-semibold text-uppercase">VIEW / EDIT</span>
    </a>
@endif

@if (array_key_exists('edit', $url))
    <a href="{{ route($url['edit'], $id) }}" class="action-link-icon-text editBtn">
        <i class="ri-edit-line"></i>
        <span class="fw-semibold text-uppercase">EDIT</span>
    </a>
@endif

@if (array_key_exists('vshow', $url))
    <a href="{{ route($url['vshow'], $id) }}" class="action-link-icon-text">
        <i class="ri-search-eye-line"></i>
        <span class="fw-semibold text-uppercase">VIEW</span>
    </a>
@endif

@if (array_key_exists('show', $url))
    <a href="{{ route($url['show'], $id) }}" class="action-link-icon-text">
        <i class="ri-search-line"></i>
        <span class="fw-semibold text-uppercase">SHOW</span>
    </a>
@endif

@if (array_key_exists('destroy', $url))
    {!! Form::open([
        'route' => [$url['destroy'], $id],
        'method' => 'DELETE',
        'class' => 'delete form-delete-inline d-inline', // Added d-inline
    ]) !!}
    <a href="javascript:void(0);" class="action-link-icon-text text-danger deleteBtn">
        <i class="ri-close-circle-line"></i>
        <span class="fw-semibold text-uppercase">DELETE</span>
    </a>
    {!! Form::close() !!}
@endif
