@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- DIUBAH: Judul Halaman --}}
                    <h1 class="m-0">Manajemen Gaji</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        {{-- DIUBAH: Breadcrumb --}}
                        <li class="breadcrumb-item active">Manajemen Gaji</li>
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
                            <h3 class="card-title">Daftar Gaji Karyawan</h3>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addSalaryModal">
                                    Tambah Data Gaji
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    {{-- DIUBAH: Kolom Tabel disesuaikan untuk data gaji --}}
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Periode Gaji</th>
                                        <th>Gaji Pokok</th>
                                        <th>Penerimaan</th>
                                        <th>Potongan</th>
                                        <th>Gaji Bersih</th>
                                        <th class="no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- DIUBAH: Loop menggunakan variabel $salaries --}}
                                    @foreach ($salaries as $index => $salary)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $salary->employee->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($salary->salary_date)->format('F Y') }}</td>
                                            <td>Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                                            {{-- DITAMBAHKAN: Kalkulasi untuk total penerimaan --}}
                                            @php
                                                $totalPenerimaan = $salary->basic_salary + $salary->allowances + $salary->bonus;
                                                $gajiBersih = $totalPenerimaan - $salary->deductions;
                                            @endphp
                                            <td>Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($salary->deductions, 0, ',', '.') }}</td>
                                            <td><strong>Rp {{ number_format($gajiBersih, 0, ',', '.') }}</strong></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editSalaryModal{{ $salary->id }}" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $salary->id }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-form-{{ $salary->id }}"
                                                    action="{{ route('salaries.destroy', $salary->id) }}"
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

    <div class="modal fade" id="addSalaryModal" tabindex="-1" role="dialog" aria-labelledby="addSalaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('salaries.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSalaryModalLabel">Tambah Data Gaji</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Karyawan <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Karyawan</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Periode Gaji (Pilih tanggal berapapun di bulan yang diinginkan) <span class="text-danger">*</span></label>
                            <input type="date" name="salary_date" class="form-control" required>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Gaji Pokok <span class="text-danger">*</span></label>
                                <input type="number" name="basic_salary" class="form-control" placeholder="Contoh: 5000000" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tunjangan</label>
                                <input type="number" name="allowances" class="form-control" placeholder="Contoh: 500000" value="0">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Bonus</label>
                                <input type="number" name="bonus" class="form-control" placeholder="Contoh: 250000" value="0">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Potongan</label>
                                <input type="number" name="deductions" class="form-control" placeholder="Contoh: 100000" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach ($salaries as $salary)
        <div class="modal fade" id="editSalaryModal{{ $salary->id }}" tabindex="-1" role="dialog" aria-labelledby="editSalaryModalLabel{{ $salary->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('salaries.update', $salary->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSalaryModalLabel{{ $salary->id }}">Edit Gaji: {{ $salary->employee->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Karyawan</label>
                                <input type="text" class="form-control" value="{{ $salary->employee->name }}" disabled>
                            </div>
                             <div class="form-group">
                                <label>Periode Gaji <span class="text-danger">*</span></label>
                                <input type="date" name="salary_date" class="form-control" value="{{ $salary->salary_date }}" required>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Gaji Pokok <span class="text-danger">*</span></label>
                                    <input type="number" name="basic_salary" class="form-control" value="{{ $salary->basic_salary }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Tunjangan</label>
                                    <input type="number" name="allowances" class="form-control" value="{{ $salary->allowances }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Bonus</label>
                                    <input type="number" name="bonus" class="form-control" value="{{ $salary->bonus }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Potongan</label>
                                    <input type="number" name="deductions" class="form-control" value="{{ $salary->deductions }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @include('layouts.admin.confirmation')
@endsection

@include('layouts.admin.alert')
