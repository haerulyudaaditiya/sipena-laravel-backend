@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Log Aktivitas Sistem</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Log Aktivitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Aktivitas Terbaru</h3>
                </div>
                <div class="card-body">
                    {{-- PERBAIKAN 1: Bungkus tabel dengan div ini --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Waktu</th>
                                    <th style="width: 15%;">Pengguna</th>
                                    <th style="width: 70%;">Aktivitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activityLogs as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->activity_date)->diffForHumans() }} <br> <small>{{ $log->activity_date }}</small></td>
                                        <td>{{ $log->user->name ?? 'User Dihapus' }}</td>
                                        {{-- Kita berikan class khusus di sini agar mudah ditarget oleh CSS --}}
                                        <td class="activity-cell">{{ $log->activity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada aktivitas yang tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $activityLogs->links('pagination::bootstrap-4') }}
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* PERBAIKAN 2: Tambahkan CSS ini */
        .activity-cell {
            word-wrap: break-word; /* Untuk browser lama */
            overflow-wrap: break-word; /* Standar modern, memaksa teks turun */
            word-break: break-all; /* Alternatif agresif jika teks sangat panjang tanpa spasi */
            white-space: normal !important; /* Pastikan white-space kembali normal */
        }

        .table {
            table-layout: fixed; /* Memaksa tabel mengikuti lebar kolom yang ditentukan */
            width: 100% !important;
        }

        .pagination {
            margin: 0;
        }
        .page-item .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@endpush
