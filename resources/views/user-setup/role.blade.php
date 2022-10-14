@extends('layouts.header')
@section('customcss')
    <style>
        table tbody tr {
            cursor: pointer;
        }
    </style>
@endsection

@section('customjs')
    <script type="text/javascript">
        var table, table2, table3;

        function setedit(id, nama, st) {
            $("#id").val(id);
            $("#nama").val(nama);
            $("#status").val(st).trigger('change');
        }

        $(document).ready(function() {
            table = $('#dtable').DataTable({
                "select": {
                    style: "single",
                    info: false
                },
                "serverSide": true,
                "stateSave": true,
                "sServerMethod": "POST",
                "deferRender": true,
                "dom": '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"p>',
                "rowId": 'name',
                "ajax": '{{ route('get.roles') }}',
                "columns": [{
                    "data": "name"
                }],
                "order": [
                    [0, "asc"]
                ]
            });

            table.on('select', function(e, dt, type, indexes) {
                var rowData = table.rows(indexes).data().toArray();
                var name = rowData[0].name;
                $.ajax({
                    type: 'POST',
                    url: '{{ route('get.roles.menu') }}',
                    data: {
                        name: name
                    },
                }).done(function(data) {
                    $(".menuoption").html(data);
                });
                $(".linep").html("<center>Loading..</center>");
                $.ajax({
                    type: 'POST',
                    url: '{{ route('get.roles.line') }}',
                    data: {
                        name: name
                    },
                }).done(function(data) {
                    $(".linep").html(data);
                    table2 = $('#dtable_h').DataTable({
                        "serverSide": true,
                        "stateSave": true,
                        "sServerMethod": "POST",
                        "deferRender": true,
                        "rowId": 'name',
                        "sDom": 't',
                        "paging": false,
                        "searching": false,
                        "info": false,
                        "ajax": {
                            "url": '{{ route('get.hakakses') }}',
                            "type": "POST",
                            "data": {
                                name: name
                            }
                        },
                        "columns": [{
                                "data": "name"
                            },
                            {
                                "data": "opt",
                                'orderable': false,
                                'sortable': false
                            }
                        ],
                        "order": [
                            [0, "asc"]
                        ]
                    });
                    table3 = $('#dtable_h2').DataTable({
                        "serverSide": true,
                        "stateSave": true,
                        "sServerMethod": "POST",
                        "deferRender": true,
                        "rowId": 'name',
                        "sDom": 't',
                        "paging": false,
                        "searching": false,
                        "info": false,
                        "ajax": {
                            "url": '{{ route('get.hakakses2') }}',
                            "type": "POST",
                            "data": {
                                name: name
                            }
                        },
                        "columns": [{
                                "data": "opt",
                                'orderable': false,
                                'sortable': false
                            },
                            {
                                "data": "name"
                            }
                        ],
                        "order": [
                            [1, "asc"]
                        ]
                    });

                    table2.on('click', 'tbody>tr', function() {
                        var id = table2.row(this).id();
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('add.hakakses') }}',
                            data: {
                                name: name,
                                namapilih: id
                            },
                        }).done(function(data) {
                            table2.ajax.reload(null, false);
                            table3.ajax.reload(null, false);
                        });
                    });
                    table3.on('click', 'tbody>tr', function() {
                        var idx = table3.row(this).id();
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('remove.hakakses') }}',
                            data: {
                                name: name,
                                namapilih: idx
                            },
                        }).done(function(data) {
                            table2.ajax.reload(null, false);
                            table3.ajax.reload(null, false);
                        });
                    });
                });
            });

            // External table additions
            // ------------------------------

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');


            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

        });

        function hapus(name) {
            swalInit.fire({
                icon: 'warning',
                title: 'Are you sure delete this {{ $title }}?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        type: 'DELETE',
                        url: '{{ route('delete.roles') }}',
                        data: {
                            name: name
                        },
                        dataType: "json",
                    }).done(function(data) {
                        return data;
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 422) {
                            var xhr = JSON.stringify(JSON.parse(jqXHR.responseText).errors);
                        } else {
                            var xhr = JSON.stringify(JSON.parse(jqXHR.responseText));
                        }
                        swalInit.fire({
                            title: 'Error',
                            text: xhr,
                            icon: 'error',
                        })
                    })
                },
                allowOutsideClick: false
            }).then((result) => {
                if (result.value != null)
                    if (result.value.status) {
                        swalInit.fire({
                            title: 'Success',
                            text: result.value.msg,
                            icon: 'success',
                            didClose: () => {
                                table.ajax.reload(null, false);
                                $(".linep").html("");
                                $(".menuoption").html("");
                            }
                        })
                    } else {
                        swalInit.fire({
                            title: 'Error',
                            text: result.value.msg,
                            icon: 'error',
                        })
                    }
            })

        }

        function addrole() {
            swalInit.fire({
                title: 'Add Role',
                input: 'text',
                inputPlaceholder: 'Enter Role Name',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: function(name) {
                    return $.ajax({
                        type: 'POST',
                        url: '{{ route('add.roles') }}',
                        data: {
                            name: name
                        },
                        dataType: "json",
                    }).done(function(data) {
                        return data;
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 422) {
                            var xhr = JSON.stringify(JSON.parse(jqXHR.responseText).errors);
                        } else {
                            var xhr = JSON.stringify(JSON.parse(jqXHR.responseText));
                        }
                        swalInit.fire({
                            title: 'Error',
                            text: xhr,
                            icon: 'error',
                        })
                    })
                },
                allowOutsideClick: false
            }).then(function(result) {
                if (result.value != null)
                    if (result.value.status) {
                        swalInit.fire({
                            title: 'Success',
                            text: result.value.msg,
                            icon: 'success',
                            didClose: () => {
                                table.ajax.reload(null, false);
                            }
                        })
                    } else {
                        swalInit.fire({
                            title: 'Error',
                            text: result.value.msg,
                            icon: 'error',
                        })
                    }
            });
        }
    </script>
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    {{ $subtitle }}
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

            <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
                <div class="hstack gap-0 mb-3 mb-lg-0">
                    <a href="#!" onclick="addrole()" class="btn flex-column btn-float py-2 mx-2 text-uppercase text-dark fw-semibold"><i class="ph-plus-circle ph-2x text-indigo"></i>ADD ROLE</a>
                    <span class="menuoption"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content pt-0">
        @include('layouts.alert')
        <div class="row">
            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <table class="table table-xxs table-striped table-hover" id="dtable">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /card -->
            </div>
            <div class="linep col-lg-8">
            </div>

        </div>

    </div>
    <!-- /content area -->
@endsection
@section('notification')
    @include('layouts.notification')
@endsection
