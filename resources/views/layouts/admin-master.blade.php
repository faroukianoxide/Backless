<!doctype html>
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BackLess - Self-hosted Backend-as-a-Service</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/themify-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/selectFX/css/cs-skin-elastic.css')}}">



    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>


    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <h3 class="navbar-brand">BackLess</h3>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="/admin/dashboard"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>


                    <h3 class="menu-title"><i class="menu-icon fa fa-cc"></i> Manage Accounts</h3><!-- /.menu-title -->

                    <li class="menu-item-has-children dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Users</a>
                            <ul class="sub-menu children dropdown-menu">
                                <li><i class="menu-icon fa fa-user"></i><a href="/admin/users/active">Active Users</a></li>
                                <li><i class="menu-icon fa fa-clock-o"></i><a href="/admin/users/suspended">Suspended Users</a></li>
                                
                            </ul>


                            <!-- issue: dont forget to change the menu icons -->
                    </li>
                    <li>
                            <a href="/admin/plans"> <i class="menu-icon fa fa-user"></i>Plans</a>
                        </li>
                     <h3 class="menu-title"> <i class="menu-icon fa fa-lock"></i> Settings</h3><!-- /.menu-title -->

                    <li>
                        <a href="/admin/change_profile"> <i class="menu-icon fa fa-user"></i>Change Profile</a>
                    </li>
                    <li>
                        <a href="/admin/change_password"> <i class="menu-icon fa fa-lock"></i>Change Password</a>
                    </li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

            <!-- Header-->
            <header id="header" class="header">
        
                <div class="header-menu">
        
                    <div class="col-sm-7">
                        <div class="header-left">
        
                            <span style="color:grey;">{{ \Request::route()->getName() }}</span>
                        </div>
                    </div>
        
                    <div class="col-sm-5">
                        <div class="user-area dropdown float-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="user-avatar rounded-circle fa fa-user"></i><span
                                    class="pull-right">{{Auth::user()->name}}(Admin)</span>
                            </a>
        
                            <div class="user-menu dropdown-menu">
        
                                <a class="nav-link" href="/admin/change_password"><i class="fa fa-cog"></i> Settings</a>
        
                                <a class="nav-link" href="/admin/logout"><i class="fa fa-power-off"></i> Logout</a>
                                <!-- issue: dont forget to use javascript to show the active page -->
                            </div>
                        </div>
        
                    </div>
                </div>
        
            </header><!-- /header -->
            <!-- Header-->

        @yield('right-panel')

    <!-- Right Panel -->
    <script>
        window.addEventListener('load', function () {
            try {
                loadChart();
            }catch (e) {
                console.log('not the page');
            }
        }, false);
    </script>

    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>


    <script src="{{asset('vendors/chart.js/dist/Chart.bundle.min.js')}} "></script>
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    <script src="{{asset('assets/js/widgets.js')}}"></script>
    <script>
        (function($) {
            "use strict";

            jQuery('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#1de9b6',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#1de9b6', '#03a9f5'],
                normalizeFunction: 'polynomial'
            });
        })(jQuery);
        
        
    </script>


</body>

</html>
