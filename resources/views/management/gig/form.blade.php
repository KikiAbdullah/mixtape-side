<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Organizer</label>
        <select name="organizer_id" class="form-control select-remote-organizer">
            @if(isset($item) && $item->organizer)
                <option value="{{ $item->organizer_id }}" selected>{{ $item->organizer->name }}</option>
            @endif
        </select>
    </div>

    <div class="col-md-8">
        <label class="form-label">Gig Title</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. Bandung Berisik" value="{{ $item->title ?? '' }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" placeholder="bandung-berisik-2023" value="{{ $item->slug ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ $item->date ?? '' }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" class="form-control" value="{{ $item->start_time ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Venue Name</label>
        <input type="text" name="venue_name" class="form-control" placeholder="e.g. GOR Saparua" value="{{ $item->venue_name ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" placeholder="e.g. Bandung" value="{{ $item->city ?? '' }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Venue Address</label>
        <textarea name="venue_address" class="form-control" rows="2">{{ $item->venue_address ?? '' }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Ticket Price (Numeric)</label>
        <input type="number" name="ticket_price" class="form-control" placeholder="0 for Free" value="{{ $item->ticket_price ?? '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Ticket Info</label>
        <input type="text" name="ticket_info" class="form-control" placeholder="e.g. OTS 50K, Presale 35K" value="{{ $item->ticket_info ?? '' }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Event Poster</label>
        <input type="file" name="poster_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->poster_url)
            <div class="mt-2">
                <img src="{{ asset($item->poster_url) }}" alt="Poster" class="rounded border" height="50">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Event Banner</label>
        <input type="file" name="banner_file" class="form-control" accept="image/*">
        @if(isset($item) && $item->banner_url)
            <div class="mt-2">
                <img src="{{ asset($item->banner_url) }}" alt="Banner" class="rounded border" height="50">
            </div>
        @endif
    </div>
</div>
