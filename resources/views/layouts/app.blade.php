<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        BASE_URL = "<?php echo url('') ?>"
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('title')</title>


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('assets/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ url('assets/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url('assets/plugins/summernote/summernote-bs4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="brand-image img-circle elevation-3 animation__shake"
                src="{{url('assets/dist/img/AdminLTELogo.webp')}}" alt="AdminLTELogo" height="100" width="100">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                @if(auth()->user()->role == 'Admin')
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/dashboard" class="nav-link">Home</a>
                </li>
                
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="/tenants" class="nav-link">Crear empresa</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="/bonos" class="nav-link">Bonos</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="/menu-auto" class="nav-link">Menus Sugeridos</a>
                    </li>
                @endif
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- Item rutas para el futuro-->
                <!-- <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>-->

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ url('/user/perfil/' . auth()->user()->id) }}" class="dropdown-item">
                         <i class="fas fa-user-circle mr-2"></i>  Perfil
                        </a>

                      <!--  <a href="{{ url('user/table') }}" class="dropdown-item">
                            <i class="fas fa-user-circle mr-2"></i>  Perfil1
                        </a>-->

                        
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('/logout') }}" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- /.navbar -->
        <!---->
        <!---->
        <!-- aqui empieza el menu general de la izquierda-->
        <!---->
        <!---->
        <!---->
        <!---->
        <style>
            .sidebar-dark-primary {
                background: #54a074 !important;
            }

            .brand-link {
                display: flex;
                justify-content: left;
                align-items: left;
            }

            .brand-image {
                margin-right: 10x;
                /* Ajusta esto según sea necesario */
            }
        </style>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            @if(auth()->user()->role == 'Admin')
                <a href="{{ url('dashboard') }}" class="brand-link">
                    <img src="{{ url('assets/images/logo-aire.png') }}" alt="Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">

                    <span class="brand-text font-weight-light">
                        @if(session('tenant'))
                            {{ session('tenant')->name }}

                        @endif
                    </span>
                </a>
            @else
                <a href="{{ url('dashboard1') }}" class="brand-link">
                    <img src="{{ url('assets/images/logo-aire.png') }}" alt="Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">

                    <span class="brand-text font-weight-light">
                        @if(session('tenant'))
                            {{ session('tenant')->name }}

                        @endif
                    </span>
                </a>
            @endif
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->

                <!-- SidebarSearch Form -->


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


                        <!-- Tenant Selector -->
                        @if(auth()->user()->tenants->count() >= 1)
                            @if(auth()->user()->role == 'Admin')
                                            <li class="nav-item">
                                                <div class="nav-link">
                                                    <form action="{{ route('set-tenant') }}" method="POST">
                                                        @csrf

                                            <li class="nav-header ">
                                                Seleccionar Empresa:
                                            </li>
                                            <select name="tenant_id" id="tenant_id" class="form-control" onchange="this.form.submit()">
                                                @foreach(auth()->user()->tenants as $tenant)
                                                    <option value="{{ $tenant->id }}" {{ session('tenant') && session('tenant')->id == $tenant->id ? 'selected' : '' }}>
                                                        {{ $tenant->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            </form>
                                </div>
                                </li>

                            @endif
                        @endif
            <li class="nav-header ">
                Comprobar Productos
            </li>
            <li class="nav-item">
                <a href="{{ url('producto')}}" class="nav-link">
                    <i class="fas fa-fw fa-box"></i>
                    <p>Comprobar Producto</p>
                </a>
            </li>


            </li>

            @if(auth()->user()->role == 'Admin')
                <li class="nav-header ">
                    Clientes
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-fw fa-share"></i>
                        <p>
                            Clientes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3 collapse">
                        <li class="nav-item">
                            <a href="{{ url('/users') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Usuarios y Ficheros</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/usuarios-con-consultas') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Seguimiento Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/consulta/create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Seguimiento</p>
                            </a>
                        </li>

                    </ul>
                </li>

            @endif

            @if(auth()->user()->role == 'Admin')
                <li class="nav-header ">
                    Calendario
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-fw fa-share "></i>
                        <p>
                            Calendario
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>



                    <ul class="nav nav-treeview pl-3 collapse">

                        <li class="nav-item">
                            <a href="{{ url('citas-semanales')}}" class="nav-link">
                                <i class="fas fa-fw fa-user"></i>
                                <p>Citas Reservadas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/horarios') }}" class="nav-link">
                                <i class="fas fa-calendar-alt"></i>
                                <p> Horarios Disponibles</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/calendario') }}" class="nav-link">
                                <i class="fas fa-calendar-check "></i>
                                <p>Mis Citas</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-header ">
                    Maestro Productos
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-fw fa-share "></i>
                        <p>
                            Productos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>



                    <ul class="nav nav-treeview pl-3 collapse">

                        <li class="nav-item">
                            <a href="{{ url('foods')}}" class="nav-link">
                                <i class="fas fa-fw fa-user"></i>
                                <p>Productos</p>
                            </a>
                        </li>


                    </ul>
                </li>
            @endif
            @if(auth()->user()->role == 'Admin')
                <li class="nav-header ">
                    Usuarios
                </li>
                <li class="nav-item">
                    <a href="{{ url('user/table')}}" class="nav-link">
                        <i class="fas fa-fw fa-user"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                <li class="nav-header ">
                    Finanzas
                </li>
                <li class="nav-item">
                    <a href="{{ url('/finanzas')}}" class="nav-link">
                        <i class="fas fa-fw fa-user"></i>
                        <p>Finanzas</p>
                    </a>
                </li>

            @endif
            @if(auth()->user()->role == 'Paciente')


                <li class="nav-header ">
                    Reservas Realizadas
                </li>
                <li class="nav-item">
                    <a href="{{ url('/calendario') }}" class="nav-link">
                        <i class="fas fa-calendar-check "></i>
                        <p>Mis Citas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('consultas1')}}" class="nav-link">
                        <i class="fas fa-fw fa-user"></i>
                        <p>Citas Reservadas</p>
                    </a>
                </li>
                <li class="nav-header ">
                    Seguimientos
                </li>
                <li class="nav-item has-treeview">
                    <a href="/consultas" class="nav-link">
                        <i class="fas fa-fw fa-share"></i>
                        <p>
                            Seguimientos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3 collapse">
                        <li class="nav-item">
                            <a href="{{ url('/consultas') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Resumen Seguimiento</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/consulta/create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear Seguimiento</p>
                            </a>
                        </li>
                    </ul>



            @endif


                </ul>
                </nav>
                <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    </aside>





    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper " style="max-width: 100%; margin: auto;">
        <!-- Main content -->
        <div class="container-fluid" style="max-width: 95%; margin: auto;">
            @yield('content')
        </div>

    </div>

    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>

    <!-- jQuery (CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Bootstrap 4 (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <!-- jQuery Knob Chart -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-knob@1.2.13/dist/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@1.13.1/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('assets/dist/js/adminlte.js')}}"></script>

    <!-- ./wrapper -->
    @yield('js')



    <!-- Your custom script for handling the tenant submenu visibility -->
    <script type="text/javascript">
        $(document).ready(function () {
            // Verificar si hay un tenant seleccionado
            if ($('#tenant_id').val() !== "") {
                $('#tenant-submenu').show(); // Mostrar el submenú cuando se selecciona un tenant
            }

            // Manejar el cambio de selección de tenant
            $('#tenant_id').change(function () {
                if ($(this).val() !== "") {
                    $('#tenant-submenu').show();  // Mostrar submenú al seleccionar un tenant
                } else {
                    $('#tenant-submenu').hide();  // Ocultar submenú si no hay selección
                }
            });
        });
    </script>



</body>

</html>