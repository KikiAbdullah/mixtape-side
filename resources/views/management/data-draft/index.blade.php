@extends('layouts.header')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Management /</span> Moderation Queue</h4>

    <div class="card">
        <h5 class="card-header">Proposed Contributions</h5>
        <div class="card-datatable table-responsive">
            <table class="table table-bordered" id="draft-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Target Table</th>
                        <th>Proposed Data</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        var table = $('#draft-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ $url['get_data'] }}',
            columns: [
                { data: 'created_at' },
                { data: 'user_name' },
                { data: 'target_table' },
                { 
                    data: 'proposed_data',
                    render: function(data) {
                        return '<pre style="font-size: 10px; max-height: 100px; overflow: auto;">' + JSON.stringify(data, null, 2) + '</pre>';
                    }
                },
                { data: 'status' },
                { data: 'action_buttons', orderable: false, searchable: false }
            ]
        });

        $('body').on('click', '.btnApprove', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Approve this change?',
                text: "Data will be applied to the live database.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Apply'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('{{ url('management/data-draft/approve') }}/' + id, { _token: '{{ csrf_token() }}' }, function(res) {
                        if(res.status) {
                            Swal.fire('Success', res.msg, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Error', res.msg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
