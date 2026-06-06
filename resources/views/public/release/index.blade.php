@extends('layouts.header-public')

@section('title', 'Releases Directory - MixtapeSide')

@section('content')
    <div class="container py-5">
        <div class="section-header text-center">
            <h2 class="section-title">RELEASES<span class="graffiti-text">Latest</span></h2>
            <p class="subtitle">Fresh noise from the underground scene.</p>
        </div>

        <section class="releases-section container">
            <div class="releases-grid">
                @forelse ($releases as $release)
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
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                        <p class="text-muted" style="font-size: 20px;">No releases found.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                {{ $releases->appends(request()->query())->links('vendor.pagination.mixtape') }}
            </div>
        </section>
    </div>
@endsection
