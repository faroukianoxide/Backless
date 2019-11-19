@extends('layouts.project-master')


@section('right-panel')

<div class="main">

    <div class="card-header">
        <strong class="card-title">Strage Configuration</strong>

    </div><br/>

    @if (count($errors))
                <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                        @if ($errors->has('json_error'))
                            <span class="badge badge-pill badge-danger">Error</span> {{$errors->first('json_error')}}
                            <br/>
                        @endif
                        @foreach ($errors->all() as $error)
                            <span class="badge badge-pill badge-danger">Error</span> {{$error}}
                            <br/>
                        @endforeach

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
    @endif
    <div class="card mx-4 my-4">
    <div class="card-header">
        <strong>All Channels</strong> "{{session('projectName')}}"
        <button class="btn pull-right"  data-toggle="modal" data-target="#scrollmodal"><i class="fa fa-plus"></i> New</button>
        </div>
    <div class="card-body">
        <table class="table table-condensed table-hover">
            <thead>
                <th>Description</th>
                <th>Channel Token</th>
                <th>Action</th>

            </thead>


            <tbody>
                <?php $sn = 0; ?>
                @foreach ($channels as $channel )
                    <tr><td>
                        <i class="fa fa-volume-up"></i> <strong>{{$channel->description}}</strong>
                        </td>
                    <td>{{$channel->channel}}</td>
                    <td>
                        <a href="/projects/{{$project_id}}/channels/{{$channel->channel}}/stats"><i class="fa fa-bar-chart"></i> Stats</a>
                        <span></span>
                        <a href="/projects/{{$project_id}}/channels/{{$channel->channel}}/edit"><i class="fa fa-edit"></i> Edit</a></td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    <!-- START SCROLL MODAL -->
    <div class="modal fade" id="scrollmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
     aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel"><i class="fa fa-volume-up"></i> Create a new channel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form action="/projects/{{$project_id}}/channels/new" method="POST">
                    <div class="modal-body">
                        <p>
                            {{csrf_field()}}
                                <div class="form-group">
                                    <label for="description" class="control-label ">Description</label>

                                    <input id="description" name="description" type="text" class="form-control"
                                    placeholder = "e.g Notifications Channel" aria-required="true" aria-invalid="false" required>
                                    <small>Create a short description of your channel</small>
                                </div>



                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END SCROLL MODAL -->
</div>

@endsection
