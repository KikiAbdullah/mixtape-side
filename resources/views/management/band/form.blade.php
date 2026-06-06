<div class="row g-4">
    <!-- Basic Info -->
    <div class="col-md-8">
        <label class="form-label">Band Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Burgerkill" value="{{ $item->name ?? '' }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug (URL)</label>
        <input type="text" name="slug" class="form-control" placeholder="burgerkill-official" value="{{ $item->slug ?? '' }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Alternative Names (comma separated)</label>
        <input type="text" name="alternative_names" class="form-control" placeholder="BK, Scumbag" value="{{ isset($item->alternative_names) ? (is_array($item->alternative_names) ? implode(', ', $item->alternative_names) : $item->alternative_names) : '' }}">
    </div>

    <!-- Media -->
    <div class="col-md-4">
        <label class="form-label">Band Logo (Square)</label>
        <input type="file" name="logo_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->logo_url)
            <div class="mt-2">
                <img src="{{ asset($item->logo_url) }}" alt="Logo" class="rounded border" height="50">
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Band Banner (Wide)</label>
        <input type="file" name="banner_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->banner_url)
            <div class="mt-2">
                <img src="{{ asset($item->banner_url) }}" alt="Banner" class="rounded border" height="50">
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Band Photo (Press Kit)</label>
        <input type="file" name="photo_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->photo_url)
            <div class="mt-2">
                <img src="{{ asset($item->photo_url) }}" alt="Photo" class="rounded border" height="50">
            </div>
        @endif
    </div>

    <!-- Origin & Time -->
    <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" placeholder="e.g. Bandung" value="{{ $item->city ?? '' }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Country</label>
        <input type="text" name="country" class="form-control" placeholder="e.g. Indonesia" value="{{ $item->country ?? 'Indonesia' }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select select" id="status-select">
            <option value="Active" {{ (isset($item) && $item->status == 'Active') ? 'selected' : '' }}>Active</option>
            <option value="On Hold" {{ (isset($item) && $item->status == 'On Hold') ? 'selected' : '' }}>On Hold</option>
            <option value="Split-up" {{ (isset($item) && $item->status == 'Split-up') ? 'selected' : '' }}>Split-up</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Formed Year</label>
        <input type="number" name="formed_year" class="form-control" placeholder="1995" value="{{ $item->formed_year ?? '' }}">
    </div>
    <div class="col-md-6" id="disbanded-container" style="{{ (isset($item) && $item->status == 'Split-up') ? '' : 'display:none;' }}">
        <label class="form-label">Disbanded Year</label>
        <input type="number" name="disbanded_year" class="form-control" placeholder="2020" value="{{ $item->disbanded_year ?? '' }}">
    </div>

    <!-- Musical Info -->
    <div class="col-md-12">
        <label class="form-label">Genres (comma separated)</label>
        <input type="text" name="genre" class="form-control" placeholder="Metalcore, Hardcore" value="{{ isset($item->genre) ? (is_array($item->genre) ? implode(', ', $item->genre) : $item->genre) : '' }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Biography</label>
        <textarea name="biography" class="form-control" rows="5">{{ $item->biography ?? '' }}</textarea>
    </div>

    <!-- Social Links -->
    <div class="col-md-12">
        <label class="form-label fw-bold">Social Links</label>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="ri-instagram-line"></i></span>
                    <input type="text" name="social_links[instagram]" class="form-control" placeholder="Instagram URL" value="{{ $item->social_links['instagram'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="ri-facebook-box-line"></i></span>
                    <input type="text" name="social_links[facebook]" class="form-control" placeholder="Facebook URL" value="{{ $item->social_links['facebook'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="ri-spotify-line"></i></span>
                    <input type="text" name="social_links[spotify]" class="form-control" placeholder="Spotify URL" value="{{ $item->social_links['spotify'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#status-select').off('change').on('change', function() {
            if ($(this).val() === 'Split-up') {
                $('#disbanded-container').show();
            } else {
                $('#disbanded-container').hide();
            }
        });
    });
</script>
