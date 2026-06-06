@extends('layouts.header-public')

@section('title', 'Discovery Search - MixtapeSide')

@section('content')
    <div class="container py-5" style="margin-top: 60px;">
        <div class="section-header text-center">
            <h2 class="section-title">DISCOVERY<span class="graffiti-text">Catalog</span></h2>
            <p class="subtitle">Search for anything in the MixtapeSide universe.</p>
        </div>


        <section class="releases-section container">
            <form action="{{ route('public.discovery') }}" method="GET" class="search-form"
                style="max-width: 700px; margin-bottom: 60px;">
                <input type="text" name="q" placeholder="Search bands, releases, labels, gigs..."
                    value="{{ $q ?? '' }}" />
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            @if (!empty($q))
                <p class="text-center text-muted" style="margin-bottom: 40px; letter-spacing: 1px;">Showing results for:
                    <strong class="text-white">"{{ $q }}"</strong>
                </p>

                <!-- Results Tabs -->
                <div class="tabs-wrapper">
                    <div class="tabs-nav">
                        <button class="tab-btn {{ !$bands->isEmpty() ? 'active' : '' }}" data-tab="bands">BANDS
                            ({{ $bands->count() }})</button>
                        <button class="tab-btn {{ $bands->isEmpty() && !$releases->isEmpty() ? 'active' : '' }}"
                            data-tab="releases">RELEASES ({{ $releases->count() }})</button>
                        <button
                            class="tab-btn {{ $bands->isEmpty() && $releases->isEmpty() && !$labels->isEmpty() ? 'active' : '' }}"
                            data-tab="labels">LABELS ({{ $labels->count() }})</button>
                        <button
                            class="tab-btn {{ $bands->isEmpty() && $releases->isEmpty() && $labels->isEmpty() && !$gigs->isEmpty() ? 'active' : '' }}"
                            data-tab="gigs">GIGS ({{ $gigs->count() }})</button>
                    </div>

                    <!-- Bands Tab -->
                    <div class="tab-content {{ !$bands->isEmpty() ? 'active' : '' }}" id="tab-bands">
                        @if ($bands->isEmpty())
                            <p class="text-muted text-center" style="padding: 80px 0;">No bands found matching your query.
                            </p>
                        @else
                            <div class="premium-grid">
                                @foreach ($bands as $band)
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
                                            <div class="card-badge">Band/Artist</div>
                                        </a>
                                        <div class="card-content">
                                            <h3 class="card-title">
                                                <a href="{{ route('public.band.show', $band->slug) }}">{{ $band->name }}</a>
                                            </h3>
                                            <div class="card-subtitle">
                                                <i class="fa-solid fa-location-dot" style="color: var(--accent-red);"></i>
                                                {{ $band->city }}
                                            </div>
                                            <div class="genre-tags">
                                                @if (is_array($band->genre))
                                                    @foreach (array_slice($band->genre, 0, 2) as $g)
                                                        <span class="genre-tag">{{ $g }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="genre-tag">{{ $band->genre }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Releases Tab -->
                    <div class="tab-content {{ $bands->isEmpty() && !$releases->isEmpty() ? 'active' : '' }}"
                        id="tab-releases">
                        @if ($releases->isEmpty())
                            <p class="text-muted text-center" style="padding: 80px 0;">No releases found matching your
                                query.</p>
                        @else
                            <div class="premium-grid">
                                @foreach ($releases as $release)
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

                    <!-- Labels Tab -->
                    <div class="tab-content {{ $bands->isEmpty() && $releases->isEmpty() && !$labels->isEmpty() ? 'active' : '' }}"
                        id="tab-labels">
                        @if ($labels->isEmpty())
                            <p class="text-muted text-center" style="padding: 80px 0;">No record labels found matching your
                                query.</p>
                        @else
                            <div class="premium-grid">
                                @foreach ($labels as $label)
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
                                            <div class="card-badge">Label</div>
                                        </a>
                                        <div class="card-content">
                                            <h3 class="card-title">
                                                <a href="{{ route('public.label.show', $label->slug) }}">{{ $label->name }}</a>
                                            </h3>
                                            <div class="card-subtitle">{{ $label->city }}</div>
                                            <div class="card-meta">
                                                <span>Est. {{ $label->formed_year }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Gigs Tab -->
                    <div class="tab-content {{ $bands->isEmpty() && $releases->isEmpty() && $labels->isEmpty() && !$gigs->isEmpty() ? 'active' : '' }}"
                        id="tab-gigs">
                        @if ($gigs->isEmpty())
                            <p class="text-muted text-center" style="padding: 80px 0;">No gigs found matching your query.
                            </p>
                        @else
                            <div class="premium-grid">
                                @foreach ($gigs as $gig)
                                    @php
                                        $isPast = \Carbon\Carbon::parse($gig->date)->isPast() && !\Carbon\Carbon::parse($gig->date)->isToday();
                                    @endphp
                                    <div class="premium-card {{ $isPast ? 'is-past' : '' }}">
                                        <a href="{{ route('public.gig.show', $gig->slug) }}" class="card-image-wrapper">
                                            @if ($isPast)
                                                <div class="past-gig-overlay">
                                                    <div class="past-stamp" style="font-size: 1.5rem;">PASSED</div>
                                                </div>
                                            @endif
                                            @if ($gig->poster_url)
                                                <img src="{{ asset($gig->poster_url) }}" alt="{{ $gig->title }}" />
                                            @else
                                                <div class="no-cover-placeholder"
                                                    style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                                    <i class="fa-solid fa-calendar-days fa-4x"></i>
                                                </div>
                                            @endif
                                            <div class="card-badge">{{ date('d M', strtotime($gig->date)) }}</div>
                                        </a>
                                        <div class="card-content">
                                            <h3 class="card-title">
                                                <a href="{{ route('public.gig.show', $gig->slug) }}">{{ $gig->title }}</a>
                                            </h3>
                                            <div class="card-subtitle">
                                                <i class="fa-solid fa-location-dot" style="color: var(--accent-red);"></i>
                                                {{ $gig->venue_name }}, {{ $gig->city }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-muted text-center" style="padding: 120px 0; font-size: 20px; letter-spacing: 1px;">Start
                    searching to discover everything in the local music scene!</p>
            @endif
        </section>
    </div>
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
    </script>
@endsection
