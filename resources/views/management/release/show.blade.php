@extends('layouts.header')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">{{ $title }} Detail</h4>
            <div class="d-flex gap-2">
                @if (auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                    <a href="{{ route('management.release.edit', $item->id) }}" class="btn btn-label-primary editReleaseBtn">
                        <i class="ri-edit-2-line me-1"></i> Edit Release
                    </a>
                @endif
                <a href="{{ route('management.release.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Release Info Card -->
                <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-0">
                        @if ($item->banner_url)
                            <img src="{{ asset($item->banner_url) }}" class="w-100 object-fit-cover" height="150"
                                alt="Banner">
                        @else
                            <div class="bg-label-primary w-100" style="height: 150px; opacity: 0.1;"></div>
                        @endif
                        <div class="p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset($item->cover_url ?? 'assets/img/elements/1.jpg') }}"
                                    class="rounded shadow border mb-3" width="150" height="150"
                                    style="margin-top: -75px; position: relative; background: white; object-fit: cover;">
                                <h5 class="mb-1 fw-bold text-uppercase">{{ $item->title }}</h5>
                                <p class="text-primary fw-medium mb-0">
                                    <a
                                        href="{{ route('management.band.show', $item->band_id) }}">{{ $item->band->name ?? '-' }}</a>
                                </p>
                                <span class="badge bg-label-info mt-2">{{ $item->release_type }}</span>
                            </div>

                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                        <span class="fw-medium text-heading">Slug:</span>
                                        <span class="text-muted small">{{ $item->slug }}</span>
                                    </li>
                                    <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                        <span class="fw-medium text-heading">Year:</span>
                                        <span class="text-muted">{{ $item->original_release_year ?? '-' }}</span>
                                    </li>
                                    <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                        <span class="fw-medium text-heading">Status:</span>
                                        @if ($item->is_verified)
                                            <span class="badge bg-label-success">Verified</span>
                                        @else
                                            <span class="badge bg-label-warning">Pending</span>
                                        @endif
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-medium text-heading d-block mb-1">Labels:</span>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($item->labels as $label)
                                                <span class="badge bg-label-secondary small">{{ $label->name }}</span>
                                            @empty
                                                <span class="text-muted smaller fst-italic">No labels connected</span>
                                            @endforelse
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <hr class="my-3">
                            <span
                                class="fw-medium text-heading d-block mb-2 text-uppercase small letter-spacing-1">Description</span>
                            <p class="text-muted small mb-0" style="line-height: 1.6;">
                                {{ $item->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="m-0 fw-bold"><i class="ri-music-2-line me-2 text-primary"></i>TRACKLIST</h5>
                        @if (auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                            <button type="button" class="btn btn-primary btn-sm btnAddTrack">
                                <i class="ri-add-line me-1"></i> Add Track
                            </button>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%" class="text-center">#</th>
                                    <th>Title</th>
                                    <th width="15%" class="text-center">Duration</th>
                                    <th width="12%" class="text-center">Lyrics</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="track-list">
                                @forelse($item->tracks()->orderBy('track_number')->get() as $track)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $track->track_number }}</td>
                                        <td>
                                            <div class="fw-semibold text-heading">{{ $track->title }}</div>
                                        </td>
                                        <td class="text-center"><span
                                                class="badge bg-label-secondary">{{ $track->duration ?? '-' }}</span></td>
                                        <td class="text-center">
                                            @if ($track->lyrics)
                                                <i class="ri-checkbox-circle-fill text-success"
                                                    title="Lyrics Available"></i>
                                                @if ($track->lyrics_translation)
                                                    <i class="ri-global-line text-info" title="Translation Available"></i>
                                                @endif
                                            @else
                                                <i class="ri-close-circle-line text-muted"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                                                <button class="btn btn-sm btn-icon btn-label-danger delTrack"
                                                    data-id="{{ $track->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="ri-disc-line ri-48px mb-2 d-block opacity-25"></i>
                                            No tracks added yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('appmodal')
    <!-- Basic modal -->
    <div id="mymodal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
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

    <!-- Modal Add Track -->
    <div class="modal fade" id="modalTrack" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="trackForm" action="{{ route('management.release.add-track') }}" method="POST">
                    @csrf
                    <input type="hidden" name="release_id" value="{{ $item->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add New Track</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">No</label>
                                <input type="number" name="track_number" class="form-control"
                                    value="{{ $item->tracks->count() + 1 }}" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Track Title</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="e.g. Beyond the Void" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duration</label>
                                <input type="text" name="duration" class="form-control" placeholder="04:20">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase small fw-bold">Original Lyrics</label>
                                <textarea name="lyrics" class="form-control" rows="8" placeholder="Enter lyrics here..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase small fw-bold">Translation</label>
                                <textarea name="lyrics_translation" class="form-control" rows="8" placeholder="Enter translation here..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Track Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            // Edit Release via Modal
            $(".editReleaseBtn").on("click", function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                $('#mymodal .modal-title').text('Edit Release Profile');
                $('#mymodal .modal-body').html(
                    '<div class="text-center py-5"><i class="ri-loader-2-line ri-spin ri-3x text-primary"></i></div>'
                );
                $('#mymodal').modal('show');

                $.get(url, function(response) {
                    if (response.status) {
                        $('#mymodal .modal-body').html(response.view);
                        $('.select').select2({
                            dropdownParent: $('#mymodal')
                        });
                        SelectRemoteData('.select-remote-band', '{{ route('select.bands') }}');
                    }
                });
            });

            // Add Track Modal
            $(".btnAddTrack").on("click", function() {
                $('#modalTrack').modal('show');
            });

            $("#trackForm").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.msg,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            location.reload();
                        }
                    }
                });
            });

            $(".delTrack").on("click", function() {
                var id = $(this).data('id');
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.msg,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    setTimeout(() => location.reload(), 1000);
                                }
                            }
                        });
                    }
                });
            });

            // Handle AJAX Form Submission for Release Edit
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.msg,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#mymodal').modal('hide');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.msg
                            });
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .letter-spacing-1 {
            letter-spacing: 1px;
        }
    </style>
@endsection
