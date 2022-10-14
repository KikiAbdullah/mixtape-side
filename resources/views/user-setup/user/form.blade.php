<div class="row mb-3">
    <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">Username</label>
    <div class="col-lg-9">
        @if (empty($item))
            {!! Form::text('username', null, ['class' => in_array('username', $errors->keys()) ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Username']) !!}
        @else
            {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username', 'disabled' => 'disabled']) !!}
        @endif

    </div>
</div>
<div class="row mb-3">
    <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">Name</label>
    <div class="col-lg-9">
        {!! Form::text('name', null, ['class' => in_array('name', $errors->keys()) ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Name']) !!}
    </div>
</div>
<div class="row mb-3">
    <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">Roles</label>
    <div class="col-lg-9">
        @if (empty($item))
            {!! Form::select('role', $data['list_role'], null, ['class' => 'select', 'placeholder' => 'Select Role']) !!}
        @else
            {!! Form::select('role', $data['list_role'], $item->roles->first()->id ?? null, ['class' => 'select', 'placeholder' => 'Select Role']) !!}
        @endif
    </div>
</div>
<div class="row mb-3">
    <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">Email</label>
    <div class="col-lg-9">
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
    </div>
</div>
<div class="row mb-3">
    <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">WhatsApp</label>
    <div class="col-lg-9">
        {!! Form::text('nowa', null, ['class' => 'form-control', 'placeholder' => 'WhatsApp Number']) !!}
    </div>
</div>
<div class="row mb-3">
    <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">Password</label>
    <div class="col-lg-9">
        {!! Form::password('password', ['class' => in_array('password', $errors->keys()) ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Password']) !!}
    </div>
</div>
@if (!empty($item))
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label text-lg-end d-none d-lg-block">Status</label>
        <div class="col-lg-9">
            {!! Form::select('deleted_at_baru', ['1' => 'Enabled', '0' => 'Disabled'], null, ['class' => 'select', 'placeholder' => 'Status']) !!}
        </div>
    </div>
@endif
