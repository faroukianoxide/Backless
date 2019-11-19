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
        <strong>Indexed Maps</strong>
        <button class="btn pull-right"  data-toggle="modal" data-target="#scrollmodal">
            <i class="fa fa-plus"></i> New</button>
        </div>
    <div class="card-body">
        
        <table class="table table-condensed table-hover">
            <thead>
                <th>Name</th>
                <th>Map Type</th>
                <th>Maps to </th>
                <th>Auth Index </th>
                <th>Action</th>
            </thead>
            <tbody>
                
                @if (count($indexes) == 0)
                <td colspan="5">
                    <div style="text-align:center">
                        No index maps yet. Create one.
                    </div>
                </td>
                    @endif    
                <?php $sn = 0; ?>
                @foreach ($indexes as $index)
                    <tr><td >
                        {{ $index->name }}
                        </td>
                        <td>
                           {{ $index->map_type }}
                        </td>

                    <td>{{ $index->maps_to }}</td>
                    <td>
                    @if ($index->is_authed == 0)
                        {{ 'FALSE' }}
                    @else
                        {{ 'TRUE' }}
                    </td>
                    @endif
                    <td><a href="/projects/{{ $project_id }}/indexes/{{ $index->id }}"><i class="fa fa-edit"></i> Edit</span></td>
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
                    <h5 class="modal-title" id="scrollmodalLabel">Create a new index map</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/projects/{{ $project_id }}/indexes/new" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>
                                <div class="form-group">
                                        <label for="name" class="control-label ">Name</label>
    
                                        <input id="name" name="name" type="text" class="form-control"
                                        oninput="change(this.value)" aria-required="true" aria-invalid="false" required>
                                        <small id="space_notifi"></small>
                                    </div>
                                    <div class="row form-group">
                                            <div class="col col-md-9">
                                                <label for="mapType" class=" form-control-label">Map Type</label>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                <select name="map_type" id="select" class="form-control">
                                                    <option value="data">Data Map</option>
                                                    
                                                    <option value="storage">Storage Map</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="id" class="control-label ">Object ID</label>
            
                                                <input id="id" name="id" type="text" class="form-control"
                                                aria-required="true" aria-invalid="false" required>
                                            
                                            </div>
                                            <div class="row form-group">
                                                    
                                                    <div class="col col-md-9">
                                                        <div class="form-check">
                                                            <div class="checkbox">
                                                                <label for="is_auth" class="form-check-label ">
                                                                    <input type="checkbox" id="is_auth" name="is_auth" value="1" class="form-check-input">Authorise
                                                                </label>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                        </p>
                    </div>
                    <div class="modal-footer" id="options">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END SCROLL MODAL -->
</div>

<script>
    function change (value) {
       var spaceNotifi =  document.getElementById('space_notifi');
       var options = document.getElementById('options');
        if (value.indexOf(' ')>0){
            spaceNotifi.style.color = 'red';
            spaceNotifi.innerHTML = "The name should not contain spaces";
            options.style.visibility = "hidden";

        }else{
            spaceNotifi.style.color = 'black';
            spaceNotifi.innerHTML = "";
            options.style.visibility = "visible";
        }

    }
</script>

@endsection
