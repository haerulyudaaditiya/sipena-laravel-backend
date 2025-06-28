<footer class="main-footer">
    © 2025 <strong>Sipena</strong> | All Rights Reserved
</footer>

<!-- jQuery -->
<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('assets/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('assets/adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('assets/adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets/adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
</script>
<!-- Summernote -->
<script src="{{ asset('assets/adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/adminlte/dist/js/adminlte.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('assets/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- DataTables & Plugins -->
<script src="{{ asset('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [{
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(.no-print)' // Mengecualikan kolom aksi
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(.no-print)' // Mengecualikan kolom aksi
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(.no-print)' // Mengecualikan kolom aksi
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(.no-print)' // Mengecualikan kolom aksi
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(.no-print)' // Mengecualikan kolom aksi
                    }
                },
                'colvis' // Tombol untuk visibilitas kolom
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
