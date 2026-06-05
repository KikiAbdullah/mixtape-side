<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Add New Release</h5>
    </div>
    <div class="card-body">
        <form id="dform" action="{{ $url['store'] }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('management.release.form')
            <div class="mt-6">
                <button type="submit" class="btn btn-primary me-2">Save Release</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
            </div>
        </form>
    </div>
</div>
