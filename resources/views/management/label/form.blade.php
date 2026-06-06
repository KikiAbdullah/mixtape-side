<div class="row g-4">
    <div class="col-md-8">
        <label class="form-label">Label Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Grimloc Records" value="{{ $item->name ?? '' }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" placeholder="grimloc-records" value="{{ $item->slug ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" placeholder="e.g. Bandung" value="{{ $item->city ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select select">
            <option value="Active" {{ (isset($item) && $item->status == 'Active') ? 'selected' : '' }}>Active</option>
            <option value="Defunct" {{ (isset($item) && $item->status == 'Defunct') ? 'selected' : '' }}>Defunct</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Formed Year</label>
        <input type="number" name="formed_year" class="form-control" placeholder="2010" value="{{ $item->formed_year ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Defunct Year (if any)</label>
        <input type="number" name="defunct_year" class="form-control" value="{{ $item->defunct_year ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Label Logo</label>
        <input type="file" name="logo_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->logo_url)
            <div class="mt-2">
                <img src="{{ asset($item->logo_url) }}" alt="Logo" class="rounded border" height="50">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Label Banner</label>
        <input type="file" name="banner_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->banner_url)
            <div class="mt-2">
                <img src="{{ asset($item->banner_url) }}" alt="Banner" class="rounded border" height="50">
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Contact Email</label>
        <input type="email" name="contact_email" class="form-control" placeholder="info@label.com" value="{{ $item->contact_email ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Website URL</label>
        <input type="url" name="website_url" class="form-control" placeholder="https://label.com" value="{{ $item->website_url ?? '' }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
    </div>
</div>
