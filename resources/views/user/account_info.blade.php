@extends('layouts.user-master')

@section('right-panel')
<div id="root" class="container" style="margin-top:15px; alignment:center">

    <!-- BEGIN MODAL-->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel"
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
                        Are you sure you really want to delete this account ? All contents related to this account will be deleted.
                        This cannot be undone
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <form action = "/user/info" method="POST">
                        {{csrf_field()}} <!-- issue: in fact this is very secure i have to change all delicate functions to use it -->
                        <!--issue: that onclick navigator change is very borring and unsecure -->
                        <input type="hidden" name="delete_account" value="1" />
                        <button type="submit"  class="btn btn-danger">I'm aware</button>
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
                                <span class="text-light display-6"><i class="fa fa-user"></i> Your Profile</span>
                            </div>
                        </div>
                    </div>


                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fa fa-user"></i> Name <span class="pull-right">{{$userInfo->name}} </span>
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-envelope-o"></i> Email <span class="pull-right">{{$userInfo->email}} </span>
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-bell"></i>Total Requests Served <span class="pull-right">{{$totalRequestCount}}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-cc"></i> Account Status <span class="pull-right">{{$userInfo->status}} </span>
                        </li>
                        @if ($userInfo->status == "Suspended")
                            <li class="list-group-item">
                                <i class="fa fa-calendar"></i> Data Suspended <span class="pull-right">{{$userInfo->last_suspended}}</span>
                            </li>
                        @endif
                        <li class="list-group-item">
                            <i class="fa fa-calendar"></i> Date Created <span class="pull-right">{{$userInfo->created_at}}</span>
                        </li>
                        <li class="list-group-item">
                        <i class="fa fa-calendar-o"></i> Date Modified <span class="pull-right">{{$userInfo->updated_at}}</span>
                    </li>

                    </ul>
                    <div class="card-footer">
                        <button class="btn btn-success btn-sm" onclick="window.location = '/user/change_profile'">
                            <i class="fa fa-refresh"></i> Change Profile
                        </button>
                        <button type="reset" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal">
                            <i class="fa fa-ban"></i> Delete Account
                        </button>
                    </div>

                </section>
            </aside>

        </div>
    </div>




@endsection
