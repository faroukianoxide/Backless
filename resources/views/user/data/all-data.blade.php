@extends('layouts.project-master')


@section('right-panel')

<div class="main">
    
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
    <div class="card mx-4 my-4">
    <div class="card-header">
        <strong>Data Store</strong> "{{session('projectName')}}"
        <button class="btn pull-right"
        data-toggle="modal" data-target="#newDataModal">
         <i class="fa fa-plus"></i> New</button>
        </div>
    <div class="card-body">
        <table class="table table-condensed table-hover" >

            @if (count($records) == 0)
                <div class="lead" style="text-align:center">
                    Your data store is empty!
                </div>
            @endif

            <tbody>
                <?php $sn = 0; ?>
                
                @foreach ($records as $record )
                    <tr><td colspan="5">
                        <div class="menus-item-has-children dropdown">
                            <span href="#" style = "cursor: pointer;" class="dropdown-togsgle" data-toggle="dropdown" aria-haspopup="truse" aria-expanded="false">
                                <i class="menu-icon fa fa-plus-square"></i> id:{{$record->id}}</span>
                            <br/><div class="sub-menu children dropdown-menu px-4" style="width:inherit">

                            <pre><code class="language-json">{{$record->content}}</code></pre>
                            </div>
                        </div></td>
                        <td>
                            @if ($record->listener != null)
                            <i class="fa fa-bell "></i> <i> {{ $record->listener}}</i>
                            @else
                            <i class="fa fa-unlink"></i> Non-Broadcasting 
                            @endif
                        </td>

                    <td><a href="/projects/{{ $project_id}}/data/{{ $record->id}}"><i class="fa fa-edit"></i> Change</a></td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    <!-- START SCROLL MODAL -->
    <div class="modal fade" id="newDataModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
     aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">CREATE NEW DATA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/projects/{{ $project_id }}/data/new" method="POST">
                    <div class="modal-body">
                        <p>
                            {{csrf_field()}}
                                
                                <div class="form-group">
                                    <label for="content" class="control-label "> <i class="fa fa-list"></i> Content</label>
                                    <textarea id="content" name="content" class="form-control"
                                    placeholder = 'JSON e.g {"name":"chales", "email":"ibrahim", "password":"tweeter"}' aria-required="true" aria-invalid="false"></textarea>
                                </div>                   

                                <div class="form-group">
                                    <label for="listener" class="control-label "><i class="fa fa-bell"></i> State Change Event</label>
                                    <input id="listener" name="listener" type="text" class="form-control"
                                    aria-required="true" aria-invalid="false" placeholder="channel.event">
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
