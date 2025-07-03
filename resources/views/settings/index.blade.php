@extends('layouts.admin.admin')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        /* Memberikan style pada container peta */
        #officeMap {
            height: 350px;
            width: 100%;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaturan Perusahaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- DITAMBAHKAN: Include untuk menampilkan alert dari server --}}
            @include('layouts.admin.alert')

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Pengaturan Lokasi Kantor</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="text-muted mb-0">Klik peta atau geser penanda.</p>
                                    {{-- DITAMBAHKAN: Wrapper untuk tombol agar rapi --}}
                                    <div>
                                        <button type="button" class="btn btn-sm btn-info" id="find-my-location">
                                            Temukan Lokasi Saya
                                        </button>
                                        {{-- DITAMBAHKAN: Tombol untuk menghapus lokasi --}}
                                        <button type="button" class="btn btn-sm btn-danger" id="clear-location">
                                            Hapus Lokasi
                                        </button>
                                    </div>
                                </div>
                                <div id="officeMap"
                                    style="height: 310px; width: 100%; border-radius: .25rem; border: 1px solid #ced4da;"
                                    class="mb-3"></div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Latitude Kantor</label>
                                            <input type="text" id="office_latitude" name="office_latitude"
                                                class="form-control @error('office_latitude') is-invalid @enderror"
                                                value="{{ old('office_latitude', $settings->office_latitude) }}"
                                                placeholder="Dipilih dari peta" readonly>
                                            @error('office_latitude')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Longitude Kantor</label>
                                            <input type="text" id="office_longitude" name="office_longitude"
                                                class="form-control @error('office_longitude') is-invalid @enderror"
                                                value="{{ old('office_longitude', $settings->office_longitude) }}"
                                                placeholder="Dipilih dari peta" readonly>
                                            @error('office_longitude')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Radius Presensi (meter)</label>
                                    <input type="number" name="presence_radius"
                                        class="form-control @error('presence_radius') is-invalid @enderror"
                                        value="{{ old('presence_radius', $settings->presence_radius) }}" required>
                                    @error('presence_radius')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Pengaturan Jam & Kebijakan</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Jam Masuk Standar</label>
                                    <input type="time" name="check_in_time"
                                        class="form-control @error('check_in_time') is-invalid @enderror"
                                        value="{{ old('check_in_time', $settings->check_in_time) }}" required>
                                    @error('check_in_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Jam Pulang Standar</label>
                                    <input type="time" name="check_out_time"
                                        class="form-control @error('check_out_time') is-invalid @enderror"
                                        value="{{ old('check_out_time', $settings->check_out_time) }}" required>
                                    @error('check_out_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Waktu Mulai Absen (Menit Sebelum Jam Masuk)</label>
                                    <input type="number" name="check_in_start_margin"
                                        class="form-control @error('check_in_start_margin') is-invalid @enderror"
                                        value="{{ old('check_in_start_margin', $settings->check_in_start_margin) }}"
                                        required>
                                    @error('check_in_start_margin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: Jika jam masuk 08:00 dan diisi 60, maka
                                        karyawan bisa mulai absen dari pukul 07:00.</small>
                                </div>
                                <div class="form-group">
                                    <label>Kuota Cuti Tahunan (hari)</label>
                                    <input type="number" name="annual_leave_quota"
                                        class="form-control @error('annual_leave_quota') is-invalid @enderror"
                                        value="{{ old('annual_leave_quota', $settings->annual_leave_quota) }}" required>
                                    @error('annual_leave_quota')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <button type="submit" class="btn btn-success float-right">
                                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Menggunakan Google Maps API dengan callback --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async
        defer></script>

    <script>
        let map, marker;
        let geolocationDenied = false;

        // Fungsi ini akan dipanggil oleh Google Maps API setelah skrip selesai dimuat
        function initMap() {
            const latInput = document.getElementById('office_latitude');
            const lngInput = document.getElementById('office_longitude');

            const initialLat = parseFloat(latInput.value) || -6.2088; // Default Jakarta
            const initialLng = parseFloat(lngInput.value) || 106.8456;
            const initialPosition = {
                lat: initialLat,
                lng: initialLng
            };

            map = new google.maps.Map(document.getElementById("officeMap"), {
                zoom: 13,
                center: initialPosition,
            });

            marker = new google.maps.Marker({
                position: initialPosition,
                map: map,
                draggable: true,
            });

            // Event listener saat marker selesai digeser
            google.maps.event.addListener(marker, 'dragend', function() {
                const position = marker.getPosition();
                latInput.value = position.lat().toFixed(7);
                lngInput.value = position.lng().toFixed(7);
            });

            // Event listener saat peta diklik
            map.addListener('click', (event) => {
                marker.setPosition(event.latLng);
                latInput.value = event.latLng.lat().toFixed(7);
                lngInput.value = event.latLng.lng().toFixed(7);
            });

            // Event listener untuk tombol "Temukan Lokasi Saya"
            document.getElementById('find-my-location').addEventListener('click', function() {
                initGeolocation();
            });

            // DITAMBAHKAN: Event listener untuk tombol "Hapus Lokasi"
            document.getElementById('clear-location').addEventListener('click', function() {
                // Mengosongkan nilai input
                latInput.value = '';
                lngInput.value = '';

                // Mengatur ulang posisi peta dan marker ke default (Jakarta)
                const defaultPosition = { lat: -6.2088, lng: 106.8456 };
                map.setCenter(defaultPosition);
                map.setZoom(13);
                marker.setPosition(defaultPosition);

                // Menampilkan notifikasi bahwa lokasi telah dihapus
                Swal.fire({
                    icon: 'info',
                    title: 'Lokasi Dikosongkan',
                    text: 'Koordinat lokasi telah dihapus. Silakan pilih lokasi baru atau simpan pengaturan.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500
                });
            });
        }

        // Fungsi geolocation yang reusable
        function initGeolocation() {
            if (navigator.geolocation && !geolocationDenied) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setCenter(pos);
                        map.setZoom(16);
                        marker.setPosition(pos);
                        document.getElementById('office_latitude').value = pos.lat.toFixed(7);
                        document.getElementById('office_longitude').value = pos.lng.toFixed(7);
                    },
                    error => {
                        handleGeolocationError(error);
                    }
                );
            } else if (!geolocationDenied) {
                handleGeolocationError({
                    code: -1,
                    message: "Browser Anda tidak mendukung Geolocation."
                }); // Kode custom
            }
        }

        // DIUBAH: Fungsi untuk menangani error Geolocation dengan SweetAlert2
        function handleGeolocationError(error) {
            let errorMessage = "Geolocation tidak dapat diakses.";
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = "Anda telah menolak izin untuk mengakses lokasi.";
                    geolocationDenied = true; // Tandai agar tidak meminta lagi
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Informasi posisi lokasi tidak tersedia saat ini.";
                    break;
                case error.TIMEOUT:
                    errorMessage = "Permintaan untuk mendapatkan lokasi melebihi batas waktu.";
                    break;
                default:
                    errorMessage = error.message; // Gunakan pesan dari error jika ada
            }
            // Menggunakan SweetAlert2 yang biasanya sudah ada di AdminLTE
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mendapatkan Lokasi',
                text: errorMessage,
            });
        }
    </script>
@endpush