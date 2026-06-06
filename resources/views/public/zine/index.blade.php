@extends('layouts.header-public')

@section('title', 'ZINE Journal - MixtapeSide')

@section('content')
    <div class="container py-5" style="margin-top: 60px;">
        <div class="section-header text-center">
            <h2 class="section-title">THE<span class="graffiti-text">ZINE</span></h2>
            <p class="subtitle">Stories, news, and deep dives into the local music scene.</p>
        </div>

        <section class="zine-section container mt-5">
            <div class="premium-grid">
                @forelse($zines as $zine)
                    <div class="premium-card">
                        <a href="{{ route('public.zine.show', $zine->slug) }}" class="card-image-wrapper" style="aspect-ratio: 16/9;">
                            @if ($zine->thumbnail_url)
                                <img src="{{ asset($zine->thumbnail_url) }}" alt="{{ $zine->title }}" />
                            @else
                                <div class="no-cover-placeholder" style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                    <i class="fa-solid fa-newspaper fa-4x"></i>
                                </div>
                            @endif
                            <div class="card-badge">{{ $zine->published_at->format('d M Y') }}</div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="{{ route('public.zine.show', $zine->slug) }}">{{ $zine->title }}</a>
                            </h3>
                            <div class="card-subtitle">By {{ $zine->author->name }}</div>
                            <p class="text-muted mt-2" style="font-size: 14px; line-height: 1.6;">
                                {{ Str::limit(strip_tags($zine->content), 120) }}
                            </p>
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
