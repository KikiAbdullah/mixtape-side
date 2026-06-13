@extends('layouts.header')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">{{ $title }} Detail</h4>
        <div class="d-flex gap-2">
            @if(auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                <a href="{{ route('management.band.edit', $item->id) }}" class="btn btn-label-primary editBtn">
                    <i class="ri-edit-2-line me-1"></i> Edit Band
                </a>
            @endif
            <a href="{{ route('management.band.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <!-- Band Info Card -->
            <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                @if($item->banner_url)
                    <img src="{{ asset($item->banner_url) }}" class="w-100 object-fit-cover" height="150" alt="Banner">
                @else
                    <div class="bg-label-primary w-100" style="height: 150px; opacity: 0.1;"></div>
                @endif
                <div class="card-body pt-0">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl mx-auto mb-3" style="margin-top: -40px; position: relative;">
                            <img src="{{ asset($item->logo_url ?? 'assets/img/elements/1.jpg') }}" alt="Logo" class="rounded-circle border border-5 border-card shadow">
                        </div>
                        <h5 class="mb-1 fw-bold text-uppercase">{{ $item->name }}</h5>
                        <p class="text-muted small mb-2">{{ $item->city }}, {{ $item->country }}</p>
                        <span class="badge bg-label-{{ $item->status == 'Active' ? 'success' : 'warning' }}">{{ $item->status }}</span>
                    </div>
                    
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                <span class="fw-medium text-heading">Formed:</span>
                                <span class="text-muted">{{ $item->formed_year ?? '-' }}</span>
                            </li>
                            @if($item->disbanded_year)
                            <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                <span class="fw-medium text-heading">Disbanded:</span>
                                <span class="text-muted">{{ $item->disbanded_year }}</span>
                            </li>
                            @endif
                            <li class="mb-3">
                                <span class="fw-medium text-heading d-block mb-1">Genres:</span>
                                <div class="d-flex flex-wrap gap-1">
                                    @if(is_array($item->genre))
                                        @foreach($item->genre as $g)
                                            <span class="badge bg-label-danger small">{{ $g }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </div>
                            </li>
                            @if($item->alternative_names && count($item->alternative_names) > 0)
                            <li class="mb-3">
                                <span class="fw-medium text-heading d-block mb-1">Also known as:</span>
                                <p class="text-muted small mb-0">{{ implode(', ', $item->alternative_names) }}</p>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <hr class="my-3">
                    <div class="bio-section">
                        <span class="fw-medium text-heading d-block mb-2 text-uppercase small letter-spacing-1">Biography</span>
                        <div class="text-muted small" style="line-height: 1.6;">
                            {!! $item->biography ? nl2br(e($item->biography)) : '<span class="fst-italic">No biography archived yet.</span>' !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header border-bottom">
                    <h6 class="m-0 fw-bold">Social Links</h6>
                </div>
                <div class="card-body py-3">
                    @if($item->social_links && count($item->social_links) > 0)
                        <div class="d-flex flex-column gap-2">
                            @foreach($item->social_links as $platform => $url)
                                <a href="{{ $url }}" target="_blank" class="d-flex align-items-center text-muted small">
                                    <i class="ri-{{ strtolower($platform) }}-line me-2 text-primary"></i> {{ ucfirst($platform) }}
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0 fst-italic">No links provided.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Tabs Navigation -->
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-releases" aria-controls="tab-releases" aria-selected="true">
                            <i class="ri-disc-line me-1"></i> Discography
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-members" aria-controls="tab-members" aria-selected="false">
                            <i class="ri-group-line me-1"></i> Members
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-gigs" aria-controls="tab-gigs" aria-selected="false">
                            <i class="ri-calendar-event-line me-1"></i> Gigs
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Releases Tab -->
                    <div class="tab-pane fade show active" id="tab-releases" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="m-0 fw-bold">Releases</h5>
                            @if(auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                                <a href="{{ route('management.release.create', ['band_id' => $item->id]) }}" class="btn btn-primary btn-sm btnAddRelease">
                                    <i class="ri-add-line me-1"></i> Add Release
                                </a>
                            @endif
                        </div>
                        <div class="row g-4">
                            @forelse($item->releases()->orderBy('original_release_year', 'desc')->get() as $release)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border shadow-none hover-shadow transition">
                                        <img src="{{ asset($release->cover_url ?? 'assets/img/elements/1.jpg') }}" class="card-img-top object-fit-cover" height="150">
                                        <div class="card-body p-3">
                                            <h6 class="mb-1 text-truncate" title="{{ $release->title }}">
                                                <a href="{{ route('management.release.show', $release->id) }}" class="text-heading fw-bold">{{ $release->title }}</a>
                                            </h6>
                                            <p class="mb-2 small text-muted">{{ $release->release_type }} • {{ $release->original_release_year }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-label-secondary smaller">{{ $release->track_count }} tracks</span>
                                                <a href="{{ route('management.release.show', $release->id) }}" class="btn btn-sm btn-icon btn-label-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="ri-disc-line ri-48px mb-2 d-block opacity-25"></i>
                                    No releases found for this band.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Members Tab -->
                    <div class="tab-pane fade" id="tab-members" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="m-0 fw-bold">Band Members</h5>
                            @if(auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                                <button class="btn btn-primary btn-sm btnAddMember">
                                    <i class="ri-add-line me-1"></i> Add Member
                                </button>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Period</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->members as $member)
                                        <tr>
                                            <td class="fw-semibold">{{ $member->name }}</td>
                                            <td>{{ $member->role }}</td>
                                            <td class="small">{{ $member->joined_year }} - {{ $member->left_year ?? 'Present' }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-label-{{ $member->is_current ? 'success' : 'secondary' }} rounded-pill">
                                                    {{ $member->is_current ? 'Active' : 'Former' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-icon btn-label-danger delMember" data-id="{{ $member->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                No members listed.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Gigs Tab -->
                    <div class="tab-pane fade" id="tab-gigs" role="tabpanel">
                        <h5 class="mb-3 fw-bold">Performance History</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Gig Name</th>
                                        <th>Date</th>
                                        <th>Venue</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->gigs()->orderBy('date', 'desc')->get() as $gig)
                                        <tr>
                                            <td class="fw-semibold">{{ $gig->name }}</td>
                                            <td>{{ $gig->date->format('d M Y') }}</td>
                                            <td class="small">{{ $gig->venue }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('management.gig.show', $gig->id) }}" class="btn btn-sm btn-icon btn-label-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                No performance history recorded.
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
    <!-- /basic modal -->
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        // Edit Band via Modal
        $(".editBtn").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            
            $('#mymodal .modal-title').text('Edit Band Profile');
            $('#mymodal .modal-body').html('<div class="text-center py-5"><i class="ri-loader-2-line ri-spin ri-3x text-primary"></i></div>');
            $('#mymodal').modal('show');

            $.get(url, function(response) {
                if(response.status) {
                    $('#mymodal .modal-body').html(response.view);
                    $('.select').select2({ dropdownParent: $('#mymodal') });
                }
            });
        });

        // Add Release via Modal
        $(".btnAddRelease").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            
            $('#mymodal .modal-title').text('Add New Release');
            $('#mymodal .modal-body').html('<div class="text-center py-5"><i class="ri-loader-2-line ri-spin ri-3x text-primary"></i></div>');
            $('#mymodal').modal('show');

            $.get(url, function(response) {
                if(response.status) {
                    $('#mymodal .modal-body').html(response.view);
                    $('.select').select2({ dropdownParent: $('#mymodal') });
                }
            });
        });

        // Handle AJAX Form Submission inside Modal
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
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
                }
            });
        });
    });
</script>
<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .smaller {
        font-size: 0.75rem;
    }
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
</style>
@endsection
