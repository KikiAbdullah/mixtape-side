@extends('layouts.header')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">{{ $title }} Detail</h4>
            <div class="d-flex gap-2">
                @if (auth()->id() == $item->created_by || auth()->user()->hasRole('SUPERADMIN') || auth()->user()->hasRole('ADMIN'))
                    <a href="{{ route('management.organizer.edit', $item->id) }}" class="btn btn-label-primary editBtn">
                        <i class="ri-edit-2-line me-1"></i> Edit Organizer
                    </a>
                @endif
                <a href="{{ route('management.organizer.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Organizer Info Card -->
                <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                    <div class="bg-label-primary w-100" style="height: 100px; opacity: 0.1;"></div>
                    <div class="card-body pt-0">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-xl mx-auto mb-3" style="margin-top: -40px; position: relative;">
                                <img src="{{ asset($item->logo_url ?? 'assets/img/elements/1.jpg') }}" alt="Logo"
                                    class="rounded-circle border border-5 border-card shadow">
                            </div>
                            <h5 class="mb-1 fw-bold text-uppercase">{{ $item->name }}</h5>
                            <p class="text-muted small mb-2"><i class="ri-map-pin-2-line me-1"></i> {{ $item->city }}</p>
                            <span class="badge bg-label-{{ $item->is_verified ? 'success' : 'warning' }}">
                                {{ $item->is_verified ? 'Verified Organizer' : 'Community Member' }}
                            </span>
                        </div>

                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3 border-bottom pb-2">
                                    <span
                                        class="fw-medium text-heading d-block mb-1 text-uppercase small letter-spacing-1">Contact
                                        Info</span>
                                    <p class="text-muted small mb-0">
                                        {{ $item->contact_info ?? 'No contact info provided.' }}</p>
                                </li>
                            </ul>
                        </div>
                        <hr class="my-3">
                        <div class="description-section">
                            <span class="fw-medium text-heading d-block mb-2 text-uppercase small letter-spacing-1">About
                                Organizer</span>
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
                <!-- Events / Gigs Tab -->
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="m-0 fw-bold"><i class="ri-calendar-event-line me-2 text-primary"></i>Organized Events /
                            Gigs</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->gigs()->orderBy('date', 'desc')->get() as $gig)
                                    <tr>
                                        <td class="fw-semibold">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($gig->poster_url ?? 'assets/img/elements/1.jpg') }}"
                                                    class="rounded me-2" width="30" height="30">
                                                <a href="{{ route('management.gig.show', $gig->id) }}"
                                                    class="text-heading">{{ $gig->title }}</a>
                                            </div>
                                        </td>
                                        <td>{{ is_string($gig->date) ? $gig->date : $gig->date->format('d M Y') }}</td>
                                        <td class="small">{{ $gig->venue_name }}</td>
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
                                            No events organized yet.
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
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            // Edit Organizer via Modal
            $(".editBtn").on("click", function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                $('#mymodal .modal-title').text('Edit Organizer Details');
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
    </style>
@endsection
