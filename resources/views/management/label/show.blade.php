@extends('layouts.header')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">{{ $title }} Detail</h4>
            <div class="d-flex gap-2">
                @if (auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                    <a href="{{ route('management.label.edit', $item->id) }}" class="btn btn-label-primary editBtn">
                        <i class="ri-edit-2-line me-1"></i> Edit Label
                    </a>
                @endif
                <a href="{{ route('management.label.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Label Info Card -->
                <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                    @if ($item->banner_url)
                        <img src="{{ asset($item->banner_url) }}" class="w-100 object-fit-cover" height="150"
                            alt="Banner">
                    @else
                        <div class="bg-label-primary w-100" style="height: 150px; opacity: 0.1;"></div>
                    @endif
                    <div class="card-body pt-0">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-xl mx-auto mb-3" style="margin-top: -40px; position: relative;">
                                <img src="{{ asset($item->logo_url ?? 'assets/img/elements/1.jpg') }}" alt="Logo"
                                    class="rounded border border-5 border-card shadow">
                            </div>
                            <h5 class="mb-1 fw-bold text-uppercase">{{ $item->name }}</h5>
                            <p class="text-muted small mb-2"><i class="ri-map-pin-2-line me-1"></i> {{ $item->city }}</p>
                            <span
                                class="badge bg-label-{{ $item->status == 'Active' ? 'success' : 'danger' }}">{{ $item->status }}</span>
                        </div>

                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="fw-medium text-heading">Formed:</span>
                                    <span class="text-muted">{{ $item->formed_year ?? '-' }}</span>
                                </li>
                                @if ($item->defunct_year)
                                    <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                        <span class="fw-medium text-heading">Defunct:</span>
                                        <span class="text-muted">{{ $item->defunct_year }}</span>
                                    </li>
                                @endif
                                <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="fw-medium text-heading">Email:</span>
                                    <span class="text-muted small">{{ $item->contact_email ?? '-' }}</span>
                                </li>
                                <li class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-medium text-heading">Website:</span>
                                    @if ($item->website_url)
                                        <a href="{{ $item->website_url }}" target="_blank" class="small text-truncate"
                                            style="max-width: 150px;">{{ str_replace(['http://', 'https://'], '', $item->website_url) }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <hr class="my-3">
                        <div class="description-section">
                            <span class="fw-medium text-heading d-block mb-2 text-uppercase small letter-spacing-1">About
                                Label</span>
                            <div class="text-muted small" style="line-height: 1.6;">
                                {!! $item->description
                                    ? nl2br(e($item->description))
                                    : '<span class="fst-italic">No description archived yet.</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Tabs Navigation -->
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#tab-releases" aria-controls="tab-releases" aria-selected="true">
                                <i class="ri-disc-line me-1"></i> Label Releases
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#tab-gigs" aria-controls="tab-gigs" aria-selected="false">
                                <i class="ri-calendar-event-line me-1"></i> Hosted Gigs
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Releases Tab -->
                        <div class="tab-pane fade show active" id="tab-releases" role="tabpanel">
                            <h5 class="mb-3 fw-bold">Discography / Pressings</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Band</th>
                                            <th class="text-center">Format</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Catalog #</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->releases as $release)
                                            <tr>
                                                <td class="fw-semibold">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset($release->cover_url ?? 'assets/img/elements/1.jpg') }}"
                                                            class="rounded me-2" width="30" height="30">
                                                        <a href="{{ route('management.release.show', $release->id) }}"
                                                            class="text-heading">{{ $release->title }}</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('management.band.show', $release->band_id) }}"
                                                        class="small">{{ $release->band->name ?? '-' }}</a>
                                                </td>
                                                <td class="text-center"><span
                                                        class="badge bg-label-secondary smaller">{{ $release->pivot->format ?? $release->release_type }}</span>
                                                </td>
                                                <td class="text-center">
                                                    {{ $release->pivot->press_year ?? $release->original_release_year }}
                                                </td>
                                                <td class="text-center"><code
                                                        class="small text-primary">{{ $release->pivot->catalog_number ?? '-' }}</code>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('management.release.show', $release->id) }}"
                                                        class="btn btn-sm btn-icon btn-label-primary">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    No releases connected to this label.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Gigs Tab -->
                        <div class="tab-pane fade" id="tab-gigs" role="tabpanel">
                            <h5 class="mb-3 fw-bold">Gigs Supported / Hosted</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Gig Name</th>
                                            <th>Date</th>
                                            <th>Role</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->gigs as $gig)
                                            <tr>
                                                <td class="fw-semibold">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset($gig->poster_url ?? 'assets/img/elements/1.jpg') }}"
                                                            class="rounded me-2" width="30" height="30">
                                                        <a href="{{ route('management.gig.show', $gig->id) }}"
                                                            class="text-heading">{{ $gig->name }}</a>
                                                    </div>
                                                </td>
                                                <td>{{ $gig->date->format('d M Y') }}</td>
                                                <td><span
                                                        class="badge bg-label-info">{{ $gig->pivot->partnership_role ?? 'Sponsor' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('management.gig.show', $gig->id) }}"
                                                        class="btn btn-sm btn-icon btn-label-primary">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    No gig history recorded.
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
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            // Edit Label via Modal
            $(".editBtn").on("click", function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                $('#mymodal .modal-title').text('Edit Label Details');
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

        .smaller {
            font-size: 0.75rem;
        }
    </style>
@endsection
