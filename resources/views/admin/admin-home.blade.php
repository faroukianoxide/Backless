@extends('layouts.admin-master')

@section('right-panel')

@if (count($errors))
<div class="alert  alert-success alert-dismissible fade show" role="alert">

        @foreach ($errors->all() as $error)

        @if ($error == 'account_deleted')
            The Account has been deleted
            
        @endif
            
            
        @endforeach

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
@endif


    <div class="content mt-3">


        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-flat-color-1">
                <div class="card-body pb-0">
                    <h4 class="mb-0">
                        <span style="font-size:30px">{{$users}}</span>
                    </h4>
                    <p class="text-light">Total Users</p>

                </div>

            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-flat-color-1">
                <div class="card-body pb-0">
                    <h4 class="mb-0">
                        <span style="font-size:30px">{{ $projects }}</span>
                    </h4>
                    <p class="text-light">Projects</p>

                </div>

            </div>
        </div>

        <!--/.col-->

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-flat-color-1">
                <div class="card-body pb-0">
                    <h4 class="mb-0">
                        @if ($platformStats[1]< (1024*1024)) <span style="font-size: 30px">
                            {{ round($platformStats[1]/(1024*1024), 4).'GB' }}</span>
                            @else
                            <span style="font-size: 30px">{{ round($platformStats[1],4).'GB' }}
                                @endif
                    </h4>
                    <p class="text-light">Storage</p>

                </div>
            </div>
        </div>
        <!--/.col-->

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-flat-color-1">
                <div class="card-body pb-0">
                    <h4 class="mb-0">
                        @if ($platformStats[0]<1024) <span style="font-size: 30px">
                            {{ ($platformStats[0]/1000).'MB' }}</span>
                            @else
                            <span style="font-size: 30px">{{ ($platformStats[0]).'MB' }}</span>
                            @endif
                    </h4>
                    <p class="text-light">Data Store</p>

                </div>
            </div>
        </div>
        <!--/.col-->

        <!-- BEGIN LOGS -->
        <div class="content mt-3">
            <div class="col">
                <aside class="profile-nav alt">
                    <section class="card">

                        <div class="card-body">
                            <div class="chartjs-size-monitor"
                                style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="mb-3">Data Store Traffic</h4>
                                </div>
                                <div class="col">
                                    <select name="select" id="select" class="form-control">
                                        <option onclick="readCharts('{{ $currentMonth }}', '{{ $currentYear }}')">
                                            {{$currentMonth.' '.$currentYear  }}</option>
                                        @foreach ($months as $month)
                                        <option onclick="readCharts('{{ $month->month }}', '{{ $month->year }}')">
                                            {{$month->month.' '.$month->year  }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <canvas id="store-traffic" height="342" width="684" class="chartjs-render-monitor"
                                    style="display: block; height: 171px; width: 342px;"></canvas>
                            </div>

                    </section>
                </aside>
            </div>

        </div>
        <!-- END LOGS -->






    </div> <!-- .content -->
</div><!-- /#right-panel -->


<script>
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    var trafficChart;

    function loadChart() {


        jQuery.noConflict();

        jQuery.ajax({

            url: '/admin/traffic',
            type: "GET",
            data: { month: monthNames[new Date().getMonth()], year: new Date().getFullYear() },

            success: function (data) {

                console.log(data);

                //Team chart

                var ctx = document.getElementById("store-traffic");
                ctx.height = 150;
                trafficChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.days,
                        type: 'line',
                        defaultFontFamily: 'Montserrat',
                        datasets: [{
                            data: data.values,
                            label: "Requests",
                            backgroundColor: 'rgba(0,103,255,.15)',
                            borderColor: 'rgba(0,103,255,0.5)',
                            borderWidth: 3.5,
                            pointStyle: 'circle',
                            pointRadius: 5,
                            pointBorderColor: 'transparent',
                            pointBackgroundColor: 'rgba(0,103,255,0.5)',
                        },]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: false,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Montserrat',
                            },


                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Days'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Requests'
                                }
                            }]
                        },
                        title: {
                            display: false,
                        }
                    }
                }); //end chart object
            },//end success
        });//end ajax

    }

    function readCharts(month, year) {

        jQuery.noConflict();
        try {

            jQuery.ajax({

                url: '/admin/traffic',
                type: "GET",
                data: { month: month, year: year },

                success: function (data) {
                    console.log(data);


                    //Team chart
                    var cts = document.getElementById("store-traffic");
                    //console.log(cts)
                    var ctx = document.getElementById("store-traffic");
                    trafficChart.destroy();
                    ctx.height = 194;

                    trafficChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.days,
                            type: 'line',
                            defaultFontFamily: 'Montserrat',
                            datasets: [{
                                data: data.values,
                                label: "Requests",
                                backgroundColor: 'rgba(0,103,255,.15)',
                                borderColor: 'rgba(0,103,255,0.5)',
                                borderWidth: 3.5,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBorderColor: 'transparent',
                                pointBackgroundColor: 'rgba(0,103,255,0.5)',
                            },]
                        },
                        options: {
                            responsive: true,
                            tooltips: {
                                mode: 'index',
                                titleFontSize: 12,
                                titleFontColor: '#000',
                                bodyFontColor: '#000',
                                backgroundColor: '#fff',
                                titleFontFamily: 'Montserrat',
                                bodyFontFamily: 'Montserrat',
                                cornerRadius: 3,
                                intersect: false,
                            },
                            legend: {
                                display: false,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    fontFamily: 'Montserrat',
                                },


                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Days'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Requests'
                                    }
                                }]
                            },
                            title: {
                                display: false,
                            }
                        }
                    }); //end chart object
                },//end success
            });//end ajax
        } catch (error) {
            console.log(error)
        }
    }

</script>


@endsection