@extends('layouts.header')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">{{ $title }} Detail</h4>
        <a href="{{ route('management.release.index') }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <!-- Release Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body p-0">
                    @if($item->banner_url)
                        <img src="{{ asset($item->banner_url) }}" class="w-100 rounded-top object-fit-cover" height="150" alt="Banner">
                    @endif
                    <div class="p-4">
                        <div class="text-center mb-4">
                            <img src="{{ asset($item->cover_url ?? 'assets/img/elements/1.jpg') }}" class="rounded shadow border mb-3" width="150" height="150" style="margin-top: {{ $item->banner_url ? '-75px' : '0' }}; position: relative; background: white;">
                            <h5 class="mb-1 fw-bold text-uppercase">{{ $item->title }}</h5>
                            <p class="text-primary fw-medium mb-0">{{ $item->band->name ?? '-' }}</p>
                            <span class="badge bg-label-info mt-2">{{ $item->release_type }}</span>
                        </div>
                        
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-medium text-heading">Slug:</span>
                                    <span class="text-muted small">{{ $item->slug }}</span>
                                </li>
                                <li class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-medium text-heading">Year:</span>
                                    <span class="text-muted">{{ $item->original_release_year ?? '-' }}</span>
                                </li>
                                <li class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-medium text-heading">Status:</span>
                                    @if($item->is_verified)
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
                        <p class="text-muted small mb-0">{{ $item->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="m-0 fw-bold"><i class="ri-music-2-line me-2 text-primary"></i>TRACKLIST</h5>
                    <button type="button" class="btn btn-primary btn-sm btnAddTrack">
                        <i class="ri-add-line me-1"></i> Add Track
                    </button>
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
                                        <div class="fw-semibold">{{ $track->title }}</div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-label-secondary">{{ $track->duration ?? '-' }}</span></td>
                                    <td class="text-center">
                                        @if($track->lyrics)
                                            <i class="ri-checkbox-circle-fill text-success" title="Lyrics Available"></i>
                                            @if($track->lyrics_translation)
                                                <i class="ri-global-line text-info" title="Translation Available"></i>
                                            @endif
                                        @else
                                            <i class="ri-close-circle-line text-muted"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-icon btn-label-danger delTrack" data-id="{{ $track->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
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

<!-- Modal Add Track -->
<div class="modal fade" id="modalTrack" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="trackForm">
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
                            <input type="number" name="track_number" class="form-control" value="{{ $item->tracks->count() + 1 }}" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Track Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Beyond the Void" required>
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
        $(".btnAddTrack").on("click", function() {
            $('#modalTrack').modal('show');
        });

        $("#trackForm").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('management.release.add-track') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
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
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('management/release/delete-track') }}/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.status) {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
