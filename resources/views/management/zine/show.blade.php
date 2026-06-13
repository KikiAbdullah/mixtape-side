@extends('layouts.header')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">{{ $title }} Detail</h4>
        <div class="d-flex gap-2">
            @if(auth()->id() == $item->author_id || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                <a href="{{ route('management.zine.edit', $item->id) }}" class="btn btn-label-primary editBtn">
                    <i class="ri-edit-2-line me-1"></i> Edit Article
                </a>
            @endif
            <a href="{{ route('management.zine.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Article Preview Card -->
            <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                @if($item->banner_url)
                    <img src="{{ asset($item->banner_url) }}" class="w-100 object-fit-cover" height="300" alt="Banner">
                @else
                    <div class="bg-label-primary w-100" style="height: 200px; opacity: 0.1;"></div>
                @endif
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge bg-label-{{ $item->status == 'Published' ? 'success' : 'warning' }} mb-2">{{ $item->status }}</span>
                        <h2 class="fw-bold mb-3">{{ $item->title }}</h2>
                        <div class="d-flex align-items-center text-muted small">
                            <div class="avatar avatar-xs me-2">
                                <img src="{{ asset('assets/img/avatars/1.png') }}" class="rounded-circle">
                            </div>
                            <span class="fw-medium me-3 text-heading">{{ $item->author->name }}</span>
                            <span class="me-3"><i class="ri-calendar-line me-1"></i> {{ $item->published_at ? $item->published_at->format('d M Y H:i') : 'Not Published' }}</span>
                            <span><i class="ri-chat-3-line me-1"></i> {{ $item->allComments->count() }} Comments</span>
                        </div>
                    </div>

                    <div class="zine-content text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                        {!! $item->content !!}
                    </div>
                </div>
            </div>

            <!-- Comments Section (Management View) -->
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom">
                    <h5 class="m-0 fw-bold"><i class="ri-chat-history-line me-2 text-primary"></i>Recent Comments</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->allComments()->latest()->take(10)->get() as $comment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <img src="{{ asset('assets/img/avatars/1.png') }}" class="rounded-circle">
                                                </div>
                                                <span class="small fw-medium">{{ $comment->user->name ?? 'Guest' }}</span>
                                            </div>
                                        </td>
                                        <td><p class="mb-0 small text-truncate" style="max-width: 300px;" title="{{ $comment->comment }}">{{ $comment->comment }}</p></td>
                                        <td class="small">{{ $comment->created_at->diffForHumans() }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-icon btn-label-danger">
                                                <i class="ri-delete-bin-line text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No comments archived.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Article Meta Info -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header border-bottom">
                    <h6 class="m-0 fw-bold">Article Metadata</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <span class="fw-medium text-heading d-block mb-1 text-uppercase small letter-spacing-1">Slug</span>
                            <code class="small text-primary">{{ $item->slug }}</code>
                        </li>
                        <li class="mb-3 border-top pt-3">
                            <span class="fw-medium text-heading d-block mb-2 text-uppercase small letter-spacing-1">Connected Entities</span>
                            
                            <!-- Bands -->
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Bands:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($item->bands as $band)
                                        <span class="badge bg-label-primary small">{{ $band->name }}</span>
                                    @empty
                                        <span class="text-muted smaller">None</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Releases -->
                            <div class="mb-2 border-top pt-2">
                                <small class="text-muted d-block mb-1">Releases:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($item->releases as $rel)
                                        <span class="badge bg-label-info small">{{ $rel->title }}</span>
                                    @empty
                                        <span class="text-muted smaller">None</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Labels -->
                            <div class="mb-2 border-top pt-2">
                                <small class="text-muted d-block mb-1">Labels:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($item->labels as $lab)
                                        <span class="badge bg-label-secondary small">{{ $lab->name }}</span>
                                    @empty
                                        <span class="text-muted smaller">None</span>
                                    @endforelse
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Thumbnail Card -->
            <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                <div class="card-header border-bottom">
                    <h6 class="m-0 fw-bold">Thumbnail Preview</h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ asset($item->thumbnail_url ?? 'assets/img/elements/1.jpg') }}" class="w-100 object-fit-cover" height="200">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('appmodal')
    <!-- Basic modal -->
    <div id="mymodal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        // Edit Zine via Modal
        $(".editBtn").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            
            $('#mymodal .modal-title').text('Edit Zine Article');
            $('#mymodal .modal-body').html('<div class="text-center py-5"><i class="ri-loader-2-line ri-spin ri-3x text-primary"></i></div>');
            $('#mymodal').modal('show');

            $.get(url, function(response) {
                if(response.status) {
                    $('#mymodal .modal-body').html(response.view);
                    $('.select').select2({ dropdownParent: $('#mymodal') });
                    // Re-init TinyMCE if needed or any other editor
                }
            });
        });

        // Handle AJAX Form Submission
        $(document).on("submit", "#mymodal form", function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire({ icon: 'success', title: 'Success', text: response.msg, timer: 1500, showConfirmButton: false });
                        $('#mymodal').modal('hide');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.msg });
                    }
                }
            });
        });
    });
</script>
<style>
    .zine-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
</style>
@endsection
