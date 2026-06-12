@extends('layouts.header')

@section('customcss')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-1">Selamat Datang, {{ auth()->user()->name }}! &#128075;</h5>
                        <p class="card-subtitle mb-3">Ringkasan Operasional MixtapeSide</p>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="text-primary mb-0">{{ $counts['bands'] }} Bands</h4>
                                <p class="mb-0">Telah terdaftar di sistem</p>
                            </div>
                            <img src="{{ asset('assets/img/illustrations/trophy.png') }}"
                                class="position-absolute bottom-0 end-0 me-4" height="100" alt="Ringkasan Pendapatan" />
                        </div>
                        <a href="{{ route('public.band.index') }}" class="btn btn-sm btn-primary">Lihat Web Publik</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded-3">
                                    <i class="ri-disc-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">{{ $counts['releases'] }}</h5>
                            <p>Total Releases</p>
                            <div class="badge bg-label-secondary rounded-pill">Tracked</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                    <i class="ri-building-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">{{ $counts['labels'] }}</h5>
                            <p>Total Labels</p>
                            <div class="badge bg-label-secondary rounded-pill">Archived</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded-3">
                                    <i class="ri-calendar-event-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">{{ $counts['gigs'] }}</h5>
                            <p>Total Gigs</p>
                            <div class="badge bg-label-secondary rounded-pill">Active</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded-3">
                                    <i class="ri-shield-user-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">{{ $counts['pending_verifications'] }}</h5>
                            <p>Verifikasi</p>
                            <div class="badge bg-label-warning rounded-pill">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6">
            <div class="col-lg-8 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Log Aktivitas Terbaru</h5>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped table-border-bottom-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log->user->name }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <h5 class="mb-1">Statistik Kontribusi</h5>
                    </div>
                    <div class="card-body">
                        <div id="monthlySummaryChart" style="min-height: 200px;"></div>
                        <p class="mt-4 text-center">Data aktivitas 7 hari terakhir.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        const ctx = document.getElementById('monthlySummaryChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['H-6', 'H-5', 'H-4', 'H-3', 'H-2', 'H-1', 'Hari Ini'],
                    datasets: [{
                        label: 'Aktivitas',
                        data: @json($chartData),
                        borderColor: '#666cff',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(102, 108, 255, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    </script>
@endsection
