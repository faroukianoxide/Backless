@extends('layouts.user-master')

@section('right-panel')
<div id="root" class="container" style="margin-top:15px; alignment:center">
    @if (($success))
        <div class="alert  alert-success alert-dismissible fade show" role="alert">
            <span class="badge badge-pill badge-success">Success</span> {{$success}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif
    <!-- now the default laravel error variable -->
    @if (($faults))
        <div class="alert  alert-danger alert-dismissible fade show" role="alert">

           <span class="badge badge-pill badge-danger">Error</span> {{$faults}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>



        </div>
        <br/>
    @endif

    @if (count($errors))
        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error )
                <span class="badge badge-pill badge-danger">Error</span> {{$error}}
            @endforeach

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif



    <div class="row">
        <div class="col">
            <aside class="profile-nav alt">
                <section class="card">
                    <div class="card-header user-header alt bg-dark">
                        <div class="media">
                            <div class="media-body">
                                <span class="text-light display-6"><i class="fa fa-gear"></i> Your Profile Settings</span>
                            </div>
                        </div>
                    </div>

                    <!-- issue: remeber to add 'required' on all form fields -->

                    <form action = '/user/change_details' method="POST">
                        {{csrf_field()}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            <i class="fa fa-user-o"></i> Name <input type = "text" name = "name" value ="{{$user->name}}"
                                 class="form-control form-field"/>
                            </li>
                            <li class="list-group-item">
                            <i class="fa fa-envelope-o"></i> Email <input type="email" name="email" value ="{{$user->email}}"
                                 class="form-control form-field"/>
                            </li>
                        </li>

                        </ul>
                        <div class="card-footer">
                            <button type = "submit" class="btn btn-success btn-sm">
                                <i class="fa fa-refresh"></i> Change Profile
                            </button>
                        </div>
                        <!-- issue: make sure to change you button(submit) buttons to real submit so that you wont face the same problem
                            as in paddybase -->

                        <!--issue: make sure that at the end you use the ide to properly indent your codes -->
                </form>


                </section>
            </aside>

        </div>
    </div>

    <div class="row">
            <div class="col">
                <aside class="profile-nav alt">
                    <section class="card">
                        <div class="card-header user-header alt bg-dark">
                            <div class="media">
                                <div class="media-body">
                                    <span class="text-light display-6"><i class="fa fa-gear"></i> Your Password Settings</span>
                                </div>
                            </div>
                        </div>

                        <form action = '/user/change_password' method="POST">
                            {{csrf_field()}}
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <i class="fa fa-lock"></i> Old Password <input type = "password" name="old_password"
                                        class="form-control form-field"/>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-lock"></i> New Password <input type="password" name="new_password"
                                        class="form-control form-field"/>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-lock"></i> Confirm New Password <input type="password" name="new_password_confirmation"
                                        class="form-control form-field"/>
                                </li>
                            </ul>

                            <div class="card-footer">
                                <button type = "submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-refresh"></i> Change Password
                                </button>
                            </div>
                            <!-- issue: make sure to change you button(submit) buttons to real submit so that you wont face the same problem
                                as in paddybase -->

                            <!--issue: make sure that at the end you use the ide to properly indent your codes -->
                    </form>


                    </section>
                </aside>

            </div>
        </div>





@endsection
