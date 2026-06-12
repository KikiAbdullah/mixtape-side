@extends('layouts.header')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1">{{ $title }}</h4>
                <p class="mb-6">Daftar {{ $subtitle }}</p>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
                <span class="menuoption"></span>
            </div>
        </div>

        @include('layouts.alert')

        <!-- Role cards -->
        <div class="row g-6">
            @foreach ($data['list_roles'] as $role)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <p class="mb-0">Total {{ $role->users->count() }} users</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="role-heading">
                                    <h5 class="mb-1">{{ $role->name ?? '' }}</h5>
                                </div>
                            </div>

                            @if ($role->name != 'SUPERADMIN')
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('user-setup.role.edit', $role->id) }}" onclick="editRole(this, event)"
                                        class="role-edit-modal">
                                        Edit Role
                                    </a>

                                    {!! Form::open([
                                        'route' => ['user-setup.role.destroy', $role->id],
                                        'method' => 'DELETE',
                                        'class' => 'delete',
                                        'style' => 'display: contents',
                                    ]) !!}
                                    <a href="#" class="text-danger deleteBtn">
                                        <i class="ri-close-circle-line"></i>
                                    </a>
                                    {!! Form::close() !!}
                                </div>
                            @else
                                <a href="#">&nbsp;</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="row h-100">
                        <div class="col-5">
                            <div class="d-flex align-items-end h-100 justify-content-center">
                                <img src="{{ asset('assets/img/illustrations/add-new-role-illustration.png') }}"
                                    class="img-fluid" alt="Image" width="68" />
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <button onclick="addRole(this, event)"
                                    class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">
                                    Add Role
                                </button>
                                <p class="mb-0">
                                    Add new role,<br />
                                    if it doesn't exist
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Role cards -->
    </div>
@endsection

@section('appmodal')
    <!-- Basic modal -->
    <div id="mymodal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-indigo text-white">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- /basic modal -->
@endsection

@section('customjs')
    <script>
        function addRole(el, e) {
            $("#mymodal").find('.modal-title').html('Tambah {{ $title }}');
            $("#mymodal").modal('show');
            $.ajax({
                url: '{{ route('user-setup.role.create') }}',
                type: 'GET',
                dataType: 'JSON',
                data: {},
                success: function(response) {
                    if (response.status) {
                        $("#mymodal").find('.modal-body-inner').html(response.view);
                    }
                }
            });
            e.preventDefault();
        }

        function editRole(el, e) {
            $("#mymodal").find('.modal-title').html('Edit {{ $title }}');
            $("#mymodal").modal('show');
            $.ajax({
                url: $(el).attr('href'),
                type: 'GET',
                dataType: 'JSON',
                data: {},
                success: function(response) {
                    if (response.status) {
                        $("#mymodal").find('.modal-body-inner').html(response.view);
                    }
                }
            });
            e.preventDefault();
        }

        $('body').on('click', '.deleteBtn', function(e) {
            var form = $(this).parents('form.delete');
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure delete this {{ $title }}?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    input: 'form-control'
                },
                reverseButtons: true,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    Swal.fire({
                        text: 'Loading..',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    form.submit();
                }
            })
            e.preventDefault();
        });
    </script>
@endsection
