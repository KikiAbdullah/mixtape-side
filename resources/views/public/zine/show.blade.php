@extends('layouts.header-public')

@section('title', (is_object($zine) ? $zine->title : $zine['title']) . ' - ZINE - MixtapeSide')

@section('content')
    <!-- Floating Capture Button -->
    <button id="capture-zine" class="btn-capture-floating" title="Share this article">
        <i class="fa-solid fa-share-nodes"></i>
    </button>

    <!-- Zine Hero -->
    <header class="band-hero"
        style="background-image: url('{{ (is_object($zine) ? $zine->banner_url : (isset($zine['banner_url']) ? $zine['banner_url'] : null)) ? asset(is_object($zine) ? $zine->banner_url : $zine['banner_url']) : 'https://images.unsplash.com/photo-1542385153-6258410523d4?q=80&w=2070&auto=format&fit=crop' }}');">
        <div class="hero-overlay"></div>
        <div class="hero-watermark">
            <i class="fa-solid fa-record-vinyl"></i>
            <span>MixtapeSide.com</span>
        </div>
        <div class="container">
            <div class="band-hero-content">
                <div class="band-logo-large" style="aspect-ratio: 1/1;">
                    @if (is_object($zine) ? $zine->thumbnail_url : (isset($zine['thumbnail_url']) ? $zine['thumbnail_url'] : null))
                        <img src="{{ asset(is_object($zine) ? $zine->thumbnail_url : $zine['thumbnail_url']) }}"
                            alt="{{ is_object($zine) ? $zine->title : $zine['title'] }}" />
                    @else
                        <div class="no-cover-placeholder"
                            style="display: flex; height: 100%; align-items: center; justify-content: center; background: #111; color: #333;">
                            <i class="fa-solid fa-newspaper fa-5x"></i>
                        </div>
                    @endif
                </div>
                <div class="band-header-info">
                    <span class="band-type-label">JOURNAL //
                        {{ is_object($zine) && $zine->published_at instanceof \Carbon\Carbon ? $zine->published_at->format('d F Y') : (isset($zine->published_at) ? (is_string($zine->published_at) ? $zine->published_at : $zine->published_at->format('d F Y')) : date('d F Y')) }}</span>
                    <h1 class="band-name-huge">{{ is_object($zine) ? $zine->title : $zine['title'] }}</h1>

                    <div class="band-quick-meta">
                        <span><i class="fa-solid fa-user-pen"></i> Words by
                            {{ is_object($zine->author) ? $zine->author->name : $zine['author']['name'] ?? 'Editor' }}</span>
                    </div>

                    <div class="band-hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ count($zine->comments ?? []) }}</span>
                            <span class="hero-stat-label">REACTIONS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">{{ count($zine->bands ?? []) }}</span>
                            <span class="hero-stat-label">BANDS</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-value">ZINE</span>
                            <span class="hero-stat-label">CULTURE</span>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3" data-html2canvas-ignore>
                        <button onclick="document.getElementById('tab-article').scrollIntoView({behavior: 'smooth'})"
                            class="btn-outline" style="padding: 10px 25px;">Read Article</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Zine Capture Area (Dedicated for Instagram Stories 9:16) -->
    <div id="zine-capture-area"
        style="position: absolute; left: -9999px; top: 0; width: 1080px; height: 1920px; background: #080808; overflow: hidden; display: block;">
        @php
            $bannerUrl = (is_object($zine)
                    ? $zine->banner_url
                    : (isset($zine['banner_url'])
                        ? $zine['banner_url']
                        : null))
                ? asset(is_object($zine) ? $zine->banner_url : $zine['banner_url'])
                : 'https://images.unsplash.com/photo-1542385153-6258410523d4?q=80&w=2070&auto=format&fit=crop';
            $thumbUrl = (is_object($zine)
                    ? $zine->thumbnail_url
                    : (isset($zine['thumbnail_url'])
                        ? $zine['thumbnail_url']
                        : null))
                ? asset(is_object($zine) ? $zine->thumbnail_url : $zine['thumbnail_url'])
                : null;
            $title = is_object($zine) ? $zine->title : $zine['title'];
            $authorName = is_object($zine->author) ? $zine->author->name : $zine['author']['name'] ?? 'Editor';
            $date =
                is_object($zine) && $zine->published_at instanceof \Carbon\Carbon
                    ? $zine->published_at->format('d F Y')
                    : (isset($zine->published_at)
                        ? (is_string($zine->published_at)
                            ? $zine->published_at
                            : $zine->published_at->format('d F Y'))
                        : date('d F Y'));
        @endphp

        <img src="{{ $bannerUrl }}"
            style="position: absolute; top: 0; left: 50%; height: 100%; width: auto; transform: translateX(-50%); filter: brightness(0.7);">
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.8) 100%);">
        </div>

        <!-- Safe Zone Content (1080x1350 centered in 1080x1920) -->
        <div
            style="position: absolute; top: 285px; height: 1350px; left: 0; right: 0; z-index: 10; display: flex; flex-direction: column; justify-content: space-between; padding: 80px;">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h4
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 42px; color: #fff; margin: 0; letter-spacing: 4px;">
                        MIXTAPESIDE</h4>
                    <span
                        style="font-family: 'Inter', sans-serif; font-size: 16px; color: #ff3e3e; font-weight: 800; letter-spacing: 6px;">JOURNAL
                        // VOL. {{ date('Y') }}</span>
                </div>
                <div
                    style="background: #fff; color: #000; padding: 8px 20px; font-family: 'Bebas Neue', sans-serif; font-size: 24px; transform: rotate(5deg); box-shadow: 8px 8px 0px #ff3e3e;">
                    ISSUE #{{ is_object($zine) ? $zine->id ?? '001' : '001' }}
                </div>
            </div>

            <div
                style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px 0;">
                @if ($thumbUrl)
                    <div
                        style="width: 550px; height: 550px; border: 15px solid #fff; box-shadow: 25px 25px 0px rgba(0,0,0,0.6); transform: rotate(-3deg); margin-bottom: 60px; overflow: hidden; background: #222;">
                        <img src="{{ $thumbUrl }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif

                <div style="max-width: 900px;">
                    <h1
                        style="font-family: 'Bebas Neue', sans-serif; font-size: 120px; color: #fff; line-height: 1.0; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        {{ $title }}
                    </h1>
                </div>

                <div
                    style="margin-top: 50px; font-family: 'Permanent Marker', cursive; font-size: 42px; color: #fff; transform: rotate(1deg); background: #ff3e3e; padding: 10px 35px;">
                    WORDS BY {{ $authorName }}
                </div>

                <div
                    style="margin-top: 60px; border: 2px solid rgba(255,255,255,0.3); padding: 15px 35px; border-radius: 100px;">
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 14px; color: #fff; margin: 0; letter-spacing: 8px; text-transform: uppercase;">
                        READ THE FULL STORY</p>
                </div>
            </div>

            <div
                style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 3px dashed rgba(255,255,255,0.4); padding-top: 40px;">
                <div style="color: #fff;">
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 14px; margin: 0; opacity: 0.8; text-transform: uppercase; letter-spacing: 2px;">
                        Published on</p>
                    <p style="font-family: 'Bebas Neue', sans-serif; font-size: 32px; margin: 0; color: #ff3e3e;">
                        {{ $date }}</p>
                </div>
                <div style="text-align: right;">
                    <p
                        style="font-family: 'Permanent Marker', cursive; font-size: 28px; color: #fff; margin: 0; text-shadow: 2px 2px 0px #ff3e3e;">
                        MixtapeSide.com</p>
                    <p
                        style="font-family: 'Inter', sans-serif; font-size: 12px; color: #fff; margin: 0; opacity: 0.6; letter-spacing: 2px;">
                        SCENE // CULTURE // UNDERGROUND</p>
                </div>
            </div>
        </div>
        <!-- Texture Overlay -->
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'); opacity: 0.3; pointer-events: none;">
        </div>
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, transparent 40%, rgba(0,0,0,0.6) 100%); pointer-events: none;">
        </div>
    </div>

    <section class="profile-tabs-section container">
        <div class="tabs-wrapper">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="article">Article</button>
                <button class="tab-btn" data-tab="reactions">Reactions ({{ count($zine->comments ?? []) }})</button>
                <button class="tab-btn" data-tab="tags">Related Tags</button>
            </div>

            <!-- Article Tab -->
            <div class="tab-content active" id="tab-article">
                <div class="zine-container">
                    <div class="zine-body" style="font-size: 1.25rem; line-height: 1.9; color: #ccc; font-weight: 300;">
                        <style>
                            .zine-body p {
                                margin-bottom: 2rem;
                            }

                            .zine-body p:first-of-type::first-letter {
                                font-size: 4.5rem;
                                float: left;
                                margin-right: 15px;
                                font-family: 'Bebas Neue', sans-serif;
                                line-height: 0.8;
                                color: var(--accent-red);
                                margin-top: 10px;
                            }

                            .zine-body h2,
                            .zine-body h3 {
                                font-family: 'Bebas Neue', sans-serif;
                                color: #fff;
                                margin-top: 3.5rem;
                                margin-bottom: 1.5rem;
                                letter-spacing: 1px;
                            }

                            .zine-body blockquote {
                                border-left: 4px solid var(--accent-red);
                                padding: 20px 30px;
                                background: rgba(255, 77, 77, 0.03);
                                font-style: italic;
                                font-size: 1.4rem;
                                color: #eee;
                                margin: 3rem 0;
                            }
                        </style>
                        @if (isset($zine->is_dummy) || (is_object($zine) && !isset($zine->id)))
                            <p>The city's underground scene has always been a breeding ground for raw talent and unfiltered
                                expression. Recently, we've witnessed a significant resurgence in the post-punk movement,
                                characterized by its brooding basslines, angular guitars, and lyrics that delve into the
                                complexities of urban life.</p>

                            <h2>The Sound of the City</h2>
                            <p>What makes this new wave of local post-punk so compelling is its ability to blend classic
                                influences with a contemporary edge. Bands are drawing inspiration from the pioneering
                                sounds of the late 70s and early 80s—think Joy Division, The Cure, and Gang of Four—while
                                infusing their music with modern production techniques and a unique local perspective.</p>

                            <blockquote>
                                "It's not just about replicating a vintage sound; it's about capturing a mood that resonates
                                with our generation. There's a shared sense of urgency and a desire to create something
                                meaningful in a world that often feels chaotic."
                            </blockquote>
                        @else
                            {!! nl2br(e(is_object($zine) ? $zine->content : $zine['content'])) !!}
                        @endif
                    </div>

                    <div class="share-box"
                        style="background: #111; padding: 30px; border-radius: 8px; margin: 4rem 0; text-align: center;">
                        <h4 style="font-family: 'Bebas Neue', sans-serif; margin-bottom: 1.5rem; color: #888;">Spread the
                            word</h4>
                        <div class="share-links" style="display: flex; justify-content: center; gap: 15px;">
                            <a href="#" class="share-btn"
                                style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #222; color: #fff;"><i
                                    class="fa-brands fa-x-twitter"></i></a>
                            <a href="#" class="share-btn"
                                style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #222; color: #fff;"><i
                                    class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="share-btn"
                                style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #222; color: #fff;"><i
                                    class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reactions Tab -->
            <div class="tab-content" id="tab-reactions">
                <div class="zine-container">
                    @auth
                        <form
                            action="{{ route('public.zine.comment', (is_object($zine) ? $zine->slug : $zine['slug']) ?? '#') }}"
                            method="POST" class="mb-5">
                            @csrf
                            <div class="mb-4">
                                <textarea name="comment" class="form-control"
                                    style="background: #111; color: #fff; border: 1px solid #333; padding: 1.5rem; border-radius: 8px;" rows="4"
                                    placeholder="Join the conversation..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger px-5 py-3"
                                style="background: var(--accent-red); border: none; font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px; font-size: 1.1rem;">Post
                                Reaction</button>
                        </form>
                    @else
                        <div class="alert alert-dark text-center py-4 mb-5"
                            style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                            <p class="mb-0 text-muted">You must <a href="{{ route('login') }}"
                                    class="text-white fw-bold">Login</a> to leave a reaction.</p>
                        </div>
                    @endauth

                    @forelse($zine->comments ?? [] as $comment)
                        <div class="comment-item"
                            style="background: #111; padding: 25px; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #1a1a1a;">
                            <div class="comment-user"
                                style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                <div class="user-avatar"
                                    style="width: 40px; height: 40px; border-radius: 50%; background: #333; display: flex; align-items: center; justify-content: center; font-family: 'Bebas Neue', sans-serif;">
                                    {{ substr($comment->user->name, 0, 1) }}</div>
                                <div class="user-info">
                                    <b
                                        style="color: #fff; display: block; font-size: 1rem;">{{ $comment->user->name }}</b>
                                    <span
                                        style="font-size: 0.8rem; color: #666;">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="comment-text" style="color: #aaa; line-height: 1.6;">{{ $comment->comment }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5" style="color: #444;">
                            <i class="ri-chat-3-line ri-4x mb-3"></i>
                            <p>No reactions yet. Be the first to react!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tags Tab -->
            <div class="tab-content" id="tab-tags">
                <div class="zine-container">
                    <h4 class="tab-sub-title">Related Bands</h4>
                    <div class="premium-grid">
                        @forelse($zine->bands ?? [] as $band)
                            <div class="premium-card">
                                <a href="{{ route('public.band.show', $band->slug) }}" class="card-image-wrapper">
                                    <div class="no-cover-placeholder"
                                        style="display: flex; height: 100%; align-items: center; justify-content: center; background: #222; color: #444;">
                                        <i class="fa-solid fa-guitar fa-4x"></i>
                                    </div>
                                </a>
                                <div class="card-content">
                                    <h3 class="card-title"><a
                                            href="{{ route('public.band.show', $band->slug) }}">{{ $band->name }}</a>
                                    </h3>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No specific bands tagged.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
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

        $('#capture-zine').on('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

            // Target the dedicated capture area
            const element = document.querySelector('#zine-capture-area');

            // Move it into view temporarily for html2canvas to work reliably
            const originalStyle = element.style.cssText;
            element.style.left = '0';
            element.style.top = '0';
            element.style.zIndex = '-1000';

            setTimeout(() => {
                html2canvas(element, {
                    useCORS: true,
                    allowTaint: false,
                    backgroundColor: '#080808',
                    scale: 1, // 1x is enough for 1080x1920
                    width: 1080,
                    height: 1920,
                    logging: false
                }).then(canvas => {
                    // Restore original style
                    element.style.cssText = originalStyle;

                    const link = document.createElement('a');
                    link.download =
                        'zine-{{ is_object($zine) ? $zine->slug : $zine['slug'] }}-mixtapeside.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                    $btn.html(originalHtml).prop('disabled', false);
                });
            }, 500);
        });
    </script>
@endsection
