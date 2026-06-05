<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Edit Band: {{ $item->name }}</h5>
    </div>
    <div class="card-body">
        <form id="formupdate" action="{{ $url['update'] }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('management.band.form')
            <div class="mt-6">
                <button type="submit" class="btn btn-primary me-2">Update Band</button>
            </div>
        </form>
    </div>
</div>
