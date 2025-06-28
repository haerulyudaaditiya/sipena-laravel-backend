<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-header">MANAJEMEN KARYAWAN</li>

        <!-- Data Karyawan -->
        <li class="nav-item">
            <a href="{{ route('employees.index') }}"
                class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Data Karyawan</p>
            </a>
        </li>

        <!-- Data Kehadiran -->
        <li class="nav-item">
            <a href="{{ route('attendances.index') }}"
                class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-check"></i>
                <p>Data Kehadiran</p>
            </a>
        </li>

        <!-- Data Pengajuan Cuti -->
        <li class="nav-item">
            <a href="{{ route('leave-requests.index') }}"
                class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-plane-departure"></i>
                <p>
                    Data Pengajuan Cuti
                    @if (isset($cutiPending) && $cutiPending > 0)
                        <span class="badge badge-warning right">{{ $cutiPending }}</span>
                    @endif
                </p>
            </a>
        </li>

        <!-- Manajemen Gaji -->
        <li class="nav-item">
            <a href="{{ route('salaries.index') }}"
                class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-wallet"></i>
                <p>Manajemen Gaji</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('reports.index') }}"
                class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>Laporan</p>
            </a>
        </li>

        <li class="nav-header">PENGATURAN & SISTEM</li>

        <li class="nav-item">
            <a href="{{ route('settings.index') }}"
                class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Pengaturan Perusahaan</p>
            </a>
        </li>

        <!-- DITAMBAHKAN: Menu Manajemen Pengumuman -->
        <li class="nav-item">
            <a href="{{ route('announcements.index') }}"
                class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bullhorn"></i>
                <p>Pengumuman</p>
            </a>
        </li>

        <!-- Log Aktivitas -->
        <li class="nav-item">
            <a href="{{ route('activity-logs.index') }}"
                class="nav-link {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-history"></i>
                <p>Log Aktivitas</p>
            </a>
        </li>

        <!-- Pengaturan Akun -->
        <li class="nav-item">
            <a href="{{ route('profile.edit') }}"
                class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-cog"></i>
                <p>Pengaturan Akun</p>
            </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link text-red"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</nav>
