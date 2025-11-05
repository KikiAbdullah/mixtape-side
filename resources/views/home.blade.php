@extends('layouts.header')

@section('customcss')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-1">Selamat Datang, Admin! &#128075;</h5>
                        <p class="card-subtitle mb-3">Ringkasan Operasional Hari Ini</p>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="text-primary mb-0">Rp 4.5 Juta</h4>
                                <p class="mb-0">Pendapatan Hari Ini</p>
                            </div>
                            <img src="{{ asset('assets/img/illustrations/trophy.png') }}"
                                class="position-absolute bottom-0 end-0 me-4" height="100" alt="Ringkasan Pendapatan" />
                        </div>
                        <a href="javascript:;" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded-3">
                                    <i class="ri-car-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">25</h5>
                            <p>Mobil Tersedia</p>
                            <div class="badge bg-label-secondary rounded-pill">Dari 30</div>
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
                                    <i class="ri-calendar-check-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">8</h5>
                            <p>Penyewaan Hari Ini</p>
                            <div class="badge bg-label-secondary rounded-pill">Terbaru</div>
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
                                    <i class="ri-user-add-line ri-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-5">
                            <h5 class="mb-1">12</h5>
                            <p>Pelanggan Baru</p>
                            <div class="badge bg-label-secondary rounded-pill">Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6">
            <div class="col-lg-8 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Pemesanan Mendatang</h5>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-1" type="button"
                                id="upcomingBookingsDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ri-more-2-line ri-20px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="upcomingBookingsDropdown">
                                <a class="dropdown-item" href="javascript:void(0);">Lihat Semua</a>
                                <a class="dropdown-item" href="javascript:void(0);">Minggu Ini</a>
                                <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped table-border-bottom-0">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Mobil</th>
                                    <th>Tanggal Ambil</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Andi Pratama</td>
                                    <td>Toyota Avanza</td>
                                    <td>23 Juli 2025</td>
                                    <td><span class="badge bg-label-info">Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td>Budi Santoso</td>
                                    <td>Honda Brio</td>
                                    <td>24 Juli 2025</td>
                                    <td><span class="badge bg-label-success">Dikonfirmasi</span></td>
                                </tr>
                                <tr>
                                    <td>Citra Dewi</td>
                                    <td>Mitsubishi Pajero</td>
                                    <td>24 Juli 2025</td>
                                    <td><span class="badge bg-label-warning">Perlu Konfirmasi</span></td>
                                </tr>
                                <tr>
                                    <td>Doni Kusuma</td>
                                    <td>Suzuki Ertiga</td>
                                    <td>25 Juli 2025</td>
                                    <td><span class="badge bg-label-success">Dikonfirmasi</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Ringkasan Bulan Ini</h5>
                            <div class="dropdown">
                                <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-1" type="button"
                                    id="monthlySummaryDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="ri-more-2-line ri-20px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlySummaryDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Bulan Lalu</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="monthlySummaryChart" style="min-height: 200px;">
                            {{-- Placeholder for a simple chart, e.g., bar chart of rentals per week --}}
                        </div>
                        <p class="mt-4 text-center">Total <strong>120</strong> penyewaan selesai bulan ini.</p>
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
                type: 'bar', // or 'line', 'pie'
                data: {
                    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                    datasets: [{
                        label: 'Jumlah Penyewaan',
                        data: [30, 45, 25, 20], // Contoh data
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(54, 162, 235, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>
@endsection
