@extends('layouts.header')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">{{ $title }} Detail</h4>
        <div class="d-flex gap-2">
            @if(auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                <a href="{{ route('management.gig.edit', $item->id) }}" class="btn btn-label-primary editBtn">
                    <i class="ri-edit-2-line me-1"></i> Edit Gig
                </a>
            @endif
            <a href="{{ route('management.gig.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <!-- Gig Info Card -->
            <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                @if($item->banner_url)
                    <img src="{{ asset($item->banner_url) }}" class="w-100 object-fit-cover" height="150" alt="Banner">
                @else
                    <div class="bg-label-primary w-100" style="height: 150px; opacity: 0.1;"></div>
                @endif
                <div class="card-body pt-0">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl mx-auto mb-3" style="margin-top: -40px; position: relative;">
                            <img src="{{ asset($item->poster_url ?? 'assets/img/elements/1.jpg') }}" alt="Poster" class="rounded border border-5 border-card shadow">
                        </div>
                        <h5 class="mb-1 fw-bold text-uppercase">{{ $item->title }}</h5>
                        <p class="text-muted small mb-2"><i class="ri-map-pin-2-line me-1"></i> {{ $item->city }}</p>
                        <span class="badge bg-label-{{ $item->is_verified ? 'success' : 'warning' }}">
                            {{ $item->is_verified ? 'Verified' : 'Pending Verification' }}
                        </span>
                    </div>
                    
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                <span class="fw-medium text-heading">Date:</span>
                                <span class="text-muted">{{ is_string($item->date) ? $item->date : $item->date->format('d M Y') }}</span>
                            </li>
                            <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                <span class="fw-medium text-heading">Time:</span>
                                <span class="text-muted">{{ $item->start_time ?? '-' }}</span>
                            </li>
                            <li class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                <span class="fw-medium text-heading">Price:</span>
                                <span class="text-muted fw-bold text-danger">{{ $item->ticket_price ?? 'Free / TBA' }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium text-heading d-block mb-1">Venue:</span>
                                <p class="text-muted small mb-0">{{ $item->venue_name }}</p>
                                <p class="text-muted smaller mb-0">{{ $item->venue_address }}</p>
                            </li>
                            <li class="mb-3 border-top pt-2">
                                <span class="fw-medium text-heading d-block mb-1 text-uppercase small letter-spacing-1">Organizer</span>
                                <div class="d-flex align-items-center">
                                    @if($item->organizer)
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ asset($item->organizer->logo_url ?? 'assets/img/elements/1.jpg') }}" class="rounded-circle">
                                        </div>
                                        <span class="text-muted">{{ $item->organizer->name }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Independent / Unknown</span>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Ticket Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header border-bottom">
                    <h6 class="m-0 fw-bold"><i class="ri-ticket-2-line me-1 text-primary"></i> Ticket Information</h6>
                </div>
                <div class="card-body py-3 text-muted small" style="line-height: 1.6;">
                    {!! $item->ticket_info ? nl2br(e($item->ticket_info)) : 'No specific ticket instructions provided.' !!}
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Tabs Navigation -->
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-lineup" aria-controls="tab-lineup" aria-selected="true">
                            <i class="ri-group-line me-1"></i> Lineup
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-partners" aria-controls="tab-partners" aria-selected="false">
                            <i class="ri-building-line me-1"></i> Partners / Labels
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Lineup Tab -->
                    <div class="tab-pane fade show active" id="tab-lineup" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="m-0 fw-bold">Band Lineup</h5>
                            @if(auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                                <button class="btn btn-primary btn-sm btnAddBand">
                                    <i class="ri-add-line me-1"></i> Add Band
                                </button>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%" class="text-center">Order</th>
                                        <th>Band Name</th>
                                        <th>Genre</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->bands()->orderBy('performance_order')->get() as $band)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $band->pivot->performance_order ?? '-' }}</td>
                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($band->logo_url ?? 'assets/img/elements/1.jpg') }}" class="rounded me-2" width="30" height="30">
                                                    <a href="{{ route('management.band.show', $band->id) }}" class="text-heading">{{ $band->name }}</a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if(is_array($band->genre))
                                                        @foreach(array_slice($band->genre, 0, 2) as $g)
                                                            <span class="badge bg-label-secondary smaller">{{ $g }}</span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-icon btn-label-danger delBand" data-id="{{ $band->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                No lineup announced yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Partners Tab -->
                    <div class="tab-pane fade" id="tab-partners" role="tabpanel">
                        <h5 class="mb-3 fw-bold">Supporting Labels & Partners</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Partner Name</th>
                                        <th>Role</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->labels as $label)
                                        <tr>
                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($label->logo_url ?? 'assets/img/elements/1.jpg') }}" class="rounded me-2" width="30" height="30">
                                                    {{ $label->name }}
                                                </div>
                                            </td>
                                            <td><span class="badge bg-label-info">{{ $label->pivot->partnership_role ?? 'Partner' }}</span></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-icon btn-label-danger delPartner" data-id="{{ $label->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                No partners listed.
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
        // Edit Gig via Modal
        $(".editBtn").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            
            $('#mymodal .modal-title').text('Edit Gig Details');
            $('#mymodal .modal-body').html('<div class="text-center py-5"><i class="ri-loader-2-line ri-spin ri-3x text-primary"></i></div>');
            $('#mymodal').modal('show');

            $.get(url, function(response) {
                if(response.status) {
                    $('#mymodal .modal-body').html(response.view);
                    $('.select').select2({ dropdownParent: $('#mymodal') });
                    if (typeof SelectRemoteData === "function") {
                        SelectRemoteData('.select-remote-organizer', '{{ route('select.organizers') }}');
                    }
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
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
    .smaller {
        font-size: 0.75rem;
    }
</style>
@endsection
