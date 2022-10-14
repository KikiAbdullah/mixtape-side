@extends('layouts.header')
@section('customcss')
@endsection

@section('customjs')
    <script type="text/javascript">
        $(function() {
            var table;
            table = $("#dtable").DataTable({
                pageLength: 100,
            });
        });
    </script>
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content container d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    {{ $subtitle }}
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

            <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
                <div class="hstack gap-3 mb-3 mb-lg-0">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content container pt-0">
        @include('layouts.alert')
        <!-- Card -->
        <div class="card w-lg-75 table-responsive">
            <table class="table table-xxs table-striped" id="dtable">
                <thead class="bg-indigo text-white">
                    <tr>
                        <th width="5%">No</th>
                        <th>Permission Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $a)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $a->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /card -->

    </div>
    <!-- /content area -->
@endsection
@section('notification')
    @include('layouts.notification')
@endsection
