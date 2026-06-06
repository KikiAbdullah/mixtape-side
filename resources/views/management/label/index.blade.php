@extends('layouts.header')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1">{{ $title }}</h4>
                <p class="mb-6">Management for {{ $subtitle }}</p>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
                <span class="menuoption"></span>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="table table-xxs" id="dtable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>City</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="dynamic-form">
                @can('labels_create')
                    @include('management.label.create')
                @else
                    <div class="alert alert-warning">
                        Anda tidak memiliki izin untuk menambah data label.
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script type="text/javascript">
        var dtable;
        const urlAjax = '{{ route('management.label.get-data') }}';
        const getButtonOption = '{{ route('get.button-option') }}';
        const buttons = {!! json_encode(['vedit' => $url['edit'], 'destroy' => $url['destroy']]) !!};
        var html_temp = $("#dynamic-form").html();
        var button_temp = '<a href="#!" class="btn flex-column btn-float py-2 mx-2 text-uppercase text-dark fw-semibold btnBack"><i class="ri-arrow-left-s-line ri-24px text-primary"></i>CANCEL</a>';

        $(document).ready(function($) {
            dtable = $('#dtable').DataTable({
                "select": { style: "single", info: false },
                "serverSide": true,
                "stateSave": true,
                "sServerMethod": "GET",
                "deferRender": true,
                "rowId": 'id',
                "ajax": urlAjax,
                "columns": [
                    { data: 'id' },
                    { data: 'logo_display' },
                    { data: 'name' },
                    { data: 'city' },
                    { data: 'status' },
                ],
                "order": [[0, "desc"]],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                "columnDefs": [
                    { "targets": [0], "visible": false, "searchable": false },
                    { "targets": [1], "className": "text-center" }
                ],
            });

            dtable.on('select', function(e, dt, type, indexes) {
                var rowData = dtable.rows(indexes).data().toArray();
                var id = rowData[0].id;
                $.ajax({
                    type: 'GET',
                    url: getButtonOption,
                    data: { id: id, buttons: buttons },
                    success: function(response) {
                        if (response.status) {
                            backtoCreate();
                            $(".menuoption").html(response.view);
                        }
                    }
                });
            });

            dtable.on('deselect', function(e, dt, type, indexes) {
                if (type === 'row') backtoCreate();
            });

            $("body").on("click", ".editBtn", function(e) {
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status) {
                            $("#dynamic-form").html(response.view);
                            $('.select').select2();
                            $('.menuoption').prepend(button_temp);
                            $('.menuoption').find('.editBtn').remove();
                        }
                    }
                });
                e.preventDefault();
            });

            $("body").on("submit", "#dform", function(e) {
                var form = $(this);
                var url = form.attr('action');
                var formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.msg, timer: 2000, showConfirmButton: false });
                            dtable.ajax.reload();
                            form[0].reset();
                            $('.select').val(null).trigger('change');
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: response.msg });
                        }
                    }
                });
                e.preventDefault();
            });

            $("body").on("submit", "#formupdate", function(e) {
                var form = $(this);
                var url = form.attr('action');
                var formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.msg, timer: 2000, showConfirmButton: false });
                            dtable.ajax.reload();
                            backtoCreate();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: response.msg });
                        }
                    }
                });
                e.preventDefault();
            });

            $('body').on('click', '.btnBack', function(e) {
                backtoCreate();
                e.preventDefault();
            });
        });

        function backtoCreate() {
            $("#dynamic-form").html(html_temp);
            $('.menuoption').html('');
            $('.select').select2();
        }
    </script>
@endsection
