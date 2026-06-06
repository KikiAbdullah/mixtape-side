@extends('layouts.header-public')

@section('title', $zine->title . ' - ZINE - MixtapeSide')

@section('customcss')
<style>
    .zine-content { font-size: 1.15rem; line-height: 1.8; color: #ddd; letter-spacing: 0.3px; }
    .zine-content p { margin-bottom: 1.8rem; }
    .zine-banner { width: 100%; height: 500px; object-fit: cover; border-radius: 4px; margin-bottom: 3rem; border: 1px solid rgba(255,255,255,0.1); }
    .comment-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 4px; margin-bottom: 1rem; }
    .comment-author { font-family: 'Bebas Neue', sans-serif; font-size: 1.2rem; color: var(--accent-red); margin-bottom: 0.5rem; display: block; }
    .comment-text { font-size: 1rem; line-height: 1.5; color: #bbb; }
    .tag-cloud { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; }
    .tag-item { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 5px 15px; border-radius: 20px; color: #eee; font-size: 0.9rem; text-decoration: none; transition: 0.3s; }
    .tag-item:hover { background: var(--accent-red); color: #fff; border-color: var(--accent-red); }
</style>
@endsection

@section('content')
    <div class="container py-5" style="margin-top: 60px;">
        <article style="max-width: 900px; margin: 0 auto;">
            <div class="text-center mb-5">
                <span style="color: var(--accent-red); font-family: 'Bebas Neue', sans-serif; font-size: 1.2rem; letter-spacing: 2px;">
                    JOURNAL // {{ $zine->published_at->format('F d, Y') }}
                </span>
                <h1 style="font-family: 'Bebas Neue', sans-serif; font-size: 4rem; margin: 1rem 0; line-height: 1;">{{ $zine->title }}</h1>
                <p class="text-muted">Words by <span class="text-white">{{ $zine->author->name }}</span></p>
            </div>

            @if($zine->banner_url)
                <img src="{{ asset($zine->banner_url) }}" class="zine-banner" alt="{{ $zine->title }}">
            @endif

            <div class="zine-content">
                {!! nl2br(e($zine->content)) !!}
            </div>

            <div class="tag-cloud">
                @foreach($zine->bands as $band)
                    <a href="{{ route('public.band.show', $band->slug) }}" class="tag-item"><i class="fa-solid fa-guitar me-1"></i> {{ $band->name }}</a>
                @endforeach
                @foreach($zine->releases as $release)
                    <a href="{{ route('public.release.show', [$release->band->slug, $release->slug]) }}" class="tag-item"><i class="fa-solid fa-compact-disc me-1"></i> {{ $release->title }}</a>
                @endforeach
                @foreach($zine->labels as $label)
                    <a href="{{ route('public.label.show', $label->slug) }}" class="tag-item"><i class="fa-solid fa-building me-1"></i> {{ $label->name }}</a>
                @endforeach
                @foreach($zine->organizers as $org)
                    <a href="#" class="tag-item"><i class="fa-solid fa-user-star me-1"></i> {{ $org->name }}</a>
                @endforeach
            </div>

            <hr class="my-5" style="opacity: 0.1;">

            <div class="comments-section">
                <h3 style="font-family: 'Bebas Neue', sans-serif; font-size: 2rem; margin-bottom: 2rem;">REACTIONS ({{ $zine->allComments->count() }})</h3>
                
                @auth
                    <form action="{{ route('public.zine.comment', $zine->slug) }}" method="POST" class="mb-5">
                        @csrf
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" style="background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1);" rows="3" placeholder="Leave your mark..."></textarea>
                        </div>
                        <button type="submit" class="btn-outline">Post Comment</button>
                    </form>
                @else
                    <div class="alert alert-dark text-center py-4 mb-5" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                        <p class="mb-0">Please <a href="{{ route('login') }}" class="text-white fw-bold">Login</a> to join the discussion.</p>
                    </div>
                @endauth

                @foreach($zine->comments as $comment)
                    <div class="comment-card">
                        <span class="comment-author">{{ $comment->user->name }}</span>
                        <div class="comment-text">{{ $comment->comment }}</div>
                        <div class="text-muted mt-2" style="font-size: 0.8rem;">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        </article>
    </div>
@endsection
