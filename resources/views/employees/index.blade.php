@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Karyawan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Data Karyawan</li>
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
                            <h3 class="card-title">Daftar Karyawan</h3>
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                data-target="#addUserModal">
                                Tambah Karyawan
                            </button>
                        </div>

                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Email</th>
                                        <th>Departemen</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                        <th class="no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $index => $employee)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $employee->employee_id }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->department ?? 'Tidak ada departemen' }}</td>
                                            <td>{{ $employee->position }}</td>
                                            <td>
                                                @if ($employee->status === 'active')
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($employee->status === 'active')
                                                    <form id="deactivate-form-{{ $employee->id }}"
                                                        action="{{ route('employees.update-status', $employee->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="inactive">
                                                        <button type="button"
                                                            class="btn btn-sm btn-secondary btn-toggle-status"
                                                            data-id="{{ $employee->id }}" data-action="nonaktifkan">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form id="activate-form-{{ $employee->id }}"
                                                        action="{{ route('employees.update-status', $employee->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="button"
                                                            class="btn btn-sm btn-success btn-toggle-status"
                                                            data-id="{{ $employee->id }}" data-action="aktifkan">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editEmployeeModal{{ $employee->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $employee->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-form-{{ $employee->id }}"
                                                    action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                                    style="display:none;">
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

    <!-- Modal Tambah Karyawan -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Tambah Karyawan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">NIK <span class="text-danger">*</span></label>
                                    <input type="text" name="employee_id"
                                        class="form-control {{ $errors->has('employee_id') ? 'is-invalid' : '' }}"
                                        value="{{ old('employee_id') }}" required>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Karyawan <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="department">Departemen</label>
                                    <input type="text" name="department"
                                        class="form-control {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                        value="{{ old('department') }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="npwp">NPWP</label>
                                    <input type="text" name="npwp"
                                        class="form-control {{ $errors->has('npwp') ? 'is-invalid' : '' }}"
                                        value="{{ old('npwp') }}">
                                    @error('npwp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="photo">Foto Karyawan</label>
                                    <input type="file" name="photo" class="form-control-file" accept="image/*"
                                        onchange="previewImage(event)">
                                    <img id="photoPreview" src="#" alt="Preview Image" class="mt-2"
                                        style="display:none; max-width: 100px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="text" name="phone"
                                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                        value="{{ old('phone') }}" maxlength="20">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="position">Posisi <span class="text-danger">*</span></label>
                                    <input type="text" name="position"
                                        class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}"
                                        value="{{ old('position') }}" required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="hire_date">Tanggal Bergabung <span class="text-danger">*</span></label>
                                    <input type="date" name="hire_date"
                                        class="form-control {{ $errors->has('hire_date') ? 'is-invalid' : '' }}"
                                        value="{{ old('hire_date') }}" required>
                                    @error('hire_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status"
                                        class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <input type="text" name="address"
                                        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                        value="{{ old('address') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Karyawan -->
    @foreach ($employees as $employee)
        <div class="modal fade" id="editEmployeeModal{{ $employee->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('employees.update', $employee->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEmployeeModalLabel{{ $employee->id }}">Edit Karyawan:
                                {{ $employee->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">NIK <span class="text-danger">*</span></label>
                                        <input type="text" name="employee_id"
                                            value="{{ old('employee_id', $employee->employee_id) }}"
                                            class="form-control {{ $errors->has('employee_id') ? 'is-invalid' : '' }}"
                                            required>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Nama Karyawan <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $employee->name) }}"
                                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            value="{{ old('email', $employee->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Departemen</label>
                                        <input type="text" name="department"
                                            value="{{ old('department', $employee->department) }}"
                                            class="form-control {{ $errors->has('department') ? 'is-invalid' : '' }}">
                                        @error('department')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="npwp">NPWP</label>
                                        <input type="text" name="npwp" id="npwp"
                                            value="{{ old('npwp', $employee->npwp) }}"
                                            class="form-control {{ $errors->has('npwp') ? 'is-invalid' : '' }}">
                                        @error('npwp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="photo">Foto Karyawan</label>
                                        <input type="file" name="photo" class="form-control-file" accept="image/*"
                                            onchange="previewImage(event)">
                                        @if ($employee->photo)
                                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="Employee Photo"
                                                class="mt-2" style="max-width: 100px;">
                                        @else
                                            <img id="photoPreview" src="#" alt="Preview Image" class="mt-2"
                                                style="display:none; max-width: 100px;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Telepon</label>
                                        <input type="text" name="phone"
                                            value="{{ old('phone', $employee->phone) }}"
                                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="position">Posisi <span class="text-danger">*</span></label>
                                        <input type="text" name="position"
                                            value="{{ old('position', $employee->position) }}"
                                            class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}"
                                            required>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="hire_date">Tanggal Bergabung <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="hire_date"
                                            value="{{ old('hire_date', $employee->hire_date) }}"
                                            class="form-control {{ $errors->has('hire_date') ? 'is-invalid' : '' }}"
                                            required>
                                        @error('hire_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status"
                                            class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                            required>
                                            <option value="active"
                                                {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="inactive"
                                                {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>
                                                Nonaktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                        <input type="text" name="address"
                                            value="{{ old('address', $employee->address) }}"
                                            class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('photoPreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
