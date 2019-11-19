@extends('layouts.user-master')

@section('right-panel')
<div class="main">
        <div class="card-header">
        <i class="fa fa-key"></i>
    </div>
    <div class="card-header">
        <button type="button" class="btn  mb-1 fa fa-plus" style="border-radius:10px" data-toggle="modal" data-target="#scrollmodal">
           New
        </button>
    </div>

    <div class="card-body mx-4">
            
        <table class="table table-condensed table-hover">
            <tbody>

                @foreach ($projects as $project )

                    <tr class="table-row sortable" style = "cursor: pointer;"
                        onclick="window.location = '/projects/{{$project->id}}' "> <!-- issue change this to html code and not javascript -->
                        <td><i class="fa fa-briefcase"></i> {{$project->name}}</td>
                        <td class="pull-right">{{$project->created_at}}</td>
                        <!-- create actions button -->

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- START SCROLL MODAL -->
    <div class="modal fade" id="scrollmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
     aria-hidden="true" style="margin-top:150px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">CREATE PEERCLOUD PROJECT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/projects/new" method="POST">
                    <div class="modal-body">
                        <!-- issue: don't forget to make the files downloadable -->
                        <p>
                            {{csrf_field()}}
                                <div class="form-group">
                                    <label for="project_name" class="control-label ">Project Name</label>
                                    <input id="project_name" name="name" type="text" class="form-control"
                                    placeholder = "e.g My first mobile app" aria-required="true" aria-invalid="false" required>
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
