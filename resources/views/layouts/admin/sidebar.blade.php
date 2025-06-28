<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
        <div class="image">
            {{-- Menggunakan ikon Font Awesome sebagai placeholder foto --}}
            <i class="fas fa-user-circle fa-2x text-light"></i>
        </div>
        <div class="info">
            {{-- DIUBAH: Mengambil nama pengguna yang sedang login --}}
            <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    @include('layouts.admin.menu')
    <!-- /.sidebar-menu -->
</div>
