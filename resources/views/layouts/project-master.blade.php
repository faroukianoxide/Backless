<!doctype html>
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ session('projectName') }} | Dashboard</title>
    <meta name="description" content="Self-Hosted Cloud Service">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/themify-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/selectFX/css/cs-skin-elastic.css')}}">
    <link rel="stylesheet" href="/vendors/prism/prism.css" />
    <link href="styleshee" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.16.0/themes/prism.min.css" />




    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="/vendors/fonts/font.css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>


    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu"
                    aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <h3 class="navbar-brand">{{session('projectName')}}</h3>
                <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
            </div>

            <ul id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <a class="menu-title" data-toggle="collapse" href="#analyticsCollapse" aria-expanded="false"
                        aria-controls="analyticsCollapse"> Analytics</a><!-- /.menu-title -->
                    <div class="collapse" id="analyticsCollapse">

                        <li>
                            <a href="/projects/{{$project_id}}/"> <i class="menu-icon fa fa-dashboard"></i>Dashboard
                            </a>
                        </li>

                    </div>


                    <a class="menu-title" data-toggle="collapse" href="#infrastructureCollapse" aria-expanded="false"
                        aria-controls="infrastructureCollapse"> Infrastructure</a><!-- /.menu-title -->
                    <div class="collapse" id="infrastructureCollapse">


                        <li class="menu-item-has-children dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Data Store </a>
                            <ul class="sub-menu children dropdown-menu">
                                <li>

                                    <a href="/projects/{{$project_id}}/data"> <i class="menu-icon fa fa-tasks-o"></i>All
                                        Data</a>
                                </li>
                                <li>

                                    <a href="/projects/{{$project_id}}/indexes"> <i
                                            class="menu-icon fa fa-tasks-o"></i>Data Indexes
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li>
                            <a href="/projects/{{$project_id}}/auths"> <i
                                    class="menu-icon fa fa-lock"></i>Auth</a>
                        </li>

                        <li>
                            <a href="/projects/{{$project_id}}/storage?path=root"> <i
                                    class="menu-icon fa fa-hdd-o"></i>Storage</a>
                        </li>
                        <!--
                        <li>
                            <a href="/projects/{{$project_id}}/logs"> <i class="menu-icon fa fa-info"></i>Project
                                Logs</a>
                        </li>
                    -->
                    </div>
                    <a class="menu-title" data-toggle="collapse" href="#servicesCollapse" aria-expanded="false"
                        aria-controls="servicesCollapse"> Services</a><!-- /.menu-title -->
                    <div class="collapse" id="servicesCollapse">

                        <!-- <li  >

                                <a href="/projects/{{$project_id}}/data"> <i class="menu-icon">{...}</i>Functions</a>
                            </li> -->

                        <li>
                            <a href="/projects/{{$project_id}}/channels"> <i
                                    class="menu-icon fa fa-exchange"></i>Realtime Channels</a>
                        </li>
                    </div>

                   
                    <li>
                            <a href="/projects/{{ $project_id }}/settings"> <i class="menu-icon fa fa-gear"></i>Settings</a>
                        </li>
                    

                </ul>

                </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->
    <!-- Header-->
    <div id="right-panel" class="right-panel">
    <header id="header" class="header">

            <div class="header-menu">
    
                <div class="col-sm-7">
                    <div class="header-left">
                        <span class="mx-2">
                        <a href="{{ route('user_home')}}"><i class="menu-icon fa fa-home"></i></a>
                        </span>
                        <span class="mx-2">
                                
                        </span>
                        <span class="mx-2">
                                <a href="/user/request_auth"><i class="menu-icon fa fa-key"></i></a>
                        </span>
                        
                    </div>
                    
                </div>
    
                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="user-avatar rounded-circle fa fa-user"></i><span class="pull-right">
                                {{ Auth::user()->name }} </span>
                        </a>
    
                        <div class="user-menu dropdown-menu">
                            
    
                            <a class="nav-link" href="/user/change_profile"><i class="fa fa-cog"></i> Settings</a>
    
                            <a class="nav-link" href="/logout"><i class="fa fa-power-off"></i> Logout</a>
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
                    loadCharts();
                }catch (e) {
                    console.log('not the page');
                }
            }, false);
        </script>

    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>

    <script src="{{asset('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="/vendors/chart.js/dist/Chart.bundle.min.js"></script>

    <script>
        
        var projectId = {{ $project_id }};

        

        function uploadFile(file) {
            if (file == '')
                return;
            var formData = new FormData();
            var files = file.files[0];
            formData.append('file', files);

            $.ajax({
                url: "/{{'projects/'.$project_id.'/storage/upload'}}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    //reloadPage
                },

                error: function (xhr, textStatus, error) {
                    //console.log(error);
                }
            });
        }




        //storage scripts
    </script>

    <script>

    </script>



</body>

</html>