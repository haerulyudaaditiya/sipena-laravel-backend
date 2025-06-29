@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Kehadiran</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Data Kehadiran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kehadiran Karyawan</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Foto Check-in</th>
                                        <th>Foto Check-out</th>
                                        <th>Status</th>
                                        <th class="no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $index => $attendance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendance->employee->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->check_in)->format('d M Y, H:i') }}
                                            </td>
                                            <td>
                                                @if ($attendance->check_out)
                                                    {{ \Carbon\Carbon::parse($attendance->check_out)->format('d M Y, H:i') }}
                                                @else
                                                    <span class="badge badge-secondary">Belum Check-out</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- DIUBAH: Foto bisa diklik & buka di tab baru --}}
                                                @if ($attendance->check_in_photo_url)
                                                    <a href="{{ $attendance->check_in_photo_url }}" target="_blank"
                                                        title="Lihat Foto">
                                                        <img src="{{ $attendance->check_in_photo_url }}" alt="Check-in"
                                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                    </a>
                                                @else
                                                    <span class="badge badge-secondary">Tidak Ada Foto</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- DIUBAH: Foto bisa diklik & buka di tab baru --}}
                                                @if ($attendance->check_out_photo_url)
                                                    <a href="{{ $attendance->check_out_photo_url }}" target="_blank"
                                                        title="Lihat Foto">
                                                        <img src="{{ $attendance->check_out_photo_url }}" alt="Check-out"
                                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                    </a>
                                                @else
                                                    <span class="badge badge-secondary">Tidak Ada Foto</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->status === 'Tepat Waktu')
                                                    <span class="badge badge-success">{{ $attendance->status }}</span>
                                                @elseif ($attendance->status === 'Terlambat')
                                                    <span class="badge badge-warning">{{ $attendance->status }}</span>
                                                @else
                                                    <span class="badge badge-info">{{ $attendance->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editAttendanceModal{{ $attendance->id }}" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $attendance->id }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-form-{{ $attendance->id }}"
                                                    action="{{ route('attendances.destroy', $attendance->id) }}"
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

    {{-- Loop untuk Modal --}}
    @foreach ($attendances as $attendance)
        <div class="modal fade" id="editAttendanceModal{{ $attendance->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editAttendanceModalLabel{{ $attendance->id }}" aria-hidden="true">
            {{-- DIUBAH: Ukuran modal diperbesar untuk menampung 2 kolom dan peta --}}
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAttendanceModalLabel{{ $attendance->id }}">Edit Kehadiran:
                                {{ $attendance->employee->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- DIUBAH: Menggunakan grid system untuk 2 kolom --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Karyawan</label>
                                        <input type="text" class="form-control"
                                            value="{{ $attendance->employee->name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="check_in">Tanggal Masuk <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="check_in" class="form-control"
                                            value="{{ old('check_in', \Carbon\Carbon::parse($attendance->check_in)->format('Y-m-d\TH:i')) }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="check_in_location">Lokasi Check-in</label>
                                        <input type="text" name="check_in_location" class="form-control"
                                            value="{{ old('check_in_location', $attendance->check_in_location) }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_out">Tanggal Keluar</label>
                                        <input type="datetime-local" name="check_out" class="form-control"
                                            value="{{ old('check_out', $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="check_out_location">Lokasi Check-out</label>
                                        <input type="text" name="check_out_location" class="form-control"
                                            value="{{ old('check_out_location', $attendance->check_out_location) }}"
                                            readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status Kehadiran</label>
                                        <select name="status" class="form-control">
                                            <option value="Tepat Waktu"
                                                {{ $attendance->status == 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu
                                            </option>
                                            <option value="Terlambat"
                                                {{ $attendance->status == 'Terlambat' ? 'selected' : '' }}>Terlambat
                                            </option>
                                            {{-- Anda bisa menambahkan status lain di sini jika perlu --}}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- DITAMBAHKAN: Preview Foto --}}
                            <div class="row">
                                {{-- DIUBAH: Preview Foto Check-in --}}
                                <div class="col-md-6 text-center">
                                    <label>Foto Masuk</label>
                                    @if($attendance->check_in_photo_url)
                                        <a href="{{ $attendance->check_in_photo_url }}" target="_blank" title="Lihat ukuran penuh">
                                            <img src="{{ $attendance->check_in_photo_url }}" alt="Foto Check-in" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                        </a>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light" style="width: 100%; height: 200px; border-radius: 5px;">
                                            <span class="text-muted">Tidak ada foto</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- DIUBAH: Preview Foto Check-out --}}
                                <div class="col-md-6 text-center">
                                    <label>Foto Keluar</label>
                                     @if($attendance->check_out_photo_url)
                                        <a href="{{ $attendance->check_out_photo_url }}" target="_blank" title="Lihat ukuran penuh">
                                            <img src="{{ $attendance->check_out_photo_url }}" alt="Foto Check-out" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                        </a>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light" style="width: 100%; height: 200px; border-radius: 5px;">
                                            <span class="text-muted">Belum ada foto</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- DITAMBAHKAN: Placeholder untuk Peta --}}
                            <div class="form-group">
                                <label>Peta Lokasi</label>
                                {{-- Kita menggunakan data-* attributes untuk menyimpan koordinat, yang akan dibaca oleh JavaScript --}}
                                {{-- DIUBAH: Menambahkan data-attributes untuk lokasi check-out --}}
                                <div id="map-{{ $attendance->id }}" class="map-container"
                                    data-lat-in="{{ $attendance->check_in_latitude }}"
                                    data-lng-in="{{ $attendance->check_in_longitude }}"
                                    data-lat-out="{{ $attendance->check_out_latitude }}"
                                    data-lng-out="{{ $attendance->check_out_longitude }}"
                                    style="height: 300px; width: 100%; border-radius: 5px; border: 1px solid #ddd">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
    {{-- Aset untuk Leaflet JS (Peta) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // DIUBAH: Logika script diperbarui untuk menangani dua titik lokasi
        $('.modal').on('shown.bs.modal', function(event) {
            let modalId = $(this).attr('id');
            let mapId = 'map-' + modalId.replace('editAttendanceModal', '');
            let mapContainer = $('#' + mapId);

            // Cek jika map belum diinisialisasi
            if (mapContainer.length > 0 && mapContainer.data('map-init') !== true) {

                // Ambil semua data koordinat dari data attributes
                let latIn = mapContainer.data('lat-in');
                let lngIn = mapContainer.data('lng-in');
                let latOut = mapContainer.data('lat-out');
                let lngOut = mapContainer.data('lng-out');

                // Hanya lanjutkan jika setidaknya ada satu koordinat
                if ((latIn && lngIn) || (latOut && lngOut)) {
                    // Inisialisasi peta tanpa setView awal
                    let map = L.map(mapId);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    let bounds = []; // Array untuk menyimpan semua titik koordinat

                    // Tambahkan marker untuk Check-in jika ada
                    if (latIn && lngIn) {
                        let checkInPoint = [latIn, lngIn];
                        L.marker(checkInPoint).addTo(map).bindPopup('Lokasi Check-in');
                        bounds.push(checkInPoint);
                    }

                    // Tambahkan marker untuk Check-out jika ada
                    if (latOut && lngOut) {
                        let checkOutPoint = [latOut, lngOut];
                        L.marker(checkOutPoint).addTo(map).bindPopup('Lokasi Check-out');
                        bounds.push(checkOutPoint);
                    }

                    // Sesuaikan view peta
                    if (bounds.length > 1) {
                        // Jika ada lebih dari satu titik, paskan peta agar semua terlihat
                        map.fitBounds(bounds, {
                            padding: [50, 50]
                        }); // Padding agar marker tidak di tepi
                    } else if (bounds.length === 1) {
                        // Jika hanya satu titik, pusatkan peta di titik itu
                        map.setView(bounds[0], 15);
                    }

                    mapContainer.data('map-init', true);

                    // Bug fix untuk Leaflet di dalam modal
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 100);
                }
            }
        });

        // Hapus status inisialisasi map saat modal ditutup
        $('.modal').on('hidden.bs.modal', function(event) {
            let modalId = $(this).attr('id');
            let mapId = 'map-' + modalId.replace('editAttendanceModal', '');
            let mapContainer = $('#' + mapId);
            if (mapContainer.length > 0) {
                mapContainer.data('map-init', false);
            }
        });
    </script>
@endpush
