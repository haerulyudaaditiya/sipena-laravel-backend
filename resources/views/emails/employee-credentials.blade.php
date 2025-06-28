@component('mail::message')
# Halo, {{ $employee->name }}

Selamat datang di perusahaan kami! Berikut adalah informasi login untuk aplikasi kami:

@component('mail::panel')
**Email:** {{ $employee->email }}  
**Password:** {{ $password }}
@endcomponent

Silakan gunakan informasi di atas untuk login ke aplikasi kami.

Setelah login, harap segera mengganti password Anda melalui halaman **Ganti Password** di aplikasi.

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.

Terima kasih,  
Tim HRD
@endcomponent
