@extends('layouts.header-public')

@section('title', 'Bands Directory - MixtapeSide')

@section('content')
    <div class="container py-5">
        <div class="section-header text-center">
            <h2 class="section-title">BANDS<span class="graffiti-text">Explores</span></h2>
            <p class="subtitle">Discover local talents, archived legends, and active forces.</p>
        </div>

        <section class="releases-section container">
            <!-- Bands Directory -->
            <div class="premium-grid">
                    @forelse($bands as $band)
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
                                <div class="card-badge">{{ $band->status }}</div>
                            </a>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="{{ route('public.band.show', $band->slug) }}">{{ $band->name }}</a>
                                </h3>
                                <div class="card-subtitle">
                                    <i class="fa-solid fa-location-dot" style="color: var(--accent-red);"></i>
                                    {{ $band->city }}, {{ $band->country }}
                                </div>

                                <div class="genre-tags">
                                    @if (is_array($band->genre))
                                        @foreach (array_slice($band->genre, 0, 3) as $g)
                                            <span class="genre-tag">{{ $g }}</span>
                                        @endforeach
                                    @else
                                        <span class="genre-tag">{{ $band->genre }}</span>
                                    @endif
                                </div>

                                <div class="card-meta">
                                    <span>Est. {{ $band->formed_year }}</span>
                                    <span>{{ $band->releases_count ?? 0 }} Releases</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                            <p class="text-muted" style="font-size: 20px;">No bands found matching your criteria.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="text-center mt-5">
                {{ $bands->appends(request()->query())->links() }}
            </div>
        </section>
    </div>
@endsection
