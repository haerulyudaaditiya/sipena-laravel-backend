@extends('layouts.admin.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Pengumuman</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengumuman</li>
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
                            <h3 class="card-title">Daftar Pengumuman</h3>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                    data-target="#addAnnouncementModal">
                                    Buat Pengumuman Baru
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Tanggal Publikasi</th>
                                        <th class="no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($announcements as $index => $announcement)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $announcement->title }}</td>
                                            <td>
                                                @if ($announcement->status == 'published')
                                                    <span class="badge badge-success">Dipublikasikan</span>
                                                @else
                                                    <span class="badge badge-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>{{ $announcement->published_at ? \Carbon\Carbon::parse($announcement->published_at)->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editAnnouncementModal{{ $announcement->id }}"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $announcement->id }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-form-{{ $announcement->id }}"
                                                    action="{{ route('announcements.destroy', $announcement->id) }}"
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

    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog"
        aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('announcements.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAnnouncementModalLabel">Buat Pengumuman Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required
                                placeholder="Contoh: Libur Nasional Hari Raya">
                        </div>
                        <div class="form-group">
                            <label for="content">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control summernote" rows="5" required
                                placeholder="Tulis isi pengumuman di sini..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="draft" selected>Simpan sebagai Draft</option>
                                <option value="published">Langsung Publikasikan</option>
                            </select>
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

    @foreach ($announcements as $announcement)
        <div class="modal fade" id="editAnnouncementModal{{ $announcement->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editAnnouncementModalLabel{{ $announcement->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAnnouncementModalLabel{{ $announcement->id }}">Edit Pengumuman
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ $announcement->title }}" required>
                            </div>
                            <div class="form-group">
                                <label for="content">Isi Pengumuman <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control summernote" rows="5" required>{{ $announcement->content }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="draft" {{ $announcement->status == 'draft' ? 'selected' : '' }}>Simpan
                                        sebagai Draft</option>
                                    <option value="published"
                                        {{ $announcement->status == 'published' ? 'selected' : '' }}>Publikasikan</option>
                                </select>
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

@push('styles')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        // Inisialisasi Summernote pada semua textarea dengan class .summernote
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Tulis isi pengumuman di sini...',
                tabsize: 2,
                height: 150
            });
        });
    </script>
@endpush
