# SIPENA - Backend API

**SIPENA (Sistem Informasi Pengelolaan Karyawan)** adalah backend RESTful API untuk aplikasi mobile berbasis Ionic. Proyek ini dibangun menggunakan **Laravel 12** dan berfungsi untuk mengelola data karyawan, presensi, cuti, gaji, dan lain-lain secara terpusat.

---

## 📚 Daftar Isi

- [✨ Fitur Utama](#-fitur-utama)
- [⚙️ Teknologi yang Digunakan](#️-teknologi-yang-digunakan)
- [📦 Paket Utama](#-paket-utama)
- [🚀 Panduan Instalasi & Konfigurasi](#-panduan-instalasi--konfigurasi)
- [📡 Struktur Endpoint API (Ringkasan)](#-struktur-endpoint-api-ringkasan)
- [🧩 Kontribusi](#-kontribusi)
- [📄 Lisensi](#-lisensi)

---

## ✨ Fitur Utama

- **🔐 Otentikasi Ganda**
  - Otentikasi berbasis sesi untuk admin (Laravel Breeze).
  - Otentikasi token untuk API mobile karyawan (Laravel Sanctum).
  
- **👨‍💼 Manajemen Karyawan (CRUD)**
  - Tambah, lihat, perbarui, dan nonaktifkan data karyawan oleh admin.

- **⏰ Manajemen Kehadiran**
  - Check-in dan check-out melalui API.
  - Validasi **Geofencing** (radius lokasi kantor).
  - Deteksi **keterlambatan otomatis** berdasarkan jam kerja.

- **📆 Manajemen Cuti**
  - Pengajuan cuti oleh karyawan via API.
  - Validasi otomatis kuota cuti.
  - Alur persetujuan (approve/reject) oleh admin.

- **💰 Manajemen Gaji**
  - Kelola komponen gaji: gaji pokok, tunjangan, bonus, potongan.
  - Gaji berdasarkan periode dan per karyawan.

- **📢 Manajemen Pengumuman**
  - Admin dapat membuat & mempublikasikan pengumuman ke aplikasi.

- **🏢 Pengaturan Perusahaan**
  - Kelola parameter global: lokasi kantor, jam kerja, kebijakan cuti.

- **📊 Laporan & Ekspor**
  - Ekspor data kehadiran, cuti, dan gaji ke **Excel & PDF**.

- **🔔 Notifikasi**
  - Pemberitahuan otomatis untuk status cuti dan slip gaji.

- **🧾 Logging Aktivitas**
  - Catatan aktivitas penting oleh admin untuk audit & keamanan.

---

## ⚙️ Teknologi yang Digunakan

- **Framework:** Laravel 12
- **Bahasa:** PHP 8.2+
- **Database:** MySQL

### 📦 Paket Utama

- [`laravel/breeze`](https://laravel.com/docs/breeze): Otentikasi berbasis sesi untuk panel admin (web).
- [`laravel/sanctum`](https://laravel.com/docs/sanctum): Autentikasi API
- [`maatwebsite/excel`](https://laravel-excel.com/): Ekspor ke Excel & PDF
- [`dompdf/dompdf`](https://github.com/dompdf/dompdf): Rendering PDF

---

## 🚀 Panduan Instalasi & Konfigurasi

### 1. Clone Repositori

```bash
git clone https://github.com/NAMA_ANDA/sipena-laravel-backend.git
cd sipena-laravel-backend
````

### 2. Install Dependensi

```bash
composer install
```

### 3. Konfigurasi Environment

Salin `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Edit `.env` dan sesuaikan:

```dotenv
APP_NAME=SIPENA
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=password_database_anda

GOOGLE_MAPS_API_KEY=KUNCI_API_GOOGLE_MAPS_ANDA
```

### 4. Generate Kunci Aplikasi

```bash
php artisan key:generate
```

### 5. Migrasi & Seeder Database

```bash
php artisan migrate:fresh --seed
```

✅ **Akun Admin Default**

* Email: `admin@example.com`
* Password: `admin12345`

✅ **Akun Karyawan Sampel**

* Email: `budisantoso@example.com`, `citralestari@example.com`, dll.
* Password: `password` (untuk semua)

### 6. Buat Storage Link

```bash
php artisan storage:link
```

### 7. Jalankan Server

```bash
php artisan serve
```

Akses backend di: [http://localhost:8000](http://localhost:8000)

---

## 📡 Struktur Endpoint API (Ringkasan)

Endpoint lengkap tersedia dalam dokumentasi sistem. Berikut struktur dasarnya:

| Modul          | Endpoint                                            |
| -------------- | --------------------------------------------------- |
| Otentikasi     | `/api/login`, `/api/logout`, `/api/change-password` |
| Presensi       | `/api/attendances/*`                                |
| Cuti           | `/api/leave-requests/*`                             |
| Gaji           | `/api/salaries/*`                                   |
| Notifikasi     | `/api/notifications/*`                              |
| ...dan lainnya |                                                     |

---

## 🧩 Kontribusi

Kontribusi sangat terbuka! Silakan buat issue atau pull request untuk fitur baru, perbaikan bug, atau peningkatan performa.

---

## 📄 Lisensi

Repositori ini menggunakan lisensi [MIT License](LICENSE).
