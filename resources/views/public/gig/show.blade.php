@extends('layouts.header-public')

@section('title', $gig->title . ' - Gig Detail - MixtapeSide')

@section('content')
    <!-- Floating Capture Button -->
    <button id="capture-band" class="btn-capture-floating" title="Share this gig">
        <i class="fa-solid fa-share-nodes"></i>
    </button>
    <!-- Gig Hero -->
    <header class="band-hero"
        style="background-image: url('{{ $gig->banner_url ? asset($gig->banner_url) : ($gig->poster_url ? asset($gig->poster_url) : 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=2000&auto=format&fit=crop') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-watermark">
            <i class="fa-solid fa-record-vinyl"></i>
            <span>MixtapeSide.com</span>
        </div>
        <div class="container">
            <div class="band-hero-content">
                <div class="band-logo-large" style="aspect-ratio: 1/1;">
                    @if ($gig->poster_url)
                        <img src="{{ asset($gig->poster_url) }}" alt="{{ $gig->title }}" />
                    @else
                        <div class="no-cover-placeholder"
                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #111; color: #333;">
                            <i class="fa-solid fa-calendar-days fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="band-header-info">
                    <span class="band-type-label">{{ date('d F Y', strtotime($gig->date)) }}</span>
                    <h1 class="band-name-huge">{{ $gig->title }}</h1>

                    <div class="band-quick-meta">
                        <span><i class="fa-solid fa-location-dot"></i> {{ $gig->venue_name }}, {{ $gig->city }}</span>
                        <span><i class="fa-solid fa-clock"></i>
                            {{ $gig->start_time ? date('H:i', strtotime($gig->start_time)) . ' WIB' : 'TBA' }}</span>
                    </div>

                    <div class="band-hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $gig->bands->count() }}</span>
                            <span class="hero-stat-label">BANDS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $gig->labels->count() }}</span>
                            <span class="hero-stat-label">PARTNERS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">LIVE</span>
                            <span class="hero-stat-label">CULTURE</span>
                        </div>
                        @if ($gig->organizer)
                            <div class="hero-stat-item">
                                <span class="hero-stat-value"
                                    style="font-size: 20px; line-height: 1.2;">{{ $gig->organizer->name }}</span>
                                <span class="hero-stat-label">ORGANIZER</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 d-flex gap-3" data-html2canvas-ignore>
                        @auth
                            <button class="btn-outline" style="padding: 10px 25px;"><i class="fa-solid fa-check"></i> I'm
                                Attending</button>
                        @else
                            <a href="{{ route('login') }}" class="btn-outline" style="padding: 10px 25px;">Login to Attend</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="profile-tabs-section container">
        <div class="tabs-wrapper">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="lineup">Line-up</button>
                <button class="tab-btn" data-tab="partners">Partners</button>
                <button class="tab-btn" data-tab="venue">Venue Info</button>
            </div>

            <!-- Line-up Tab -->
            <div class="tab-content active" id="tab-lineup">
                @if ($gig->bands->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">No bands announced yet for this gig.</p>
                @else
                    <div class="premium-grid">
                        @foreach ($gig->bands as $band)
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
                                    <div class="card-badge">Performer</div>
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

            <!-- Partners Tab -->
            <div class="tab-content" id="tab-partners">
                @if ($gig->labels->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">No label partners listed for this gig.</p>
                @else
                    <div class="premium-grid">
                        @foreach ($gig->labels as $label)
                            <div class="premium-card">
                                <a href="{{ route('public.label.show', $label->slug) }}" class="card-image-wrapper">
                                    @if ($label->logo_url)
                                        <img src="{{ asset($label->logo_url) }}" alt="{{ $label->name }}" />
                                    @else
                                        <div class="no-cover-placeholder"
                                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                            <i class="fa-solid fa-building fa-4x"></i>
                                        </div>
                                    @endif
                                    <div class="card-badge">{{ $label->pivot->partnership_role }}</div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title">
                                        <a href="{{ route('public.label.show', $label->slug) }}">{{ $label->name }}</a>
                                    </h3>
                                    <div class="card-subtitle">{{ $label->city }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Venue Tab -->
            <div class="tab-content" id="tab-venue">
                <div class="bio-wrapper" style="text-align: center;">
                    <h4 class="tab-sub-title" style="border: none; padding: 0; margin-bottom: 40px;">{{ $gig->venue_name }}
                    </h4>
                    <p style="font-size: 24px; color: white; margin-bottom: 10px;">{{ $gig->venue_address }}</p>
                    <p style="font-size: 18px; color: var(--text-muted);">{{ $gig->city }}</p>

                    @if ($gig->ticket_info)
                        <div
                            style="background: rgba(255,255,255,0.02); padding: 40px; border-radius: 8px; margin-top: 50px; border: 1px solid rgba(255,255,255,0.05);">
                            <h5 class="meta-label">Additional Ticket Info</h5>
                            <p style="color: #bbb; margin-top: 15px;">{{ $gig->ticket_info }}</p>
                        </div>
                    @endif
                </div>
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
                    link.download = '{{ $gig->slug }}-mixtapeside.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();

                    $btn.html(originalHtml).prop('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Captured!',
                        text: 'Gig profile has been saved as PNG.',
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
