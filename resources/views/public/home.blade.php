@extends('layouts.header-public')

@section('title', 'MixtapeSide - Rock Archive & Local Scene')

@section('content')
    <header class="hero-section">
        <div class="particles-bg"></div>
        <div class="hero-content">
            <div class="typography-wrapper">
                <h1 class="main-title">NOT HERE TO BE HEARD</h1>
                <h2 class="graffiti-text overlay-1">Rock Archive</h2>
                <h1 class="main-title mt-negative">WE TAKE OVER</h1>
                <h2 class="graffiti-text overlay-2">Local Scene</h2>
            </div>

            <p class="subtitle">Your Gateway to Local Music Heritage.</p>

            <form action="{{ route('public.discovery') }}" class="search-form">
                <input type="text" placeholder="Search bands, releases, gigs..." name="q" />
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </header>

    <section class="stats-section">
        <div class="stat-item">
            <span class="stat-number">{{ $bandCount ?? '5K' }}<span class="text-red">+</span></span>
            <span class="stat-label">Bands Archived</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $releaseCount ?? '12K' }}</span>
            <span class="stat-label">Releases Tracked</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $labelCount ?? '800' }}</span>
            <span class="stat-label">Labels Archived</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $upcomingGigs->count() }}<span class="text-red">+</span></span>
            <span class="stat-label">Upcoming Gigs</span>
        </div>
    </section>

    <!-- Popular Labels Section -->
    <section class="labels-section container mt-5 home-page-section">
        <div class="section-header text-center">
            <h2 class="section-title">
                THE <span class="graffiti-text text-red">RECORD</span> KEEPERS
            </h2>
            <p>Underground labels preserving the noise.</p>
        </div>
        <div class="premium-grid">
            @foreach ($popularLabels ?? [] as $label)
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
                    </a>
                    <div class="card-content">
                        <h3 class="card-title">
                            <a href="{{ route('public.label.show', $label->slug) }}">{{ $label->name }}</a>
                        </h3>
                        <div class="card-subtitle">{{ $label->city }}, Est. {{ $label->formed_year }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('public.discovery') }}?type=labels" class="btn-outline">Browse All Labels</a>
        </div>
    </section>

    <section class="releases-section container home-page-section">
        <div class="section-header">
            <h2 class="section-title">
                ALL ABOUT <span class="graffiti-text text-red">ALBUM</span>
            </h2>
            <p>The latest drops from the underground.</p>
        </div>

        <div class="releases-grid">
            @forelse($newReleases as $release)
                <a href="{{ route('public.release.show', [$release->band->slug, $release->slug]) }}" class="release-card">
                    <div class="cover-wrapper">
                        @if ($release->cover_url)
                            <img src="{{ asset($release->cover_url) }}" alt="{{ $release->title }}" />
                        @else
                            <div class="no-cover-placeholder"><i class="fa-solid fa-compact-disc fa-2x"></i></div>
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
            @empty
                <p class="text-muted text-center">No releases yet.</p>
            @endforelse
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('public.discovery') }}" class="btn-outline">Explore All Releases</a>
        </div>
    </section>

    <section class="gigs-section container home-page-section">
        <div class="section-header text-center">
            <h2 class="section-title">
                WE DON'T PERFORM <br /><span class="graffiti-text text-red">WE TAKE OVER</span>
            </h2>
            <p>Upcoming stages & chaotic live cultures.</p>
        </div>

        <div class="polaroid-collage">
            @forelse($upcomingGigs as $key => $gig)
                <a href="{{ route('public.gig.show', $gig->slug) }}"
                    class="polaroid {{ $key == 0 ? 'p-left' : ($key == 1 ? 'p-center' : 'p-right') }}">
                    @if ($gig->poster_url)
                        <img src="{{ asset($gig->poster_url) }}" alt="{{ $gig->title }}" />
                    @else
                        <div class="no-cover-placeholder"
                            style="height: 250px; background: #eee; display: flex; align-items: center; justify-content: center; color: #ccc;">
                            <i class="fa-solid fa-calendar-day fa-3x"></i></div>
                    @endif
                    <div class="polaroid-caption">
                        <h4>{{ $gig->title }}</h4>
                        <p>{{ $gig->city }}, {{ date('d M Y', strtotime($gig->date)) }}</p>
                    </div>
                    @if ($key == 0 || $key == 2)
                        <img src="https://cdn-icons-png.flaticon.com/512/323/323367.png" class="tape" alt="tape" />
                    @endif
                </a>
            @empty
                <p class="text-muted text-center">No upcoming gigs.</p>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('public.gig.index') }}" class="btn-outline">View Tour Dates</a>
        </div>
    </section>

    <!-- Join The Movement -->
    <section class="join-section mt-5">
        <div class="join-content">
            <h2 class="section-title">JOIN THE <br /><span class="graffiti-text text-red">MOVEMENT</span></h2>
            <p>Help us archive the history, contribute your knowledge, and keep the scene alive.</p>
            <div class="d-flex justify-content-center gap-4 mt-4">
                <a href="{{ route('register') }}" class="btn-outline" style="background: white; color: black; border: none;">Create Account</a>
                <a href="{{ route('public.discovery') }}" class="btn-outline">Contribute Data</a>
            </div>
        </div>
    </section>
@endsection
