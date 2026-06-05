<div class="row g-4">
    <div class="col-md-8">
        <label class="form-label">Organizer Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Atap Promotions" value="{{ $item->name ?? '' }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" placeholder="atap-promotions" value="{{ $item->slug ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" placeholder="e.g. Bandung" value="{{ $item->city ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Contact Info</label>
        <input type="text" name="contact_info" class="form-control" placeholder="Email or Phone" value="{{ $item->contact_info ?? '' }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Logo</label>
        <input type="file" name="logo_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->logo_url)
            <div class="mt-2">
                <img src="{{ asset($item->logo_url) }}" alt="Logo" class="rounded border" height="50">
            </div>
        @endif
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
    </div>
</div>
