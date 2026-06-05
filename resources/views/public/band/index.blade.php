@extends('layouts.header-public')

@section('title', 'Bands Directory - MixtapeSide')

@section('content')
    <div class="container py-5">
        <div class="section-header text-center">
            <h2 class="section-title">BANDS<span class="graffiti-text">Explores</span></h2>
            <p class="subtitle">Discover local talents, archived legends, and active forces.</p>
        </div>

        <section class="releases-section container">
            <div class="premium-index-layout">
                <!-- Filters Sidebar -->
                <aside class="premium-sidebar">
                    <h3 class="filter-title">Filter Bands</h3>
                    <form action="{{ route('public.band.index') }}" method="GET">
                        <!-- Genre Filter -->
                        <div class="filter-group">
                            <label>Genre</label>
                            <input type="text" name="genre" class="filter-input" placeholder="e.g. Punk, Hardcore"
                                value="{{ request('genre') }}">
                        </div>

                        <!-- City Filter -->
                        <div class="filter-group">
                            <label>City of Origin</label>
                            <input type="text" name="city" class="filter-input" placeholder="e.g. Bandung, Jakarta"
                                value="{{ request('city') }}">
                        </div>

                        <!-- Status Filter -->
                        <div class="filter-group">
                            <label>Band Status</label>
                            <select name="status" class="filter-input"
                                style="appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239ca3af%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.4-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right .7rem top 50%; background-size: .65rem auto;">
                                <option value="" style="background: #1a1a1a;">All Status</option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}
                                    style="background: #1a1a1a;">Active</option>
                                <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}
                                    style="background: #1a1a1a;">On Hold</option>
                                <option value="Split-up" {{ request('status') == 'Split-up' ? 'selected' : '' }}
                                    style="background: #1a1a1a;">Split-up</option>
                            </select>
                        </div>

                        <!-- Formed Year -->
                        <div class="filter-group">
                            <label>Formed Year Range</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="number" name="formed_from" class="filter-input" placeholder="From"
                                    value="{{ request('formed_from') }}" style="padding: 12px 8px; font-size: 13px;">
                                <input type="number" name="formed_to" class="filter-input" placeholder="To"
                                    value="{{ request('formed_to') }}" style="padding: 12px 8px; font-size: 13px;">
                            </div>
                        </div>

                        <button type="submit" class="btn-outline"
                            style="width:100%; text-align:center; margin-top: 10px; cursor: pointer;">Apply Filters</button>
                        <a href="{{ route('public.band.index') }}" class="btn-outline"
                            style="width:100%; text-align:center; display: block; margin-top: 10px; border-color: rgba(255,255,255,0.1); color: #666; font-size: 12px;">Reset
                            Filters</a>
                    </form>
                </aside>

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
