@extends('layouts.project-master')

    @section('right-panel')
    <div class="content mt-3">
        <div class="row">
        <div class="col">
            <div class="card text-white bg-flat-color-1">
                <div class="card-body pb-0">
                    <h4 class="mb-0">
                            <span style="font-size: 30px">
                        @if ($usages[0]<1024)
                        {{ ($usages[0]/1000)}}
                        @else
                        {{ ($usages[0])}}</span>
                        @endif
                       
                    </h4>
                    <span class="pull-right">MB</span>
                    
                    <p class="text-light">Data Store Used</p>
                </div>

            </div>
        </div>
        <!--/.col-->

        <div class="col">
            <div class="card text-white bg-flat-color-2">
                <div class="card-body pb-0">
                    <h4 class="mb-0">
                            <span style="font-size: 30px">
                        @if ($usages[1]< (1024*1024))
                        {{ round($usages[1]/(1024*1024), 4) }}
                        @else
                        {{ round($usages[1], 4) }}
                        @endif
                    </h4>
                    <span class="pull-right">GB</span>
                    <p class="text-light">Storage Used</p>

                </div>
            </div>
        </div>
        <!--/.col-->
        </div>
        
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

                            <div class="row form-group">
                                <div class="col">
                                    <label for="select" class=" mb-3 form-control-label">
                                        <h4>Datastore Traffic</h4>
                                    </label>
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
                            </div>
                            <canvas id="store-traffic" height="342" width="684" class="chartjs-render-monitor"
                                style="display: block; height: 171px; width: 342px;"></canvas>
                        </div>

                    </section>
                </aside>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"
                                class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <h4 class="mb-3">Data Store Usage (MB) </h4>
                            <canvas id="dataStoreChart" height="207" style="display: block; width: 414px; height: 207px;"
                                width="414" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"
                                class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <h4 class="mb-3">Storage (MB)</h4>
                            <span id="storageText"></span>
                            <canvas id="storageChart" height="207" style="display: block; width: 414px; height: 207px;"
                                width="414" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div> <!-- .content -->

    <script>
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        var trafficChart;

        function loadCharts () {
            
        (function ($) {
            "use strict";

            $.ajax({

                url: '/projects/chart_details/{{ $project_id }}',
                type: "GET",

                success: function (result) {
                    var useMB = (result.dataSize) / (1024 * 1024);
                    var useMBOthers = result.dataUsedByOthers / (1024 * 1024);
                    var assetsMB = (result.assetsSize) / (1024 * 1024);
                    var assetsUsedByOthers = result.assetsUsedByOthers / (1024 * 1024);
                    
                    var ctx = document.getElementById("dataStoreChart");
                    ctx.height = 150;
                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [useMB.toPrecision(2), useMBOthers.toPrecision(2),
                                ],
                                backgroundColor: [
                                    "rgba(0, 123, 255,0.9)",
                                    "grey",     
                                

                                ],
                                hoverBackgroundColor: [
                                    "rgba(0, 123, 255,0.9)",
                                    "grey",
                                
                                ]

                            }],
                            labels: [
                                "Used",
                                "Other Projects by You",
                                
                            ]
                        },
                        options: {
                            responsive: true
                        }
                    });

                    var cty = document.getElementById("storageChart");
                    cty.height = 150;
                    var myChart = new Chart(cty, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [assetsMB.toPrecision(2), assetsUsedByOthers.toPrecision(2),
                                ],
                                backgroundColor: [
                                    "rgba(0, 123, 255,0.9)",
                                    "rgba (0, 50, 256, 1)",     
                                

                                ],
                                hoverBackgroundColor: [
                                    "rgba(0, 123, 255,0.9)",
                                    "grey",     
                                
                                ]

                            }],
                            labels: [
                                "Used",
                                "Other Projects by You",
                                
                            ]
                        },
                        options: {
                            responsive: true
                        }
                    });
                },//endsuccess
            });


            try {
                $.ajax({

                    url: '/projects/{{ $project_id }}/traffic',
                    type: "GET",
                    data: {month:monthNames[new Date().getMonth()], year: new Date().getFullYear()},

                    success: function (data) {


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
            } catch (error) {

            }

        })(jQuery);

        }

        function readCharts(month, year) {
            
            jQuery.noConflict();
            try {

                jQuery.ajax({

                    url: '/projects/{{ $project_id }}/traffic',
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

</div><!-- /#right-panel -->

@endsection