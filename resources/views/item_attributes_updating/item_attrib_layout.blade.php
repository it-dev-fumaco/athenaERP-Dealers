<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Attribute Update</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        {{--  <!-- Google Font: Source Sans Pro -->  --}}
        <link rel="stylesheet" href="{{ asset('/updated/custom/font.css') }}">
        {{--  <!-- Font Awesome Icons -->  --}}
        <link rel="stylesheet" href="{{ asset('/updated/plugins/fontawesome-free/css/all.min.css') }}">
        {{--  <!-- Ekko Lightbox -->  --}}
        <link rel="stylesheet" href="{{ asset('/updated/plugins/ekko-lightbox/ekko-lightbox.css') }}">
        {{--  <!-- Theme style -->  --}}
        <link rel="stylesheet" href="{{ asset('/updated/dist/css/adminlte.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('/updated/plugins/select2/css/select2.min.css') }}">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="{{ asset('/updated/plugins/datepicker/datepicker3.css') }}">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{ asset('/updated/plugins/iCheck/all.css') }}">

    </head>
    <body class="layout-top-nav">
        <div class="wrapper">
            <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="/search" class="navbar-brand">
                    {{-- <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
                    <span class="brand-text font-weight-light"><b>ERP</b> Item Attribute Update</span>
                </a>
            
                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    {{-- <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contact</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Dropdown</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="#" class="dropdown-item">Some action </a></li>
                                <li><a href="#" class="dropdown-item">Some other action</a></li>
                    
                                <li class="dropdown-divider"></li>
                    
                                <!-- Level two dropdown-->
                                <li class="dropdown-submenu dropdown-hover">
                                    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
                                    <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                        <li>
                                            <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
                                        </li>
                        
                                        <!-- Level three dropdown-->
                                        <li class="dropdown-submenu">
                                            <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level 2</a>
                                            <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                                            <li><a href="#" class="dropdown-item">3rd level</a></li>
                                            <li><a href="#" class="dropdown-item">3rd level</a></li>
                                            </ul>
                                        </li>
                                        <!-- End Level three -->
                        
                                        <li><a href="#" class="dropdown-item">level 2</a></li>
                                        <li><a href="#" class="dropdown-item">level 2</a></li>
                                    </ul>
                                </li>
                                <!-- End Level two -->
                            </ul>
                        </li>
                    </ul> --}}

                    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                        <div class="p-2">
                            <img src="{{ asset('dist/img/avatar04.png') }}" class="img-circle" alt="User Image" width="30" height="30">
                            <span class="d-md-none d-lg-none d-xl-inline-block" style="font-size: 13pt;">{{ Auth::user()->full_name }}</span>
                        </div>
						<a href="/signout" class="btn btn-default m-1">
                            <i class="fas fa-sign-out-alt"></i><span class="d-md-none d-lg-none d-xl-inline-block">Sign Out</span>
                        </a>

                    </ul>
                </div>
            </div>
        </nav>
        <div class="content-wrapper">
            @yield('content')

        </div>
       
</div>
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      FUMACO Inc.
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

        <!-- jQuery -->
        <script src="{{ asset('/updated/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('/updated/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Ekko Lightbox -->
        <script src="{{ asset('/updated/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('/updated/dist/js/adminlte.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('/updated/plugins/select2/js/select2.min.js') }}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{ asset('/updated/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
        <!-- iCheck 1.0.1 -->
        <script src="{{ asset('/updated/plugins/iCheck/icheck.min.js') }}"></script>
        <!-- ChartJS -->
        <script src="{{ asset('/updated/plugins/chart.js/Chart.min.js') }}"></script>

        <script src="{{ asset('/js/angular.min.js') }}"></script>
        <script src="{{ asset('/js/bootstrap-notify.js') }}"></script>

        @yield('script')
    </body>
</html>