@extends('layouts.header-public')

@section('title', $band->name . ' - Band/Artist Profile - MixtapeSide')

@section('content')
    <!-- Floating Capture Button -->
    <button id="capture-band" class="btn-capture-floating" title="Share your band">
        <i class="fa-solid fa-share-nodes"></i>
    </button>

    <!-- Band/Artist Hero -->
    <header class="band-hero"
        style="background-image: url('{{ $band->banner_url ? asset($band->banner_url) : ($band->logo_url ? asset($band->logo_url) : 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=2000&auto=format&fit=crop') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-watermark">
            <i class="fa-solid fa-record-vinyl"></i>
            <span>MixtapeSide.com</span>
        </div>
        <div class="container">
            <div class="band-hero-content">
                <div class="band-logo-large">
                    @if ($band->logo_url)
                        <img src="{{ asset($band->logo_url) }}" alt="{{ $band->name }}" />
                    @else
                        <div class="no-cover-placeholder"
                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #111; color: #333;">
                            <i class="fa-solid fa-guitar fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="band-header-info">
                    <span class="band-type-label">Archive No. #{{ str_pad($band->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <h1 class="band-name-huge">{{ $band->name }}</h1>

                    <div class="band-quick-meta">
                        <span><i class="fa-solid fa-location-dot"></i> {{ $band->city }}, {{ $band->country }}</span>
                        <span><i class="fa-solid fa-calendar-days"></i> Est. {{ $band->formed_year }}</span>
                        <span><i class="fa-solid fa-bolt"></i> {{ $band->status }}</span>
                    </div>

                    <div class="genre-tags mt-4">
                        @if (is_array($band->genre))
                            @foreach ($band->genre as $g)
                                <span class="genre-tag"
                                    style="padding: 6px 15px; font-size: 12px;">{{ $g }}</span>
                            @endforeach
                        @else
                            <span class="genre-tag" style="padding: 6px 15px; font-size: 12px;">{{ $band->genre }}</span>
                        @endif
                    </div>

                    <div class="band-hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $groupedReleases->flatten()->count() }}</span>
                            <span class="hero-stat-label">RELEASES</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $currentMembers->count() + $pastMembers->count() }}</span>
                            <span class="hero-stat-label">MUSICIANS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ $upcomingGigs->count() + $pastGigs->count() }}</span>
                            <span class="hero-stat-label">CONCERTS</span>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3" data-html2canvas-ignore>
                        @if (!empty($band->social_links))
                            @foreach ($band->social_links as $platform => $url)
                                @if (!empty($url))
                                    <a href="{{ $url }}" target="_blank" class="btn-outline"
                                        style="padding: 10px 20px; font-size: 18px;">
                                        <i class="fab fa-{{ $platform }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        @endif
                        @if (auth()->check() && !$band->owner_id)
                            <a href="#" class="btn-outline" style="padding: 10px 25px;">Claim Profile</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Band Capture Area (Dedicated for Instagram Stories 9:16) -->
    <div id="band-capture-area"
        style="position: absolute; left: -9999px; top: 0; width: 1080px; height: 1920px; background: #080808; overflow: hidden; display: block;">
        @php
            $bannerUrl = $band->banner_url
                ? asset($band->banner_url)
                : ($band->logo_url
                    ? asset($band->logo_url)
                    : 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=2000&auto=format&fit=crop');
            $logoUrl = $band->logo_url ? asset($band->logo_url) : null;
            $genres = is_array($band->genre) ? implode(' // ', $band->genre) : $band->genre;
        @endphp

        <img src="{{ $bannerUrl }}"
            style="position: absolute; top: 0; left: 50%; height: 100%; width: auto; transform: translateX(-50%); filter: brightness(0.6);">
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.85) 100%);">
        </div>

        <!-- Safe Zone Content (1080x1350 centered in 1080x1920) -->
        <div
            style="position: absolute; top: 285px; height: 1350px; left: 0; right: 0; z-index: 10; display: flex; flex-direction: column; justify-content: space-between; padding: 80px;">

            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h4
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 42px; color: #fff; margin: 0; letter-spacing: 4px;">
                        MIXTAPESIDE</h4>
                    <span
                        style="font-family: 'Inter', sans-serif; font-size: 16px; color: #ff3e3e; font-weight: 800; letter-spacing: 6px;">ARCHIVE
                        // NO. #{{ str_pad($band->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div
                    style="background: #fff; color: #000; padding: 8px 20px; font-family: 'Bebas Neue', sans-serif; font-size: 24px; transform: rotate(5deg); box-shadow: 8px 8px 0px #ff3e3e;">
                    {{ strtoupper($band->status) }}
                </div>
            </div>

            <div
                style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px 0;">
                @if ($logoUrl)
                    <div
                        style="width: 550px; height: 550px; border: 15px solid #fff; box-shadow: 25px 25px 0px rgba(0,0,0,0.6); transform: rotate(-3deg); margin-bottom: 60px; overflow: hidden; background: #111;">
                        <img src="{{ $logoUrl }}" style="width: 100%; height: 100%; object-fit: contain; padding: 20px;">
                    </div>
                @else
                    <div
                        style="width: 550px; height: 550px; border: 15px solid #fff; box-shadow: 25px 25px 0px rgba(0,0,0,0.6); transform: rotate(-3deg); margin-bottom: 60px; display: flex; align-items: center; justify-content: center; background: #111; color: #333;">
                        <i class="fa-solid fa-guitar fa-10x"></i>
                    </div>
                @endif

                <div style="max-width: 900px;">
                    <h1
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 120px; color: #fff; line-height: 1.0; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        {{ $band->name }}
                    </h1>
                </div>

                <div
                    style="margin-top: 40px; font-family: 'Inter', sans-serif; font-size: 24px; color: #ff3e3e; font-weight: 800; text-transform: uppercase; letter-spacing: 4px;">
                    {{ $genres }}
                </div>

                <div
                    style="margin-top: 50px; font-family: 'Permanent Marker', cursive; font-size: 36px; color: #fff; transform: rotate(-1deg); background: #ff3e3e; padding: 10px 35px;">
                    {{ $band->city }}, {{ $band->country }}
                </div>
            </div>

            <div
                style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 3px dashed rgba(255,255,255,0.4); padding-top: 40px;">
                <div style="color: #fff;">
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 14px; margin: 0; opacity: 0.8; text-transform: uppercase; letter-spacing: 2px;">
                        Established</p>
                    <p style="font-family: 'Bebas Neue', sans-serif; font-size: 32px; margin: 0; color: #ff3e3e;">
                        SINCE {{ $band->formed_year }}</p>
                </div>
                <div style="text-align: right;">
                    <p
                        style="font-family: 'Permanent Marker', cursive; font-size: 28px; color: #fff; margin: 0; text-shadow: 2px 2px 0px #ff3e3e;">
                        MixtapeSide.com</p>
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 12px; color: #fff; margin: 0; opacity: 0.6; letter-spacing: 2px;">
                        BANDS // GIGS // CULTURE</p>
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

    <section class="profile-tabs-section container">
        <div class="tabs-wrapper">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="biography">About</button>
                <button class="tab-btn" data-tab="discography">Discography</button>
                <button class="tab-btn" data-tab="members">Personnel</button>
                <button class="tab-btn" data-tab="gigs">Gig History</button>
                <button class="tab-btn" data-tab="zines">Related Zines</button>
            </div>

            <!-- About Tab -->
            <div class="tab-content active" id="tab-biography">
                <div class="bio-wrapper">
                    @if ($band->biography)
                        {!! nl2br(e($band->biography)) !!}
                    @else
                        <p class="text-muted text-center py-5">No biography available for this band yet.</p>
                    @endif
                </div>
            </div>

            <!-- Discography Tab -->
            <div class="tab-content" id="tab-discography">
                @if ($groupedReleases->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">No discography data available.</p>
                @else
                    @foreach ($groupedReleases as $type => $typeReleases)
                        <h4 class="tab-sub-title">{{ $type }}</h4>
                        <div class="premium-grid">
                            @foreach ($typeReleases as $release)
                                <div class="premium-card">
                                    <a href="{{ route('public.release.show', [$band->slug, $release->slug]) }}"
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
                                                href="{{ route('public.release.show', [$band->slug, $release->slug]) }}">{{ $release->title }}</a>
                                        </h3>
                                        <div class="card-meta">
                                            <span>Year: {{ $release->original_release_year }}</span>
                                            <span>{{ $release->track_count ?? 0 }} Tracks</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Members Tab -->
            <div class="tab-content" id="tab-members">
                <h4 class="tab-sub-title">Current Personnel</h4>
                <div class="personnel-grid">
                    @forelse($currentMembers as $member)
                        <div class="personnel-card">
                            <div>
                                <span class="name">{{ $member->name }}</span>
                                <span class="role">{{ $member->role_instrument }}</span>
                            </div>
                            <span class="years">{{ $member->join_year }} - Now</span>
                        </div>
                    @empty
                        <p class="text-muted" style="padding: 20px 0;">No current personnel data.</p>
                    @endforelse
                </div>

                @if (!$pastMembers->isEmpty())
                    <h4 class="tab-sub-title" style="margin-top: 60px;">Former Members</h4>
                    <div class="personnel-grid">
                        @foreach ($pastMembers as $member)
                            <div class="personnel-card" style="opacity: 0.6;">
                                <div>
                                    <span class="name">{{ $member->name }}</span>
                                    <span class="role">{{ $member->role_instrument }}</span>
                                </div>
                                <span class="years">{{ $member->join_year }} - {{ $member->leave_year ?? '?' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Gigs Tab -->
            <div class="tab-content" id="tab-gigs">
                @if (!$upcomingGigs->isEmpty())
                    <h4 class="tab-sub-title">Upcoming Shows</h4>
                    <div class="premium-grid">
                        @foreach ($upcomingGigs as $gig)
                            <div class="premium-card">
                                <a href="{{ route('public.gig.show', $gig->slug) }}" class="card-image-wrapper"
                                    style="aspect-ratio: 16/9;">
                                    @if ($gig->poster_url)
                                        <img src="{{ asset($gig->poster_url) }}" alt="{{ $gig->title }}" />
                                    @else
                                        <div class="no-cover-placeholder"
                                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                            <i class="fa-solid fa-calendar-days fa-3x"></i>
                                        </div>
                                    @endif
                                    <div class="card-badge">{{ date('d M Y', strtotime($gig->date)) }}</div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title">{{ $gig->title }}</h3>
                                    <div class="card-subtitle"><i class="fa-solid fa-location-dot"></i>
                                        {{ $gig->venue_name }}, {{ $gig->city }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <h4 class="tab-sub-title" style="margin-top: 60px;">Past Performances</h4>
                <div class="gig-list">
                    @forelse($pastGigs as $gig)
                        <a href="{{ route('public.gig.show', $gig->slug) }}" class="gig-list-item past"
                            style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 15px;">
                            <span class="gig-date"
                                style="color: var(--accent-red); font-weight: bold;">{{ date('d M Y', strtotime($gig->date)) }}</span>
                            <span class="gig-title"
                                style="font-size: 18px; margin-left: 20px;">{{ $gig->title }}</span>
                            <span class="gig-venue"
                                style="float: right; color: var(--text-muted);">{{ $gig->city }}</span>
                        </a>
                    @empty
                        <p class="text-muted" style="padding: 20px 0;">No past performances recorded.</p>
                    @endforelse
                </div>
            </div>

            <!-- Zines Tab -->
            <div class="tab-content" id="tab-zines">
                @if ($band->zines->where('status', 'Published')->isEmpty())
                    <p class="text-muted text-center" style="padding: 60px 0;">No articles featuring this band/artist yet.</p>
                @else
                    <div class="premium-grid">
                        @foreach ($band->zines->where('status', 'Published') as $zine)
                            <div class="premium-card">
                                <a href="{{ route('public.zine.show', $zine->slug) }}" class="card-image-wrapper" style="aspect-ratio: 16/9;">
                                    @if ($zine->thumbnail_url)
                                        <img src="{{ asset($zine->thumbnail_url) }}" alt="{{ $zine->title }}" />
                                    @else
                                        <div class="no-cover-placeholder" style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                            <i class="fa-solid fa-newspaper fa-3x"></i>
                                        </div>
                                    @endif
                                    <div class="card-badge">{{ $zine->published_at->format('d M Y') }}</div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title">
                                        <a href="{{ route('public.zine.show', $zine->slug) }}">{{ $zine->title }}</a>
                                    </h3>
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

            // Target the dedicated capture area
            const element = document.querySelector('#band-capture-area');

            // Move it into view temporarily for html2canvas to work reliably
            const originalStyle = element.style.cssText;
            element.style.left = '0';
            element.style.top = '0';
            element.style.zIndex = '-1000';

            setTimeout(() => {
                html2canvas(element, {
                    useCORS: true,
                    allowTaint: false,
                    backgroundColor: '#080808',
                    scale: 1,
                    width: 1080,
                    height: 1920,
                    logging: false
                }).then(canvas => {
                    // Restore original style
                    element.style.cssText = originalStyle;

                    const link = document.createElement('a');
                    link.download = 'band-{{ $band->slug }}-mixtapeside.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();

                    $btn.html(originalHtml).prop('disabled', false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Captured!',
                        text: 'Band profile has been saved as PNG.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Capture failed:', err);
                    element.style.cssText = originalStyle;
                    $btn.html(originalHtml).prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Failed to capture image. Check console for details.',
                    });
                });
            }, 500);
        });
    </script>
@endsection
