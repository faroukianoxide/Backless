@extends('layouts.admin-master')

@section('right-panel')
<div id="root" class="container" style="margin-top:15px">
    <div class="row">
        <div class="col">
            <aside class="profile-nav alt">
                <section class="card">
                    <div class="card-header user-header alt bg-dark">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-light display-6">User's Information</span>
                            </div>
                        </div>
                    </div>


                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fa fa-user"></i> Name <span class="pull-right"> {{$user->name}} </span>
                        </li>
                        <li class="list-group-item">
                        <i class="fa fa-envelope"></i> Email <span class=" pull-right ">{{$user->email}}</span>
                        </li>
                        <li class="list-group-item">
                        <i class="fa fa-calendar"></i> Date Created <span class="pull-right">{{$user->created_at}}</span>
                        </li>
                        <li class="list-group-item">
                        <i class="fa fa-calendar"></i> Date Last Activated <span class="pull-right">{{$user->last_activated}}</span>
                        </li>
                        <li class="list-group-item">
                        <i class="fa fa-calendar-o"></i> Date Suspended <span class="pull-right">{{$user->last_suspended}}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-bell-o"></i> Total Requests Served
                        <span class="badge badge-success pull-right">{{$totalRequests}}</span>
                        </li>
                        <li class="list-group-item">
                        <i class="fa fa-calendar-o hover"></i> Token Expiry Date <span class="pull-right ">{{$user->expiry_date /*issue use timestmps*/}}</span>
                        </li>
                        </li>
                    </ul>

                    <div class="card-footer">

                        <button type="submit" class="btn btn-success btn-sm" data-toggle="modal" data-target="#activatemodal">
                            <i class="fa fa-dot-circle-o"></i> Activate Account
                        </button>

                        <button type="reset" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletemodal">
                            <i class="fa fa-ban"></i> Delete Account
                        </button>
                        <!-- users should'nt log in from the API anymore -->
                        <!-- since we now have suspension and deletion, an account has to be extra verified before login -->
                    </div>

                </section>
            </aside>

        </div>
    </div>

</div>

<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
     aria-hidden="true">
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
                        Note that this action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">I'm aware</button> <!--issue: implement delete user account -->
                </div>
                <!-- issue: add pagination to tables -->

            </div>
        </div>
    </div>

    <!--ACTIVATION MODAL -->
    <div class="modal fade" id="activatemodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
     aria-hidden="true">
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
                        Do you really want to activate this account ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">No</button>
                    <form action="/admin/activate" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="activate_user" value="1" />
                        <input type="hidden" name="user_id" value="{{$user->id}}" />
                        <button type="submit" class="btn btn-success">
                             Yes
                        </button>
                    </form>
                </div>
                <!-- issue: add pagination to tables -->

            </div>
        </div>
    </div>
    <!--END ACTIVATION MODAL -->



@endsection
