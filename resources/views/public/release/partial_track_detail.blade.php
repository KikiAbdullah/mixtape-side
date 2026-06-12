<div class="track-detail-content">
    <!-- Track Header Info -->
    <div class="track-info-header mb-5">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="track-number-badge">
                    {{ str_pad($track->track_number, 2, '0', STR_PAD_LEFT) }}
                </div>
            </div>
            <div class="col">
                <h2 class="track-title-main">{{ $track->title }}</h2>
                <div class="track-meta-pills">
                    <span class="meta-pill"><i class="fa-regular fa-clock me-1"></i> {{ $track->duration ? sprintf('%02d:%02d', floor($track->duration / 60), $track->duration % 60) : '--:--' }}</span>
                    <span class="meta-pill"><i class="fa-solid fa-compact-disc me-1"></i> {{ $track->release->title }}</span>
                    @if($track->lyrics)
                        <span class="meta-pill pill-red"><i class="fa-solid fa-file-lines me-1"></i> LYRICS AVAILABLE</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Side: Lyrics & Translation -->
        <div class="col-md-7 border-end-custom">
            <div class="lyrics-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="section-title m-0">
                        <i class="fa-solid fa-microphone-lines me-2"></i> LYRICS
                    </h6>
                    @if($track->lyrics_translation)
                        <div class="btn-group btn-group-sm lyrics-toggle-group">
                            <button class="btn btn-outline-danger active" id="btn-original">Original</button>
                            <button class="btn btn-outline-danger" id="btn-translate">Translation</button>
                        </div>
                    @endif
                </div>

                <div class="lyrics-container">
                    <div class="lyrics-box" id="lyrics-original">
                        @if($track->lyrics)
                            {{ $track->lyrics }}
                        @else
                            <div class="no-data-placeholder">
                                <i class="fa-solid fa-quote-left fa-2x mb-3 opacity-25"></i>
                                <p>Lyrics haven't been archived for this track yet.</p>
                            </div>
                        @endif
                    </div>
                    @if($track->lyrics_translation)
                        <div class="lyrics-box" id="lyrics-translation" style="display: none; font-style: italic; color: #999;">
                            {{ $track->lyrics_translation }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Credits & Meta -->
        <div class="col-md-5">
            <!-- Credits -->
            <div class="contributors-section mb-5">
                <h6 class="section-title">
                    <i class="fa-solid fa-users-gear me-2"></i> CREDITS & PERSONNEL
                </h6>
                <div class="contributors-list">
                    @forelse($track->contributors as $contrib)
                        <div class="contributor-card-premium">
                            <div class="contrib-main">
                                <span class="contrib-name">{{ $contrib->name }}</span>
                                <span class="contrib-role">{{ $contrib->role }}</span>
                            </div>
                            @if ($contrib->notes)
                                <div class="contrib-notes-premium">
                                    {{ $contrib->notes }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="no-data-placeholder-small">
                            <p>No specific credits found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Share / Actions -->
            <div class="track-actions-section">
                <h6 class="section-title">
                    <i class="fa-solid fa-share-nodes me-2"></i> SPREAD THE NOISE
                </h6>
                <div class="d-grid gap-2">
                    <button class="btn-action-premium" onclick="copyTrackLink('{{ $track->id }}')">
                        <i class="fa-solid fa-link me-2"></i> COPY TRACK LINK
                    </button>
                    <button class="btn-action-premium" onclick="shareOnTwitter('{{ $track->title }}', '{{ url()->current() }}')">
                        <i class="fa-brands fa-twitter me-2"></i> SHARE ON TWITTER
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Track Info Header */
    .track-number-badge {
        width: 60px;
        height: 60px;
        background: #ff3e3e;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Bebas Neue', sans-serif;
        font-size: 32px;
        border-radius: 4px;
        box-shadow: 4px 4px 0px rgba(255, 255, 255, 0.1);
    }

    .track-title-main {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 2.5rem;
        color: white;
        margin: 0;
        line-height: 1;
        letter-spacing: 1px;
    }

    .track-meta-pills {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .meta-pill {
        font-size: 11px;
        background: rgba(255, 255, 255, 0.05);
        color: #888;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .meta-pill.pill-red {
        background: rgba(255, 62, 62, 0.1);
        color: #ff3e3e;
        border: 1px solid rgba(255, 62, 62, 0.2);
    }

    /* Layout & Sections */
    .border-end-custom {
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        padding-right: 30px;
    }

    .section-title {
        color: #ff3e3e;
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.2rem;
        letter-spacing: 2px;
        margin-bottom: 1.5rem;
        display: inline-flex;
        align-items: center;
        border-left: 3px solid #ff3e3e;
        padding-left: 15px;
    }

    /* Lyrics Container */
    .lyrics-box {
        max-height: 500px;
        overflow-y: auto;
        font-family: 'Inter', sans-serif;
        line-height: 1.8;
        color: #eee;
        white-space: pre-wrap;
        font-size: 1.05rem;
        padding: 20px;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* Contributors Premium */
    .contributor-card-premium {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.03) 0%, transparent 100%);
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 10px;
        border-left: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .contributor-card-premium:hover {
        background: linear-gradient(90deg, rgba(255, 62, 62, 0.05) 0%, transparent 100%);
        border-left-color: #ff3e3e;
        transform: translateX(5px);
    }

    .contrib-name {
        display: block;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .contrib-role {
        display: block;
        color: #ff3e3e;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
    }

    .contrib-notes-premium {
        margin-top: 8px;
        font-size: 0.8rem;
        color: #666;
        font-style: italic;
    }

    /* Action Buttons */
    .btn-action-premium {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #aaa;
        padding: 12px;
        border-radius: 4px;
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 1px;
        font-size: 1.1rem;
        transition: all 0.3s;
        text-align: center;
        width: 100%;
    }

    .btn-action-premium:hover {
        background: white;
        color: black;
        border-color: white;
    }

    /* Placeholders */
    .no-data-placeholder, .no-data-placeholder-small {
        text-align: center;
        padding: 30px;
        color: #444;
        border: 1px dashed rgba(255, 255, 255, 0.05);
        border-radius: 8px;
    }

    .no-data-placeholder-small {
        padding: 15px;
        font-size: 0.85rem;
    }

    /* Custom Scrollbar */
    .lyrics-box::-webkit-scrollbar {
        width: 4px;
    }
    .lyrics-box::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .border-end-custom {
            border-right: none;
            padding-right: 0;
            margin-bottom: 40px;
        }
    }
</style>

<script>
    // Lyrics Translation Toggle
    $('#btn-original').on('click', function() {
        $(this).addClass('active');
        $('#btn-translate').removeClass('active');
        $('#lyrics-original').show();
        $('#lyrics-translation').hide();
    });

    $('#btn-translate').on('click', function() {
        $(this).addClass('active');
        $('#btn-original').removeClass('active');
        $('#lyrics-original').hide();
        $('#lyrics-translation').show();
    });

    function copyTrackLink(id) {
        const url = window.location.origin + '/track/' + id + '/detail';
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Copied!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    function shareOnTwitter(title, url) {
        const text = encodeURIComponent(`Archived Track: ${title} on #MixtapeSide 🤘\n${url}`);
        window.open(`https://twitter.com/intent/tweet?text=${text}`, '_blank');
    }
</script>
