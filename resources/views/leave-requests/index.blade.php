@extends('layouts.admin.admin')

@section('content')
    {{-- =================================================================================== --}}
    {{-- HEADER HALAMAN --}}
    {{-- =================================================================================== --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pengajuan Cuti</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengajuan Cuti</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================================================================== --}}
    {{-- KONTEN UTAMA (TABEL) --}}
    {{-- =================================================================================== --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Pengajuan Cuti Karyawan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('leave-requests.index') }}" method="GET" class="form-inline">
                                <div class="input-group input-group-sm" style="width: 500px;">
                                    <input type="date" name="start_date" class="form-control" title="Tanggal Mulai"
                                        value="{{ request('start_date') }}">
                                    <input type="date" name="end_date" class="form-control ml-2" title="Tanggal Selesai"
                                        value="{{ request('end_date') }}">
                                    <select name="status" class="form-control ml-2">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                            Ditolak</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <a href="{{ route('leave-requests.index') }}" class="btn btn-sm btn-secondary ml-2"
                                    title="Reset Filter">
                                    Reset
                                </a>
                            </form>
                            <table id="leave-request-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jenis Cuti</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                        <th class="no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveRequests as $index => $leaveRequest)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $leaveRequest->employee->name }}</td>
                                            <td>
                                                @switch($leaveRequest->type)
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
                                            <td>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}</td>
                                            <td>
                                                @if ($leaveRequest->status == 'pending')
                                                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                                                @elseif ($leaveRequest->status == 'approved')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#detailModal{{ $leaveRequest->id }}"
                                                    title="Lihat Detail & Validasi">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $leaveRequest->id }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-form-{{ $leaveRequest->id }}"
                                                    action="{{ route('leave-requests.destroy', $leaveRequest->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =================================================================================== --}}
    {{-- MODAL DETAIL & VALIDASI --}}
    {{-- =================================================================================== --}}
    @foreach ($leaveRequests as $leaveRequest)
        <div class="modal fade" id="detailModal{{ $leaveRequest->id }}" tabindex="-1" role="dialog"
            aria-labelledby="detailModalLabel{{ $leaveRequest->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel{{ $leaveRequest->id }}">Detail Pengajuan Cuti:
                            {{ $leaveRequest->employee->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><strong>Nama Karyawan:</strong>
                            <p class="text-muted">{{ $leaveRequest->employee->name }}</p>
                        </div>
                        <div class="form-group">
                            <strong>Jenis Cuti:</strong>
                            <p class="text-muted">
                                @switch($leaveRequest->type)
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
                            </p>
                        </div>
                        <div class="form-group"><strong>Tanggal:</strong>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }}
                                s/d {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}</p>
                        </div>
                        <div class="form-group"><strong>Kontak Selama Cuti:</strong>
                            <p class="text-muted">{{ $leaveRequest->contact ?? '-' }}</p>
                        </div>
                        <div class="form-group"><strong>Alasan Pengajuan:</strong>
                            <p class="text-muted">{{ $leaveRequest->reason }}</p>
                        </div>
                        <hr>
                        <div class="form-group">
                            <strong>Status Saat Ini:</strong>
                            @if ($leaveRequest->status == 'pending')
                                <p><span class="badge badge-warning">Menunggu Persetujuan</span></p>
                            @elseif ($leaveRequest->status == 'approved')
                                <p><span class="badge badge-success">Disetujui</span></p>
                            @else
                                <p><span class="badge badge-danger">Ditolak</span></p>
                            @endif
                        </div>

                        {{-- DITAMBAHKAN: Menampilkan alasan penolakan jika ada --}}
                        @if ($leaveRequest->status == 'rejected' && $leaveRequest->rejection_reason)
                            <div class="form-group mt-3">
                                <strong class="text-danger">Alasan Penolakan:</strong>
                                <div class="alert alert-danger mt-1">
                                    {{ $leaveRequest->rejection_reason }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        {{-- DIUBAH: Tombol aksi validasi --}}
                        @if ($leaveRequest->status == 'pending')
                            <div>
                                {{-- Tombol Tolak kini membuka modal baru untuk alasan --}}
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#rejectModal{{ $leaveRequest->id }}">Tolak</button>

                                <form action="{{ route('leave-requests.update.status', $leaveRequest->id) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- DITAMBAHKAN: Modal baru khusus untuk alasan penolakan -->
        <div class="modal fade" id="rejectModal{{ $leaveRequest->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form action="{{ route('leave-requests.update.status', $leaveRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Alasan Penolakan</h5>
                            <button type="button" class="close" data-dismiss="modal"
                                aria-label="Tutup">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="rejection_reason">Mohon berikan alasan penolakan untuk pengajuan oleh
                                    <strong>{{ $leaveRequest->employee->name }}</strong>.</label>
                                <textarea name="rejection_reason" class="form-control" rows="4" required
                                    placeholder="Contoh: Kuota cuti tidak mencukupi atau ada proyek mendesak."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @include('layouts.admin.confirmation')
@endsection

@include('layouts.admin.alert')

@push('scripts')
    <script>
        $(function() {
            $("#leave-request-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
        });
    </script>
@endpush
