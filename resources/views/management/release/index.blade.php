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
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Band/Artist</th>
                                    <th>Type</th>
                                    <th>Year</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="dynamic-form">
                @can('releases_create')
                    @include('management.release.create')
                @else
                    <div class="alert alert-warning">
                        Anda tidak memiliki izin untuk menambah data rilis.
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    
    <script type="text/javascript">
        var dtable;
        const urlAjax = '{{ route('management.release.get-data') }}';
        const getButtonOption = '{{ route('get.button-option') }}';
        const buttons = {!! json_encode(['show' => $url['show'], 'edit' => $url['edit'], 'destroy' => $url['destroy']]) !!};
        var html_temp = $("#dynamic-form").html();
        var button_temp = '<a href="#!" class="btn flex-column btn-float py-2 mx-2 text-uppercase text-dark fw-semibold btnBack"><i class="ri-arrow-left-s-line ri-24px text-primary"></i>CANCEL</a>';

        $(document).ready(function($) {
            SelectRemoteData('.select-remote-band', '{{ route('select.bands') }}');


            dtable = $('#dtable').DataTable({
                "select": { style: "single", info: false },
                "serverSide": true,
                "stateSave": true,
                "processing": true,
                "sServerMethod": "GET",
                "deferRender": true,
                "rowId": 'id',
                "ajax": urlAjax,
                "columns": [
                    { data: 'id' },
                    { data: 'cover_display' },
                    { data: 'title' },
                    { data: 'band_name' },
                    { data: 'release_type' },
                    { data: 'original_release_year' },
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
                console.log("Row selected, ID:", id);
                $.ajax({
                    type: 'GET',
                    url: getButtonOption,
                    data: { id: id, buttons: buttons },
                    success: function(response) {
                        console.log("Button option response:", response);
                        if (response.status) {
                            backtoCreate();
                            $(".menuoption").html(response.view);
                        }
                    },
                    error: function(xhr) {
                        console.error("Ajax error fetching buttons:", xhr.responseText);
                    }
                });
            });

            dtable.on('deselect', function(e, dt, type, indexes) {
                if (type === 'row') backtoCreate();
            });

            $("body").on("click", ".editBtn, .btnShow", function(e) {
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status) {
                            $("#dynamic-form").html(response.view);
                            if (response.view.includes('select-remote-band')) {
                                SelectRemoteData('.select-remote-band', '{{ route('select.bands') }}');
                            }
                            $('.select').select2();
                            $('.menuoption').prepend(button_temp);
                            $('.menuoption').find('.editBtn, .btnShow').remove();
                        }
                    }
                });
                e.preventDefault();
            });

            // Handle Add Track
            $("body").on("click", ".btnAddTrack", function() {
                $('#modalTrack').modal('show');
            });

            $("body").on("submit", "#trackForm", function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: '{{ route('management.release.add-track') }}',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modalTrack').modal('hide');
                            form[0].reset();
                            // Refresh Show View
                            $(".btnShow[href*='/release/" + form.find('input[name="release_id"]').val() + "']").first().click();
                            Swal.fire({ icon: 'success', title: 'Success', text: response.msg, timer: 2000 });
                        }
                    }
                });
            });

            // Handle Delete Track
            $("body").on("click", ".delTrack", function() {
                var id = $(this).data('id');
                var release_id = $('input[name="release_id"]').val();
                Swal.fire({
                    title: 'Delete track?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('management/release/delete-track') }}/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                if (response.status) {
                                    $(".btnShow[href*='/release/" + release_id + "']").first().click();
                                    Swal.fire({ icon: 'success', title: 'Deleted!', text: response.msg, timer: 2000 });
                                }
                            }
                        });
                    }
                });
            });

            $("body").on("submit", "#dform, #formupdate", function(e) {
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
                            if(form.attr('id') == 'dform') {
                                form[0].reset();
                                $('.select').val(null).trigger('change');
                                SelectRemoteData('.select-remote-band', '{{ route('select.bands') }}');
                            } else {
                                backtoCreate();
                            }
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
            console.log("Back to Create triggered");
            $("#dynamic-form").html(html_temp);
            $('.menuoption').html('');
            SelectRemoteData('.select-remote-band', '{{ route('select.bands') }}');
        }
    </script>
@endsection
