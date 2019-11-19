@extends('layouts.user-master')

@section('right-panel')

<div id="right-panel" class="right-panel">

    <!-- BEGIN LOGS -->
    <div class="content mt-3">
            <div class="col">
                <aside class="profile-nav alt">
                    <section class="card">
                        <div class="card-header user-header alt bg-dark">
                            <div class="media">
                                <div class="media-body">
                                    <span class="text-light display-6">Activity Logs ({{count($logs)}})</span>
                                </div>
                            </div>
                        </div>


                        <ul class="list-group list-group-flush">

                            @if (count($logs) == 0)
                                <li class="list-group-item" style="text-align:center">
                                    No activities yet
                                </li>
                            @endif

                            @foreach ($logs as $log)
                                <li class="list-group-item">
                                    <pre>{{$log->date.'  '.$log->info}}</pre>
                                </li>
                            @endforeach



                            <!-- issue: should i add graph to the side of the logs -->

                        </ul>

                    </section>
                </aside>

            </div>
        </div>
        <!-- END LOGS -->

</div>

@endsection
