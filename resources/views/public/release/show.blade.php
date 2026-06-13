@extends('layouts.header-public')

@section('title', $release->title . ' by ' . $band->name . ' - MixtapeSide')

@section('content')
    <!-- Floating Capture Button -->
    <button id="capture-band" class="btn-capture-floating" title="Share this release">
        <i class="fa-solid fa-share-nodes"></i>
    </button>
    <!-- Release Hero -->
    <header class="band-hero"
        style="background-image: url('{{ $release->banner_url ? asset($release->banner_url) : ($release->cover_url ? asset($release->cover_url) : 'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?q=80&w=2000&auto=format&fit=crop') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-watermark">
            <i class="fa-solid fa-record-vinyl"></i>
            <span>MixtapeSide.com</span>
        </div>
        <div class="container">
            <div class="band-hero-content">
                <div class="band-logo-large">
                    @if ($release->cover_url)
                        <img src="{{ asset($release->cover_url) }}" alt="{{ $release->title }}" />
                    @else
                        <div class="no-cover-placeholder"
                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #111; color: #333;">
                            <i class="fa-solid fa-compact-disc fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="band-header-info">
                    <span class="band-type-label">{{ $release->release_type }}</span>
                    <h1 class="band-name-huge">{{ $release->title }}</h1>

                    <div class="band-quick-meta">
                        <span>by <a href="{{ route('public.band.show', $band->slug) }}"
                                style="color: var(--accent-red); font-weight: bold;">{{ $band->name }}</a></span>
                        <span><i class="fa-solid fa-calendar-days"></i> {{ $release->original_release_year }}</span>
                        <span><i class="fa-solid fa-music"></i> {{ $release->track_count }} Tracks</span>
                    </div>

                    <div class="band-hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $release->tracks->count() }}</span>
                            <span class="hero-stat-label">SONGS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $release->labels->count() }}</span>
                            <span class="hero-stat-label">LABEL PRESS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $release->release_type }}</span>
                            <span class="hero-stat-label">FORMAT</span>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3" data-html2canvas-ignore>
                        <a href="#tracklist" class="btn-outline" style="padding: 10px 25px;"><i
                                class="fa-solid fa-play"></i> View Tracklist</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Release Capture Area (Dedicated for Instagram Stories 9:16) -->
    <div id="release-capture-area"
        style="position: absolute; left: -9999px; top: 0; width: 1080px; height: 1920px; background: #080808; overflow: hidden; display: block;">
        @php
            $bannerUrl = $release->banner_url
                ? asset($release->banner_url)
                : ($release->cover_url
                    ? asset($release->cover_url)
                    : 'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?q=80&w=2000&auto=format&fit=crop');
            $coverUrl = $release->cover_url ? asset($release->cover_url) : null;
        @endphp

        <img src="{{ $bannerUrl }}"
            style="position: absolute; top: 0; left: 50%; height: 100%; width: auto; transform: translateX(-50%); filter: brightness(0.6);">
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.85) 100%);">
        </div>

        <!-- Safe Zone Content (utilizing more of the 1080x1920 area) -->
        <div
            style="position: absolute; top: 80px; height: 1720px; left: 0; right: 0; z-index: 10; display: flex; flex-direction: column; justify-content: space-between; padding: 80px;">

            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h4
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 42px; color: #fff; margin: 0; letter-spacing: 4px;">
                        MIXTAPESIDE</h4>
                    <span
                        style="font-family: 'Inter', sans-serif; font-size: 16px; color: #ff3e3e; font-weight: 800; letter-spacing: 6px;">
                        {{ strtoupper($release->release_type) }} // ARCHIVE</span>
                </div>
                <div
                    style="background: #fff; color: #000; padding: 8px 20px; font-family: 'Bebas Neue', sans-serif; font-size: 24px; transform: rotate(5deg); box-shadow: 8px 8px 0px #ff3e3e;">
                    {{ $release->original_release_year }}
                </div>
            </div>

            <div
                style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px 0;">
                @if ($coverUrl)
                    <div
                        style="width: 520px; height: 520px; border: 15px solid #fff; transform: rotate(-2deg); margin-bottom: 40px; overflow: hidden; background: #111;">
                        <img src="{{ $coverUrl }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @else
                    <div
                        style="width: 520px; height: 520px; border: 15px solid #fff; transform: rotate(-2deg); margin-bottom: 40px; display: flex; align-items: center; justify-content: center; background: #111; color: #333;">
                        <i class="fa-solid fa-compact-disc fa-8x"></i>
                    </div>
                @endif

                <div style="max-width: 900px;">
                    <h1
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 110px; color: #fff; line-height: 1.0; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        {{ $release->title }}
                    </h1>
                </div>

                <div
                    style="margin-top: 20px; font-family: 'Permanent Marker', cursive; font-size: 48px; color: #ff3e3e; transform: rotate(1deg); text-shadow: 3px 3px 0px #fff;">
                    {{ $band->name }}
                </div>

                <div
                    style="margin-top: 30px; width: 100%; max-width: 850px; background: rgba(255,255,255,0.03); padding: 40px; border-radius: 12px; text-align: left; border: 1px solid rgba(255,255,255,0.05);">
                    <h5
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 32px; color: #ff3e3e; margin-bottom: 25px; border-bottom: 3px solid #ff3e3e; display: inline-block; padding-right: 30px; letter-spacing: 2px;">
                        TRACKLIST</h5>
                    @foreach ($release->tracks->take(5) as $track)
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <span
                                    style="font-family: 'Bebas Neue', sans-serif; font-size: 28px; color: #ff3e3e; min-width: 40px;">{{ str_pad($track->track_number, 2, '0', STR_PAD_LEFT) }}</span>
                                <span
                                    style="font-family: 'Inter', sans-serif; font-size: 24px; color: #fff; font-weight: 600;">{{ $track->title }}</span>
                            </div>
                            <span
                                style="font-family: monospace; font-size: 20px; color: #888;">{{ $track->duration ? sprintf('%02d:%02d', floor($track->duration / 60), $track->duration % 60) : '--:--' }}</span>
                        </div>
                    @endforeach
                    @if ($release->tracks->count() > 5)
                        <p
                            style="font-family: 'Permanent Marker', cursive; font-size: 20px; color: #ff3e3e; margin-top: 25px; text-align: center; opacity: 0.8; letter-spacing: 1px;">
                            ... AND {{ $release->tracks->count() - 5 }} MORE TRACKS</p>
                    @endif
                </div>
            </div>

            <div
                style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 3px dashed rgba(255,255,255,0.4); padding-top: 40px;">
                <div style="color: #fff;">
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 14px; margin: 0; opacity: 0.8; text-transform: uppercase; letter-spacing: 2px;">
                        Format</p>
                    <p style="font-family: 'Bebas Neue', sans-serif; font-size: 32px; margin: 0; color: #ff3e3e;">
                        {{ $release->release_type }} ({{ $release->track_count }} TRACKS)</p>
                </div>
                <div style="text-align: right;">
                    <p
                        style="font-family: 'Permanent Marker', cursive; font-size: 28px; color: #fff; margin: 0; text-shadow: 2px 2px 0px #ff3e3e;">
                        MixtapeSide.com</p>
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 12px; color: #fff; margin: 0; opacity: 0.6; letter-spacing: 2px;">
                        DISCOGRAPHY // ARCHIVE // SCENE</p>
                </div>
            </div>
        </div>
        <!-- Texture Overlay -->
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); opacity: 0.3; pointer-events: none;">
        </div>
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, transparent 40%, rgba(0,0,0,0.6) 100%); pointer-events: none;">
        </div>
    </div>

    <section class="profile-tabs-section container" id="tracklist">
        <div class="tabs-wrapper">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="tracks">Tracklist</button>
                <button class="tab-btn" data-tab="details">Release Notes</button>
                <button class="tab-btn" data-tab="labels">Label & Pressing</button>
            </div>

            <!-- Tracks Tab -->
            <div class="tab-content active" id="tab-tracks">
                <div class="members-table" style="max-width: 900px; margin: 0 auto;">
                    @forelse($release->tracks as $track)
                        <div class="member-row track-item"
                            style="cursor: pointer; padding: 25px; background: rgba(255,255,255,0.02); margin-bottom: 10px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.05);"
                            data-id="{{ $track->id }}" data-title="{{ $track->title }}">
                            <span class="member-name"
                                style="min-width: 40px; color: var(--accent-red); font-family: var(--font-heading); font-size: 24px;">{{ str_pad($track->track_number, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="member-role"
                                style="flex: 1; font-size: 18px; color: white; padding-left: 20px;">{{ $track->title }}</span>
                            <span class="member-years"
                                style="font-family: monospace; color: #666;">{{ $track->duration ? sprintf('%02d:%02d', floor($track->duration / 60), $track->duration % 60) : '--:--' }}</span>
                        </div>
                    @empty
                        <p class="text-muted text-center" style="padding: 40px 0;">No tracks listed yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Details Tab -->
            <div class="tab-content" id="tab-details">
                <div class="bio-wrapper">
                    @if ($release->description)
                        {!! nl2br(e($release->description)) !!}
                    @else
                        <p class="text-muted text-center py-5">No additional notes for this release.</p>
                    @endif
                </div>
            </div>

            <!-- Labels Tab -->
            <div class="tab-content" id="tab-labels">
                @if ($release->labels->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">Independent / Self-released (D.I.Y)</p>
                @else
                    <div class="premium-grid">
                        @foreach ($release->labels as $label)
                            <div class="premium-card">
                                <a href="{{ route('public.label.show', $label->slug) }}" class="card-image-wrapper"
                                    style="aspect-ratio: 16/9;">
                                    @if ($label->logo_url)
                                        <img src="{{ asset($label->logo_url) }}" alt="{{ $label->name }}" />
                                    @else
                                        <div class="no-cover-placeholder"
                                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                            <i class="fa-solid fa-building fa-3x"></i>
                                        </div>
                                    @endif
                                    <div class="card-badge">{{ $label->pivot->press_type }}</div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title">
                                        <a href="{{ route('public.label.show', $label->slug) }}">{{ $label->name }}</a>
                                    </h3>
                                    <div class="card-subtitle">Format: {{ $label->pivot->format }}
                                        ({{ $label->pivot->press_year }})
                                    </div>
                                    @if ($label->pivot->catalog_number)
                                        <div class="card-meta">
                                            <span>CATALOG #:</span>
                                            <span
                                                style="font-family: monospace; color: white;">{{ $label->pivot->catalog_number }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('appmodal')
    <!-- Basic modal -->
    <div id="mymodal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 8px;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 20px 30px; background: #1a1a1a;">
                    <h5 class="modal-title" style="font-family: 'Bebas Neue', sans-serif; font-size: 28px; color: #ff3e3e; letter-spacing: 1px; margin: 0;"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8;"></button>
                </div>

                <div class="modal-body" style="padding: 30px;">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    <!-- /basic modal -->
@endsection


@section('customjs')
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
            });
        });

        // Capture functionality
        $('#capture-band').on('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

            const element = document.getElementById('release-capture-area');
            const originalStyle = element.style.cssText;

            Object.assign(element.style, {
                position: 'fixed',
                left: '0px',
                top: '0px',
                zIndex: '9999',
                opacity: '1',
                display: 'block'
            });

            setTimeout(() => {
                html2canvas(element, {
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#080808',
                    scale: 2,
                    width: 1080,
                    height: 1920,
                    logging: false
                }).then(canvas => {
                    element.style.cssText = originalStyle;

                    const link = document.createElement('a');
                    link.download = 'release-{{ $release->slug }}-mixtapeside.png';
                    link.href = canvas.toDataURL('image/png', 1.0);
                    link.click();

                    $btn.html(originalHtml).prop('disabled', false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Captured!',
                        text: 'Release profile has been saved as PNG.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Capture failed:', err);
                    element.style.cssText = originalStyle;
                    $btn.html(originalHtml).prop('disabled', false);
                });
            }, 500);
        });

        // Track Detail Modal Logic
        $('.track-item').on('click', function() {
            const trackId = $(this).data('id');
            const title = $(this).data('title');

            // Set title
            $('#mymodal .modal-title').text(title);

            // Show loading
            $('#mymodal .modal-body').html(
                '<div class="text-center py-5"><i class="fa-solid fa-spinner fa-spin fa-2x" style="color: var(--accent-red);"></i><p class="mt-2" style="color: #666; font-family: Bebas Neue; letter-spacing: 2px;">FETCHING DATA...</p></div>'
            );

            // Show modal
            const modalEl = document.getElementById('mymodal');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalEl);
            }
            modalInstance.show();

            // Fetch data
            $.ajax({
                url: '{{ url('/track') }}/' + trackId + '/detail',
                type: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $('#mymodal .modal-body').hide().html(response.view).fadeIn(400);
                    }
                },
                error: function(xhr) {
                    $('#mymodal .modal-body').html(
                        '<div class="text-center py-5"><i class="fa-solid fa-triangle-exclamation fa-2x text-danger"></i><p class="mt-2 text-danger">FAILED TO LOAD TRACK DETAILS</p></div>'
                    );
                }
            });
        });
    </script>
@endsection
