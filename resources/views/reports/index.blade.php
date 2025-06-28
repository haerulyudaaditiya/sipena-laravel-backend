@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan & Ekspor Data</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Laporan Kehadiran -->
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i>Laporan Kehadiran</h3></div>
                <div class="card-body">
                    <p>Pilih rentang tanggal untuk mengunduh laporan kehadiran dalam format Excel.</p>
                    <form action="{{ route('reports.export.attendance') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-5 form-group"><label>Tanggal Mulai</label><input type="date" name="start_date" class="form-control" required></div>
                            <div class="col-md-5 form-group"><label>Tanggal Selesai</label><input type="date" name="end_date" class="form-control" required></div>
                            <div class="col-md-2 form-group"><button type="submit" class="btn btn-primary btn-block"><i class="fas fa-download mr-1"></i> Ekspor</button></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DIUBAH: Laporan Cuti -->
            <div class="card card-info card-outline">
                 <div class="card-header"><h3 class="card-title"><i class="fas fa-plane-departure mr-1"></i>Laporan Cuti</h3></div>
                <div class="card-body">
                    <p>Pilih rentang tanggal untuk mengunduh rekapitulasi pengajuan cuti dalam format Excel.</p>
                     <form action="{{ route('reports.export.leave') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-5 form-group"><label>Tanggal Mulai</label><input type="date" name="start_date" class="form-control" required></div>
                            <div class="col-md-5 form-group"><label>Tanggal Selesai</label><input type="date" name="end_date" class="form-control" required></div>
                            <div class="col-md-2 form-group"><button type="submit" class="btn btn-info btn-block"><i class="fas fa-download mr-1"></i> Ekspor</button></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DIUBAH: Laporan Gaji -->
             <div class="card card-success card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-wallet mr-1"></i>Laporan Gaji Bulanan</h3></div>
                <div class="card-body">
                    <p>Pilih bulan dan tahun untuk mengunduh rekapitulasi gaji dalam format Excel.</p>
                    <form action="{{ route('reports.export.salary') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-5 form-group">
                                <label>Pilih Bulan</label>
                                <select name="month" class="form-control" required>
                                    @for ($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m, 1, date('Y'))) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-5 form-group">
                                <label>Pilih Tahun</label>
                                <select name="year" class="form-control" required>
                                    @for ($y=date('Y'); $y>=date('Y')-5; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 form-group"><button type="submit" class="btn btn-success btn-block"><i class="fas fa-download mr-1"></i> Ekspor</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
