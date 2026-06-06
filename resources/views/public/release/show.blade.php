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
                        <div class="member-row"
                            style="cursor: pointer; padding: 25px; background: rgba(255,255,255,0.02); margin-bottom: 10px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.05);"
                            data-bs-toggle="modal" data-bs-target="#trackModal{{ $track->id }}">
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

    <!-- Track Modals -->
    @foreach ($release->tracks as $track)
        <div class="modal fade" id="trackModal{{ $track->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content"
                    style="background: #111; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 8px;">
                    <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 30px;">
                        <h5 class="modal-title"
                            style="font-family: var(--font-heading); font-size: 32px; letter-spacing: 1px;">
                            {{ str_pad($track->track_number, 2, '0', STR_PAD_LEFT) }}. {{ $track->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="filter: invert(1); opacity: 0.5;"></button>
                    </div>
                    <div class="modal-body" style="padding: 30px;">
                        <div class="row">
                            <div class="col-md-7 mb-4 mb-md-0">
                                <h6 class="meta-label" style="margin-bottom: 20px;">Lyrics</h6>
                                <div
                                    style="max-height: 450px; overflow-y: auto; font-family: 'Inter', sans-serif; line-height: 1.8; color: #bbb; white-space: pre-wrap; font-size: 16px; padding-right: 20px;">
                                    {{ $track->lyrics ?? 'Lyrics not available yet.' }}</div>
                            </div>
                            <div class="col-md-5">
                                <h6 class="meta-label" style="margin-bottom: 20px;">Contributors</h6>
                                @forelse($track->contributors as $contrib)
                                    <div
                                        style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.03);">
                                        <div style="color: white; font-weight: 600; font-size: 15px;">{{ $contrib->name }}
                                        </div>
                                        <div
                                            style="color: var(--accent-red); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px;">
                                            {{ $contrib->role }}</div>
                                        @if ($contrib->notes)
                                            <div style="color: #555; font-size: 12px; margin-top: 4px;">
                                                {{ $contrib->notes }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-muted" style="font-size: 14px;">No contributor credits listed.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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

            const element = document.querySelector('.band-hero');
            window.scrollTo(0, 0);

            setTimeout(() => {
                html2canvas(element, {
                    useCORS: true,
                    allowTaint: false,
                    backgroundColor: '#080808',
                    scale: 3,
                    logging: false,
                    onclone: (clonedDoc) => {
                        const watermark = clonedDoc.querySelector('.hero-watermark');
                        if (watermark) {
                            watermark.style.opacity = '1';
                        }
                    }
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = '{{ $release->slug }}-mixtapeside.png';
                    link.href = canvas.toDataURL('image/png');
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
                    $btn.html(originalHtml).prop('disabled', false);
                });
            }, 500);
        });
    </script>
@endsection
