@extends('layouts.user-master')

@section('right-panel')
<div id="root" class="container" style="margin-top:15px">

    <!-- BEGIN MODAL-->
    <div class="modal fade" id="suspendmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel"
        data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Warning!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        This account will no longer serve request with this token, this process can not be undone
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <form action = "/user/request_auth" method="POST">
                        {{csrf_field()}} <!-- issue: in fact this is very secure i have to change all delicate functions to use it -->
                        <!--issue: that onclick navigator change is very borring and unsecure -->
                        <input type="hidden" name="change_auth" value="1"/>
                        <button type="submit" type="button" class="btn btn-danger">I'm aware</>
                    </form>
                    <!--issue: implement delete user account -->
                    <!--issue: use form to enable delicate actions -->
                </div>

            </div>
        </div>
    </div>
    <!--END MODAL -->


    <div class="row">
        <div class="col">
            <aside class="profile-nav alt">
                <section class="card">
                    <div class="card-header user-header alt bg-dark">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-light display-6 "><i class="fa fa-lock"></i> Request Authentication
                                    Settings</span>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            <i class="fa fa-key"></i> Authorization Token:
                            <input type="text" style="width: 50%" id="auth_token" style="visibility:none" value="{{$requestInfo->auth_token}}" />
                            <div class=" pull-right ">

                                <button type="submit" class="btn btn-success btn-sm" onclick="copyToClipboard();">
                                    <i class="fa fa-copy" title="copy to clipboard"></i> Copy
                                </button>
                                <button type="reset" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#suspendmodal">
                                    <i class="fa fa-refresh"></i> Change
                                </button>

                            </div>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-calendar"></i> Date Issued <span class="pull-right">
                                {{$requestInfo->date_issued}} </span>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-calendar-o"></i> Date Changed <span class="pull-right"> @if ($requestInfo->date_changed == null )
                                {{'Not Changed'}} @else {{$requestInfo->date_changed}} @endif
                                <!-- issue this method of if and else if good use if for others too --> </span>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-clock-o"></i> Expiry Date <span class="pull-right ">{{$requestInfo->expiry_date}}</span>
                        </li>

                    </ul>

                </section>

            </aside>



        </div>
    </div>
    <script>
        function copyToClipboard() {
            //issue: remember to perhaps hide this function in another file or something
            let token = document.getElementById('auth_token');
            token.select();
            document.execCommand('copy');

        }
    </script>




    @endsection
