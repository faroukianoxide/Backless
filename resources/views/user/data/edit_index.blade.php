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
    

        <div class="card mx-4" style="" >
            <div class="card-content">
                <div class="card-header">
                    <h5 class="card-title" id="scrollmodalLabel">Reconfigure this index</h5>
                </div>
                <form action="/projects/{{ $project_id }}/indexes/{{ $id }}/change" method="POST">
                    @csrf
                    <div class="card-body">
                        <p>
                                <div class="form-group">
                                        <label for="name" class="control-label ">Name</label>
    
                                        <input id="name" name="name" type="text" class="form-control"
                                        oninput="change(this.value)" aria-required="true" aria-invalid="false" 
                                value = "{{ $index->name }}"required>
                                        <small id="space_notifi"></small>
                                    </div>
                                    <div class="row form-group">
                                            <div class="col col-md-9">
                                                <label for="mapType" class=" form-control-label">Map Type</label>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                
                                                <select name="map_type" id="select" class="form-control">
                                                    
                                                    <option value="data"
                                                    @if ($index->map_type == 'data')
                                                    selected
                                                    @endif
                                                    >Data Map</option>
                                                    
                                                    <option value="storage"
                                                    @if ($index->map_type == 'storage')
                                                    selected
                                                    @endif
                                                    >Storage Map</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="id" class="control-label ">Object ID</label>
            
                                                <input id="id" name="id" type="text" class="form-control"
                                                aria-required="true" aria-invalid="false" required
                                                value="{{ $index->maps_to }}"
                                                >
                                            
                                            </div>
                                            <div class="row form-group">
                                                    
                                                    <div class="col col-md-9">
                                                        <div class="form-check">
                                                            <div class="checkbox">
                                                                <label for="is_auth" class="form-check-label ">
                                                                    <input type="checkbox" id="is_auth" name="is_auth" value="1" class="form-check-input"
                                                                    @if ($index->is_authed == 1) 
                                                                    checked
                                                                    @endif
                                                                    >Authorise
                                                                </label>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                        </p>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button  type = "reset" class="btn btn-danger pull-right" data-toggle="modal" data-target="#deletemodal">Delete</button>
                    </div>
                </form>
            </div>
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
                       Are you sure you want to permanently delete this index map? This cannot be undone.
                        
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <form action="/projects/{{ $project_id }}/indexes/delete/" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="delete_user" value="1" />
                        <input type="hidden" name="index_id" value="{{ $id }}"/>
                        
                        <button type="submit" class="btn btn-danger">I'm aware</button> <!--issue: implement delete user account -->
                    </form>
                </div>
                <!-- issue: add pagination to tables -->

            </div>
        </div>
    </div>


<script>
    function change (value) {
       var spaceNotifi =  document.getElementById('space_notifi');
        if (value.indexOf(' ')>0){
            spaceNotifi.style.color = 'red';
            spaceNotifi.innerHTML = "The name should not contain spaces";

        }else{
            spaceNotifi.style.color = 'black';
            spaceNotifi.innerHTML = "";
        }

    }
</script>

@endsection
