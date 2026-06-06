<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" placeholder="Article title" value="{{ $item->title ?? '' }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Thumbnail (Square/Small)</label>
        <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->thumbnail_url)
            <img src="{{ asset($item->thumbnail_url) }}" class="mt-2 rounded" height="50">
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Banner (Wide/Large)</label>
        <input type="file" name="banner_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->banner_url)
            <img src="{{ asset($item->banner_url) }}" class="mt-2 rounded" height="50">
        @endif
    </div>

    <div class="col-md-12">
        <label class="form-label">Content</label>
        <textarea name="content" class="form-control" rows="10" required>{{ $item->content ?? '' }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select select">
            <option value="Draft" {{ (isset($item) && $item->status == 'Draft') ? 'selected' : '' }}>Draft</option>
            <option value="Published" {{ (isset($item) && $item->status == 'Published') ? 'selected' : '' }}>Published</option>
        </select>
    </div>

    <hr>
    <div class="col-md-12">
        <h6 class="mb-2">Tags (Connect to Archive)</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Related Bands/Artists</label>
                <select name="band_ids[]" class="form-control select-remote-band" multiple>
                    @if(isset($item))
                        @foreach($item->bands as $band)
                            <option value="{{ $band->id }}" selected>{{ $band->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Related Releases</label>
                <select name="release_ids[]" class="form-control select-remote-release" multiple>
                    @if(isset($item))
                        @foreach($item->releases as $release)
                            <option value="{{ $release->id }}" selected>{{ $release->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Related Labels</label>
                <select name="label_ids[]" class="form-control select-remote-label" multiple>
                    @if(isset($item))
                        @foreach($item->labels as $label)
                            <option value="{{ $label->id }}" selected>{{ $label->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Related Organizers</label>
                <select name="organizer_ids[]" class="form-control select-remote-organizer" multiple>
                    @if(isset($item))
                        @foreach($item->organizers as $org)
                            <option value="{{ $org->id }}" selected>{{ $org->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select').select2();
        SelectRemoteData('.select-remote-band', '{{ route('select.bands') }}');
        SelectRemoteData('.select-remote-release', '{{ route('select.releases') }}');
        SelectRemoteData('.select-remote-label', '{{ route('select.labels') }}');
        SelectRemoteData('.select-remote-organizer', '{{ route('select.organizers') }}');
    });
</script>
