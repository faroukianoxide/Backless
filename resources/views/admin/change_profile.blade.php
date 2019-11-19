@extends('layouts.admin-master')

@section('right-panel')
<div id="root" class="container" style="margin-top:15px; alignment:center">

    @if ($message == 'success')

        <div class="alert  alert-success alert-dismissible fade show" role="alert">
            <span class="badge badge-pill badge-success">Success</span> Your profile has been updated
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

                    <form action='/admin/change_profile' method="POST">
                        {{csrf_field()}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fa fa-user-o"></i> Name <input type="text" name="name" value="{{$user->name}}"
                                    class="form-control form-field" />
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-envelope-o"></i> Email <input type="email" name="email" value="{{$user->email}}"
                                    class="form-control form-field" />
                            </li>
                            </li>

                        </ul>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-refresh"></i> Change Profile
                            </button>
                        </div>
                        <!--issue: make sure that at the end you use the ide to properly indent your codes -->
                    </form>


                </section>
            </aside>

        </div>
    </div>

 @endsection
