@extends('layouts.header-public')

@section('title', 'Gigs & Events - MixtapeSide')

@section('customcss')
    <style>
        .past-gig-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            pointer-events: none;
        }

        .past-stamp {
            border: 4px solid var(--accent-red);
            color: var(--accent-red);
            font-family: 'Permanent Marker', cursive;
            font-size: 2.5rem;
            padding: 10px 20px;
            text-transform: uppercase;
            transform: rotate(-20deg);
            opacity: 0.8;
            letter-spacing: 5px;
            box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
            background: rgba(0, 0, 0, 0.2);
        }

        .premium-card.is-past {
            filter: grayscale(0.5);
            opacity: 0.8;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="section-header text-center">
            <h2 class="section-title">GIGS<span class="graffiti-text">Updates</span></h2>
            <p class="subtitle">Witness the intensity of live music and local events.</p>
        </div>


        <section class="releases-section container">
            <!-- Gigs Grid -->
            <div class="premium-grid">
                    @forelse($gigs as $gig)
                        @php
                            $isPast = \Carbon\Carbon::parse($gig->date)->isPast() && !\Carbon\Carbon::parse($gig->date)->isToday();
                        @endphp
                        <div class="premium-card {{ $isPast ? 'is-past' : '' }}">
                            <a href="{{ route('public.gig.show', $gig->slug) }}" class="card-image-wrapper">
                                @if ($isPast)
                                    <div class="past-gig-overlay">
                                        <div class="past-stamp">PASSED</div>
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
