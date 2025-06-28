@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalKaryawan }}</h3>
                            <p>Total Karyawan</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="{{ route('employees.index') }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $hadirHariIni }}</h3>
                            <p>Hadir Hari Ini</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-check"></i></div>
                        <a href="{{ route('attendances.index') }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $cutiPending }}</h3>
                            <p>Pengajuan Cuti Pending</p>
                        </div>
                        <div class="icon"><i class="fas fa-plane-departure"></i></div>
                        <a href="{{ route('leave-requests.index') }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</h3>
                            <p>Total Gaji Bulan Ini</p>
                        </div>
                        <div class="icon"><i class="fas fa-wallet"></i></div>
                        <a href="{{ route('salaries.index') }}" class="small-box-footer">Lihat Detail <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Laporan -->
            <div class="row">
                <!-- Grafik Laporan Gaji -->
                <section class="col-lg-7 connectedSortable">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Laporan Gaji (6 Bulan Terakhir)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="salaryChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Grafik Laporan Cuti -->
                <section class="col-lg-5 connectedSortable">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Tipe Cuti (Bulan Ini)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="leaveChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Laporan Pengajuan Cuti Terbaru -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pengajuan Cuti Terbaru (Menunggu Persetujuan)</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama Karyawan</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentLeaveRequests as $request)
                                        <tr>
                                            <td>{{ $request->employee->name }}</td>
                                            <td>
                                                @switch($request->type)
                                                    @case('annual')
                                                        Cuti Tahunan
                                                    @break

                                                    @case('sick')
                                                        Izin Sakit
                                                    @break

                                                    @case('personal')
                                                        Keperluan Pribadi
                                                    @break

                                                    @case('other')
                                                        Lainnya
                                                    @break

                                                    @default
                                                        Tidak Diketahui
                                                @endswitch

                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}</td>
                                            <td><span class="badge badge-warning">Menunggu Persetujuan</span></td>
                                            <td><a href="{{ route('leave-requests.index') }}"
                                                    class="btn btn-sm btn-info">Lihat</a></td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada pengajuan cuti yang menunggu.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @push('scripts')
        {{-- Import Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Inisialisasi Grafik Gaji (Bar Chart)
            const salaryCtx = document.getElementById('salaryChart').getContext('2d');
            new Chart(salaryCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($salaryLabels) !!},
                    datasets: [{
                        label: 'Total Gaji Dibayarkan',
                        data: {!! json_encode($salaryValues) !!},
                        backgroundColor: 'rgba(0, 123, 255, 0.7)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Inisialisasi Grafik Cuti (Doughnut Chart)
            const leaveCtx = document.getElementById('leaveChart').getContext('2d');
            new Chart(leaveCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($leaveLabels) !!},
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: {!! json_encode($leaveValues) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                    }]
                },
            });
        </script>
    @endpush
