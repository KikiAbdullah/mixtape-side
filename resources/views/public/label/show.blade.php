@extends('layouts.header-public')

@section('title', $label->name . ' - Label Profile - MixtapeSide')

@section('content')
    <!-- Floating Capture Button -->
    <button id="capture-band" class="btn-capture-floating" title="Share this label">
        <i class="fa-solid fa-share-nodes"></i>
    </button>
    <!-- Label Hero -->
    <header class="band-hero"
        style="background-image: url('{{ $label->banner_url ? asset($label->banner_url) : ($label->logo_url ? asset($label->logo_url) : 'https://images.unsplash.com/photo-1611426959571-9941542ea4a8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-watermark">
            <i class="fa-solid fa-record-vinyl"></i>
            <span>MixtapeSide.com</span>
        </div>
        <div class="container">
            <div class="band-hero-content">
                <div class="band-logo-large">
                    @if ($label->logo_url)
                        <img src="{{ asset($label->logo_url) }}" alt="{{ $label->name }}" />
                    @else
                        <div class="no-cover-placeholder"
                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #111; color: #333;">
                            <i class="fa-solid fa-building fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="band-header-info">
                    <span class="band-type-label">Record Label</span>
                    <h1 class="band-name-huge">{{ $label->name }}</h1>

                    <div class="band-quick-meta">
                        <span><i class="fa-solid fa-location-dot"></i> {{ $label->city }}</span>
                        <span><i class="fa-solid fa-calendar-days"></i> Est. {{ $label->formed_year }}</span>
                        <span><i class="fa-solid fa-bolt"></i> {{ $label->status }}</span>
                    </div>

                    <div class="band-hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $catalog->count() }}</span>
                            <span class="hero-stat-label">CATALOG</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $roster->count() }}</span>
                            <span class="hero-stat-label">ROSTER</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">INDIE</span>
                            <span class="hero-stat-label">FORCES</span>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3" data-html2canvas-ignore>
                        @if ($label->website_url)
                            <a href="{{ $label->website_url }}" target="_blank" class="btn-outline"
                                style="padding: 10px 25px;"><i class="fa-solid fa-globe"></i> Visit Website</a>
                        @endif
                        @if ($label->contact_email)
                            <a href="mailto:{{ $label->contact_email }}" class="btn-outline" style="padding: 10px 25px;"><i
                                    class="fa-solid fa-envelope"></i> Contact</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="profile-tabs-section container">
        <div class="tabs-wrapper">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="about">About</button>
                <button class="tab-btn" data-tab="catalog">Catalog</button>
                <button class="tab-btn" data-tab="roster">Roster</button>
            </div>

            <!-- About Tab -->
            <div class="tab-content active" id="tab-about">
                <div class="bio-wrapper">
                    @if ($label->description)
                        {!! nl2br(e($label->description)) !!}
                    @else
                        <p class="text-muted text-center py-5">No description available for this label yet.</p>
                    @endif
                </div>
            </div>

            <!-- Catalog Tab -->
            <div class="tab-content" id="tab-catalog">
                @if ($catalog->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">No releases in catalog.</p>
                @else
                    <div class="premium-grid">
                        @foreach ($catalog as $release)
                            <div class="premium-card">
                                <a href="{{ route('public.release.show', [$release->band->slug, $release->slug]) }}"
                                    class="card-image-wrapper">
                                    @if ($release->cover_url)
                                        <img src="{{ asset($release->cover_url) }}" alt="{{ $release->title }}" />
                                    @else
                                        <div class="no-cover-placeholder"
                                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                            <i class="fa-solid fa-compact-disc fa-4x"></i>
                                        </div>
                                    @endif
                                    <div class="card-badge">{{ $release->release_type }}</div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title">
                                        <a
                                            href="{{ route('public.release.show', [$release->band->slug, $release->slug]) }}">{{ $release->title }}</a>
                                    </h3>
                                    <div class="card-subtitle">{{ $release->band->name }}</div>
                                    <div class="card-meta">
                                        <span>{{ $release->original_release_year }}</span>
                                        <span>{{ $release->track_count ?? 0 }} Tracks</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Roster Tab -->
            <div class="tab-content" id="tab-roster">
                @if ($roster->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">No bands in roster.</p>
                @else
                    <div class="premium-grid">
                        @foreach ($roster as $band)
                            <div class="premium-card">
                                <a href="{{ route('public.band.show', $band->slug) }}" class="card-image-wrapper">
                                    @if ($band->logo_url)
                                        <img src="{{ asset($band->logo_url) }}" alt="{{ $band->name }}" />
                                    @else
                                        <div class="no-cover-placeholder"
                                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                            <i class="fa-solid fa-guitar fa-4x"></i>
                                        </div>
                                    @endif
                                    <div class="card-badge">Roster</div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title">
                                        <a href="{{ route('public.band.show', $band->slug) }}">{{ $band->name }}</a>
                                    </h3>
                                    <div class="card-subtitle"><i class="fa-solid fa-location-dot"
                                            style="color: var(--accent-red);"></i> {{ $band->city }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
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
                    link.download = '{{ $label->slug }}-mixtapeside.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();

                    $btn.html(originalHtml).prop('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Captured!',
                        text: 'Label profile has been saved as PNG.',
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
