<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"> --}}
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.0.0/ckeditor5.css" />
    <script src="https://cdn.ckeditor.com/ckeditor5/45.0.0/ckeditor5.umd.js"></script>
    <!-- Latest compiled and minified CSS -->
    <style>
        .modal-open {
            overflow: hidden
        }

        .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            display: none;
            overflow: auto;
            outline: 0;
        }

        .modal.show {
            display: block !important;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            background-color: #000;
            opacity: 0.5;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 50px auto;
            max-width: 800px;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            border: 1px solid #999;
            border-radius: 6px;
            box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
            outline: 0;
        }

        #modal-supplier {
            z-index: 1060 !important;
        }

        #modal-supplier .modal-dialog {
            display: block !important;
            margin: 50px auto !important;
        }

        #modal-supplier .modal-content {
            display: block !important;
        }

        #modal-detail {
            z-index: 1060 !important;
        }

        #modal-detail .modal-dialog {
            display: block !important;
            margin: 50px auto !important;
        }

        #modal-detail .modal-content {
            display: block !important;
        }

        .modal-open {
            overflow: hidden;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 10px;
        }
        
        .modal-xl {
            max-width: 95%;
            width: 95%;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #999;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 6px;
            -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
            box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
            outline: 0
        }
        
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }
        
        .badge-success {
            background-color: #5cb85c;
        }
        
        .badge-warning {
            background-color: #f0ad4e;
        }
        
        .badge-danger {
            background-color: #d9534f;
        }
        
        .modal-header.bg-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4;
        }
        
        .modal-header.bg-info {
            background-color: #5bc0de !important;
            border-color: #46b8da;
        }
        
        .modal-xl .table-produk {
            width: 100% !important;
            table-layout: fixed;
            font-size: 11px;
        }
        
        .modal-xl .table-produk th,
        .modal-xl .table-produk td {
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding: 4px !important;
            vertical-align: middle;
            border: 1px solid #ddd;
        }
        
        .modal-xl .table-produk th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px !important;
        }
        
        .modal-xl .table-responsive {
            border: none;
            overflow-x: hidden !important;
        }
        
        .modal-xl .modal-body {
            padding: 15px;
            max-height: 500px;
        }
        
        /* Compact styling for buttons and badges */
        .modal-xl .btn-xs {
            padding: 2px 6px;
            font-size: 9px;
            line-height: 1.2;
            border-radius: 2px;
        }
        
        .modal-xl .label {
            font-size: 9px;
            padding: 2px 4px;
        }
        
        .modal-xl .badge {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 10px;
        }
        
        .modal-xl .badge-success { background-color: #5cb85c; }
        .modal-xl .badge-warning { background-color: #f0ad4e; }
        .modal-xl .badge-danger { background-color: #d9534f; }
        
        .dataTables_wrapper {
            width: 100%;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            text-align: center;
            margin-top: 15px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 2px;
            background-color: #fff;
            border: 1px solid #ddd;
            color: #337ab7;
            text-decoration: none;
            border-radius: 4px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #e6e6e6;
            border-color: #adadad;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #337ab7;
            border-color: #337ab7;
            color: white;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #777;
            background-color: #fff;
            border-color: #ddd;
            cursor: not-allowed;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            background-color: #000
        }

        .modal-backdrop.fade {
            filter: alpha(opacity=0);
            opacity: 0
        }

        .modal-backdrop.in {
            filter: alpha(opacity=50);
            opacity: .5
        }

        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5
        }

        .modal-header .close {
            margin-top: -2px
        }

        .modal-title {
            margin: 0;
            line-height: 1.42857143
        }

        .modal-body {
            position: relative;
            padding: 15px
        }

        .modal-footer {
            padding: 15px;
            text-align: right;
            border-top: 1px solid #e5e5e5
        }

        .modal-footer .btn+.btn {
            margin-bottom: 0;
            margin-left: 5px
        }

        .modal-footer .btn-group .btn+.btn {
            margin-left: -1px
        }

        .modal-footer .btn-block+.btn-block {
            margin-left: 0
        }

        .modal-scrollbar-measure {
            position: absolute;
            top: -9999px;
            width: 50px;
            height: 50px;
            overflow: scroll
        }

        @media (min-width:768px) {
            .modal-dialog {
                width: 600px;
                margin: 30px auto
            }
            .modal-content {
                -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                box-shadow: 0 5px 15px rgba(0, 0, 0, .5)
            }
            .modal-sm {
                width: 300px
            }
        }

        @media (min-width:992px) {
            .modal-lg {
                width: 900px
            }
        }
    </style>
    @stack('style-alt')

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    {{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="left: inherit; right: 0px;">
                    <a href="{{ route('admin.profile.show') }}" class="dropdown-item">
                        <i class="mr-2 fas fa-file"></i>
                        {{ __('My profile') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="mr-2 fas fa-sign-out-alt"></i>
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </div>
            </li>
            @endauth
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </a>

        @include('layouts.navigation')
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @if(isset($errors) && $errors->any())
        <div class="content-header mb-0 pb-0">
            <div class="container-fluid">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul class="p-0 m-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        @if(session()->has('message'))
            <div class="content-header mb-0 pb-0">
                <div class="container-fluid">
                    <div class="mb-0 alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('message') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
        @endif
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

{{--  @vite('resources/js/app.js')  --}}
<!-- AdminLTE App -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/adminlte.min.js') }}" defer></script>
    <script>
            $("#path").on("change", function () {
            const item = $(".image-item").removeClass("d-none");
            const image = $("#path");
            const imgPreview = $(".img-preview").addClass("d-block");
            const oFReader = new FileReader();
            var inputFiles = this.files;
            var inputFile = inputFiles[0];
            // console.log(inputFile);
            oFReader.readAsDataURL(inputFile);

            // var render = new FileReader();
            oFReader.onload = function (oFREvent) {
                console.log(oFREvent.target.result);
                $(".img-preview").attr("src", oFREvent.target.result);
            };
        });
    </script>
    @include('sweetalert::alert')

@stack('script-alt')
@stack('scripts')
</body>
</html>
