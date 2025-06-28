@push('scripts')
<script>
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: "{{ session('success') }}",
      timer: 2500,
      showConfirmButton: false
    });
  @endif

  @if (session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: "{{ session('error') }}",
    });
  @endif

  @if ($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Validasi Gagal',
      html: `{!! implode('<br>', $errors->all()) !!}`,
    });
  @endif
</script>
@endpush
