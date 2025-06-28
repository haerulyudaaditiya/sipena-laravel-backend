@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaturan Akun</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengaturan Akun</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <!-- Form Informasi Profil -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Profil</h3>
                        </div>
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Alamat Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Form Ubah Password -->
                    <div class="card card-primary">
                         <div class="card-header">
                            <h3 class="card-title">Ubah Password</h3>
                        </div>
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')
                             <div class="card-body">
                                <div class="form-group">
                                    <label for="current_password">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control" id="current_password">
                                     @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" name="password" class="form-control" id="password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('layouts.admin.alert')
