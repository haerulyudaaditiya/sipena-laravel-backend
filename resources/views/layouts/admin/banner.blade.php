{{-- <!-- Total Admin -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <h3>{{ $totalAdmins }}</h3>
            <p>Total Admin</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <a href="{{ route('users.index') }}" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

<!-- Total User -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3>{{ $totalRegularUsers }}</h3>
            <p>Total User</p>
        </div>
        <div class="icon">
            <i class="fas fa-user"></i>
        </div>
        <a href="{{ route('users.index') }}" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

<!-- Total Jabatan -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3>{{ $totalPositions }}</h3>
            <p>Total Jabatan</p>
        </div>
        <div class="icon">
            <i class="fas fa-sitemap"></i>
        </div>
        <a href="{{ route('positions.index') }}" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

<!-- Posisi Terisi -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <h3>{{ $positionsFilled }}</h3>
            <p>Jabatan Terisi</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-check"></i>
        </div>
        <a href="{{ route('organizations.index') }}" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

<!-- Posisi Kosong -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
        <div class="inner">
            <h3>{{ $positionsVacant }}</h3>
            <p>Jabatan Kosong</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-times"></i>
        </div>
        <a href="{{ route('positions.index') }}" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

<!-- Total Kontak Masuk -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3>{{ $totalContacts }}</h3>
            <p>Total Pesan Masuk</p>
        </div>
        <div class="icon">
            <i class="fas fa-envelope"></i>
        </div>
        <a href="{{ route('contacts.index') }}" class="small-box-footer">More info
            <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

<!-- Kontak Masuk 7 Hari Terakhir -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
        <div class="inner">
            <h3>{{ $recentContacts }}</h3>
            <p>Pesan Baru (7 Hari)</p>
        </div>
        <div class="icon">
            <i class="fas fa-calendar-week"></i>
        </div>
        <a href="{{ route('contacts.index') }}" class="small-box-footer">More info
            <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div> --}}
