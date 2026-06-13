@extends('layouts.header-public')

@section('title', 'ZINE Journal - MixtapeSide')

@section('content')
    <div class="container py-5">
        <div class="section-header text-center">
            <h2 class="section-title">ZINE<span class="graffiti-text">JOURNAL</span></h2>
            <p class="subtitle">Stories, news, and deep dives into the local music scene.</p>
        </div>

        <section class="releases-section container">
            <div class="premium-grid">
                @forelse($zines as $zine)
                    <div class="premium-card">
                        <a href="{{ is_object($zine) && isset($zine->slug) ? route('public.zine.show', $zine->slug) : '#' }}" class="card-image-wrapper">
                            @if (isset($zine->thumbnail_url) && $zine->thumbnail_url)
                                <img src="{{ asset($zine->thumbnail_url) }}" alt="{{ $zine->title }}" />
                            @else
                                <div class="no-cover-placeholder" style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                    <i class="fa-solid fa-newspaper fa-4x"></i>
                                </div>
                            @endif
                            <div class="card-badge">{{ is_string($zine->published_at) ? $zine->published_at : $zine->published_at->format('d M Y') }}</div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="{{ is_object($zine) && isset($zine->slug) ? route('public.zine.show', $zine->slug) : '#' }}">{{ $zine->title }}</a>
                            </h3>
                            <div class="card-subtitle">
                                <i class="fa-solid fa-user-pen" style="color: var(--accent-red);"></i>
                                {{ is_object($zine->author) ? $zine->author->name : 'Editor' }}
                            </div>

                            <p style="color: #bbb; margin-top: 15px; font-size: 14px; line-height: 1.6; height: 4.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                {{ Str::limit(strip_tags($zine->content), 120) }}
                            </p>

                            <div class="card-meta" style="color: #888; border-top: 1px solid rgba(255,255,255,0.08);">
                                <span><i class="fa-solid fa-clock"></i> 5 MIN READ</span>
                                <span>{{ count($zine->comments ?? []) }} Reactions</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                        <p class="text-muted" style="font-size: 20px;">No articles published yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                {{ $zines->links('vendor.pagination.mixtape') }}
            </div>
        </section>
    </div>
@endsection
