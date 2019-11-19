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
                            <i class="fa fa-briefcase"></i> Projects <span class="pull-right">{{ $projectCount}}</span>
                            </li>
                            <li class="list-group-item">
                                    <i class="fa fa-calendar"></i> Data Store  <span class="pull-right">

                                            @if ($usage['store']<1024)
                                            {{ ($usage['store']/1000).'MB' }}
                                            @else
                                            {{ ($usage['store']).'MB' }} 
                                            @endif
                                            of {{ $plan->store_limit }} MB 

                                    </span>
                                </li>
                            <li class="list-group-item">
                                <i class="fa fa-calendar"></i> Storage Used <span class="pull-right">

                                        @if ($usage['storage']< (1024*1024))
                                        {{ round($usage['storage']/(1024*1024), 4).'GB' }}
                                        @else
                                        {{ round($usage[1], 4).'GB' }}
                                        @endif
                                        of {{ $plan->storage_limit }} GB 

                                </span>
                                </li>    
                        
                        
                        <li class="list-group-item">
                        <i class="fa fa-key"></i> Token Expiry Date
                            <span class="pull-right ">{{$user->expiry_date /*issue use timestmps*/}}</span>
                        </li>
                        <li class="list-group-item">    
                        <i class="fa fa-key"></i> Plan
                            <span class="pull-right ">{{ $plan->name /*issue use timestmps*/}}</span>
                        </li>

                        </li>
                    </ul>

                    <div class="card-footer">

                        <button type="reset" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#suspendmodal">
                            <i class="fa fa-dot-circle-o"></i> Suspend Account
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
                        All record relating to this account will be deleted. Note that this action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <form action="/admin/delete/" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="delete_user" value="1" />
                        <input type="hidden" name="user_id" value="{{$user->id}}" />
                        <button type="submit" class="btn btn-danger">I'm aware</button> <!--issue: implement delete user account -->
                    </form>
                </div>
                <!-- issue: add pagination to tables -->

            </div>
        </div>
    </div>

    <div class="modal fade" id="suspendmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
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
                        Note that this account will not be able to serve requests until you activate it
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <form action="/admin/suspend" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="suspend_user" value="1"/>
                        <input type="hidden" name="user_id" value="{{$user->id}}"/>
                        <button type="submit" class="btn btn-warning">I'm aware</button> <!--issue: implement delete user account -->
                    </form>
                </div>

            </div>
        </div>
    </div>



@endsection
