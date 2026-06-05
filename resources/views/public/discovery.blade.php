@extends('layouts.header-public')

@section('title', 'Discovery Search - MixtapeSide')

@section('content')
    <div class="container py-5">
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
                            <div class="releases-grid">
                                @foreach ($bands as $band)
                                    <a href="{{ route('public.band.show', $band->slug) }}" class="release-card">
                                        <div class="cover-wrapper">
                                            @if ($band->logo_url)
                                                <img src="{{ asset($band->logo_url) }}" alt="{{ $band->name }}" />
                                            @else
                                                <div class="no-cover-placeholder"><i class="fa-solid fa-guitar fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="vinyl-record"></div>
                                            <span class="badge">Band</span>
                                        </div>
                                        <div class="release-info">
                                            <h3>{{ $band->name }}</h3>
                                            <p class="artist"><i class="fa-solid fa-location-dot"></i> {{ $band->city }}
                                            </p>
                                            <span
                                                class="year">{{ is_array($band->genre) ? implode(', ', $band->genre) : $band->genre }}</span>
                                        </div>
                                    </a>
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
                            <div class="releases-grid">
                                @foreach ($releases as $release)
                                    <a href="{{ route('public.release.show', [$release->band->slug, $release->slug]) }}"
                                        class="release-card">
                                        <div class="cover-wrapper">
                                            @if ($release->cover_url)
                                                <img src="{{ asset($release->cover_url) }}" alt="{{ $release->title }}" />
                                            @else
                                                <div class="no-cover-placeholder"><i
                                                        class="fa-solid fa-compact-disc fa-2x"></i></div>
                                            @endif
                                            <div class="vinyl-record"></div>
                                            <span class="badge">{{ $release->release_type }}</span>
                                        </div>
                                        <div class="release-info">
                                            <h3>{{ $release->title }}</h3>
                                            <p class="artist">{{ $release->band->name }}</p>
                                            <span class="year">{{ $release->original_release_year }}</span>
                                        </div>
                                    </a>
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
                            <div class="releases-grid">
                                @foreach ($labels as $label)
                                    <a href="{{ route('public.label.show', $label->slug) }}" class="release-card">
                                        <div class="cover-wrapper">
                                            @if ($label->logo_url)
                                                <img src="{{ asset($label->logo_url) }}" alt="{{ $label->name }}" />
                                            @else
                                                <div class="no-cover-placeholder"><i class="fa-solid fa-building fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="vinyl-record"></div>
                                            <span class="badge">Label</span>
                                        </div>
                                        <div class="release-info">
                                            <h3>{{ $label->name }}</h3>
                                            <p class="artist"><i class="fa-solid fa-location-dot"></i> {{ $label->city }}
                                            </p>
                                            <span class="year">Est. {{ $label->formed_year }}</span>
                                        </div>
                                    </a>
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
                            <div class="gig-list">
                                @foreach ($gigs as $gig)
                                    <a href="{{ route('public.gig.show', $gig->slug) }}" class="gig-list-item">
                                        <span class="gig-date">{{ date('d M Y', strtotime($gig->date)) }}</span>
                                        <span class="gig-title">{{ $gig->title }}</span>
                                        <span class="gig-venue">{{ $gig->venue_name }}, {{ $gig->city }}</span>
                                    </a>
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
