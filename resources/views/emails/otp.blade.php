<x-mail::message>
# Kode Reset Password Anda

Gunakan kode di bawah ini untuk mereset password akun Anda di aplikasi SIPENA.

<x-mail::panel>
{{ $otp }}
</x-mail::panel>

Kode ini hanya berlaku selama 10 menit. Jika Anda tidak meminta reset password, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
