@push('scripts')
    <script>
        $(function() {
            // Untuk notifikasi SUKSES, gunakan "toast" yang tidak mengganggu
            @if (session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Untuk notifikasi ERROR dari server (bukan validasi), gunakan modal alert
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                });
            @endif

            // Untuk error VALIDASI FORM, gunakan modal alert dengan daftar HTML
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                });
            @endif
        });
    </script>
@endpush
