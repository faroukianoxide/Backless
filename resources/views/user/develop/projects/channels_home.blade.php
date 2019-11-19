@extends('layouts.project-master')


@section('right-panel')

<div class="main">

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
        <span>All Channels for</span> "{{session('projectName')}}"
        <button class="btn pull-right"  data-toggle="modal" data-target="#createModal"><i class="fa fa-plus"></i> New</button>
        </div>
    <div class="card-body">
        
        <table class="table table-condensed">
            <thead>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>

            </thead>


            <tbody>
                    @if (count($channels) == 0)
                    <td colspan="3">
                        <div style="text-align:center">
                            You haven't created any realtime channel yet
                        </div>
                    </td>
                @endif
                <?php $sn = 0; ?>
                @foreach ($channels as $channel )
                    <tr>
                    
                    <td>{{$channel->channel}}</td>
                    <td>
                            <div class="menus-item-has-children dropdown">
                                <span href="#" style = "cursor: pointer;" class="dropdown-togsgle" data-toggle="dropdown" aria-haspopup="truse" aria-expanded="false">
                                    <i class="menu-icon fa fa-eye"></i> <i>{{ substr($channel->description, 0, 20).'....' }}</i></span>
                                <br/><div class="sub-menu children dropdown-menu px-4" style="width:inherit">
    
                                <pre><code class="language-json">{{$channel->description}}</code></pre>
                                </div>
                            </div></td>
                    <td>
                    
                        <span></span>
                        <button class="border-none item-button" 
                        onclick="openChangeModal('{{ $channel->channel  }}', 
                        '{{  $channel->description  }}', <?php echo $channel->id?> )">
                            <i class="fa fa-edit"></i></button></td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    <!-- START CREATE MODAL -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
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
                                <label for="name" class="control-label ">Name</label>

                                <input id="name" name="name" type="text" class="form-control"
                                oninput="change(this.value)" aria-required="true" aria-invalid="false" required>
                                <small id="space_notifi"></small>
                            </div>

                            <div class="form-group">
                                    <label for="description" class="control-label ">Description</label>
    
                                    <input id="description" name="description" type="text" class="form-control"
                                     aria-required="true" aria-invalid="false" required>
                                    
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
    <!-- END CREATE MODAL -->
    <!-- START CHANGE MODAL -->
    <div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
     aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel"><i class="fa fa-change"></i> Change channel properties</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form action="/projects/{{ $project_id}}/channels/change" method="POST">
                    <div class="modal-body">
                        <p>
                            {{csrf_field()}}
                            <input type="hidden" name="channel_id" id="channel_id" value="-1"/>
                            <div class="form-group">
                                <label for="change_name" class="control-label ">Name</label>

                                <input id="change_name" name="name" type="text" class="form-control"
                                oninput="change(this.value)" aria-required="true" aria-invalid="false" required>
                                <small id="space_notifi"></small>
                            </div>

                            <div class="form-group">
                                    <label for="change_description" class="control-label ">Description</label>
    
                                    <input id="change_description" name="description" type="text" class="form-control"
                                     aria-required="true" aria-invalid="false" required>
                                    
                                </div>

                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END CHANGE MODAL -->
</div>

<script>
    function change (value) {
       var spaceNotifi =  document.getElementById('space_notifi');
        if (value.indexOf(' ')>0){
            spaceNotifi.style.color = 'red';
            spaceNotifi.innerHTML = "channel name should not contain spaces";

        }else{
            spaceNotifi.style.color = 'black';
            spaceNotifi.innerHTML = "";
        }

    }

    function openChangeModal (channel, description, id) {
        jQuery.noConflict();
        jQuery('#change_name').val(channel);
        jQuery('#change_description').val(description);
        jQuery('#channel_id').val(id);
        jQuery('#changeModal').modal('show');
    }
</script>

@endsection
