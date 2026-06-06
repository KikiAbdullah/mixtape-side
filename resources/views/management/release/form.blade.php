<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Band</label>
        <select name="band_id" class="form-control select-remote-band" required>
            @if(isset($item) && $item->band)
                <option value="{{ $item->band_id }}" selected>{{ $item->band->name }}</option>
            @endif
        </select>
    </div>

    <div class="col-md-8">
        <label class="form-label">Release Title</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. Beyond the Self" value="{{ $item->title ?? '' }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" placeholder="beyond-the-self" value="{{ $item->slug ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Release Type</label>
        <select name="release_type" class="form-select select">
            <option value="Full-length" {{ (isset($item) && $item->release_type == 'Full-length') ? 'selected' : '' }}>Full-length</option>
            <option value="EP" {{ (isset($item) && $item->release_type == 'EP') ? 'selected' : '' }}>EP</option>
            <option value="Single" {{ (isset($item) && $item->release_type == 'Single') ? 'selected' : '' }}>Single</option>
            <option value="Demo" {{ (isset($item) && $item->release_type == 'Demo') ? 'selected' : '' }}>Demo</option>
            <option value="Split" {{ (isset($item) && $item->release_type == 'Split') ? 'selected' : '' }}>Split</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Original Release Year</label>
        <input type="number" name="original_release_year" class="form-control" placeholder="2023" value="{{ $item->original_release_year ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Cover Artwork</label>
        <input type="file" name="cover_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->cover_url)
            <div class="mt-2">
                <img src="{{ asset($item->cover_url) }}" alt="Cover" class="rounded border" height="50">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Release Banner</label>
        <input type="file" name="banner_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->banner_url)
            <div class="mt-2">
                <img src="{{ asset($item->banner_url) }}" alt="Banner" class="rounded border" height="50">
            </div>
        @endif
    </div>

    <div class="col-md-12">
        <label class="form-label">Description / Liner Notes</label>
        <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
    </div>
</div>
