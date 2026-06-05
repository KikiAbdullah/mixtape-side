@extends('layouts.header-public')

@section('title', 'Gigs & Events - MixtapeSide')

@section('content')
    <div class="container py-5">
        <div class="section-header text-center">
            <h2 class="section-title">GIGS<span class="graffiti-text">Updates</span></h2>
            <p class="subtitle">Witness the intensity of live music and local events.</p>
        </div>


        <section class="releases-section container">
            <div class="premium-index-layout">
                <!-- Filters -->
                <aside class="premium-sidebar">
                    <h3 class="filter-title">Filter Events</h3>
                    <form action="{{ route('public.gig.index') }}" method="GET">
                        <div class="filter-group">
                            <label>City</label>
                            <input type="text" name="city" class="filter-input" placeholder="e.g. Bandung"
                                value="{{ request('city') }}">
                        </div>
                        <div class="filter-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="filter-input" value="{{ request('from_date') }}">
                        </div>
                        <button type="submit" class="btn-outline"
                            style="width:100%; text-align:center; margin-top: 10px; cursor: pointer;">Search</button>
                        <a href="{{ route('public.gig.index') }}" class="btn-outline"
                            style="width:100%; text-align:center; display: block; margin-top: 10px; border-color: rgba(255,255,255,0.1); color: #666; font-size: 12px;">Reset
                            Filters</a>
                    </form>
                </aside>

                <!-- Gigs Grid -->
                <div class="premium-grid">
                    @forelse($gigs as $gig)
                        <div class="premium-card">
                            <a href="{{ route('public.gig.show', $gig->slug) }}" class="card-image-wrapper">
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
                                <div class="card-meta">
                                    <span>{{ date('Y', strtotime($gig->date)) }}</span>
                                    <span>{{ $gig->organizer->name ?? 'Underground' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                            <p class="text-muted" style="font-size: 20px;">No gigs found matching your criteria.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="text-center mt-5">
                {{ $gigs->appends(request()->query())->links() }}
            </div>
        </section>

    </div>
@endsection
