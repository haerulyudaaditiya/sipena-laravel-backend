@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delete Confirmation
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data akan terhapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });

        // Activate/Deactivate Confirmation
        const toggleButtons = document.querySelectorAll('.btn-toggle-status');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const action = this.getAttribute('data-action');
                const actionText = action === 'aktifkan' ? 'mengaktifkan' : 'menonaktifkan';

                Swal.fire({
                    title: `Yakin ingin ${action} pengguna?`,
                    text: `Pengguna akan ${actionText}.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: action === 'aktifkan' ? '#28a745' : '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Ya, ${action}`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formId = action === 'aktifkan' ? 'activate-form-' + id : 'deactivate-form-' + id;
                        document.getElementById(formId).submit();
                    }
                });
            });
        });
    });
</script>
@endpush