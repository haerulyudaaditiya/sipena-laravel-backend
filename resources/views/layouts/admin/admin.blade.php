<!DOCTYPE html>
<html lang="en">
@include('layouts.admin.header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.admin.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            @include('layouts.admin.logo')

            <!-- Sidebar -->
            @include('layouts.admin.sidebar')
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.admin.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        @stack('scripts')
    </div>
    <!-- ./wrapper -->

</body>

</html>
