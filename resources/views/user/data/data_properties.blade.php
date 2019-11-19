@extends('layouts.project-master')

@section('right-panel')
<div id="root" class="container" style="margin-top:15px">

    @if (count($errors))

            <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                        <span class="badge badge-pill badge-danger">Error</span> {{$error}}
                        <br/>
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
                                <span class="text-light display-6">Properties</span>
                            </div>
                        </div>
                    </div>


                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fa fa-envelope-o"></i> Date Created <span class="pull-right">{{$data->created_at}} </span>
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-tasks"></i> Date Modified <span class="pull-right">{{$data->updated_at}}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-bell-o"></i>State Change Event<span class="pull-right">{{ $data->listener }}</span>
                        </li>
                    </ul>

                </section>
            </aside>

        </div>
    </div>

    <div class="coddl">
        <aside class="profile-nav alt">
            <section class="card">
                <div class="card-header user-header alt bg-dark">
                    <div class="media">
                        <div class="media-body">
                            <i class="fa fa-cog hover"></i> <span class="text-light display-6">Settings</span>
                        </div>
                    </div>
                </div>
            <form action="/projects/{{$project_id}}/data/{{$data->id}}/update" method="POST">
                    {{csrf_field()}}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="#"> <i class="fa fa-envelope-o"></i> Content </a>
                            <div class="form-group">
                                <textarea id="content" name="content" class="form-control" aria-required="true"
                                    aria-invalid="false">{{$data->content}}</textarea>

                            </div>

                            <div class="form-group">
                                <label for="listener" class=" form-control-label"> <i class="fa fa-bell"></i> State Change Event</label>
                            <input type="listener" id="listener" name="listener" value="{{ $data->listener }}" class="form-control">
                                
                            </div>
                        </li>

                    </ul>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-dot-circle-o"></i> Save
                            </button>
                        <button type="reset" class="btn btn-danger btn-sm"
                        onclick="window.location = '/projects/{{$project_id}}/data/{{$data->id}}/delete'">
                                <i class="fa fa-ban"></i> Delete Data
                            </button>
                        </div>

                </form>

            </section>
        </aside>

    </div>

</div>



@endsection


