@extends('layouts.project-master')


@section('right-panel')

<div class="main">


        <form action="/projects/{{$project_id}}/rename" method="POST">
            @csrf
            <div class="card mx-4 my-4">
                <div class="card-header">
                    <span>Change Project Name</span>
                </div>
            <div class="card-body">
                <p>
                    {{csrf_field()}}
                        <div class="form-group">
                            <label for="name" class="control-label ">Enter new project name</label>
                            <input type="hidden" name="project_id" value="{{ $project_id }}"/>
                            <input id="name" name="name" type="text" class="form-control"
                            value="{{ $name }}"
                            aria-required="true" aria-invalid="false" required>
                        </div>
                </p>        
    
    
                </p>
            </div>
            <div class="card-footer">
              
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Rename</button>
            </div>
            </div>
        </form>

   
    <form action="/projects/{{$project_id}}/delete" method="POST">
        @csrf
        <div class="card mx-4 my-4">
        <div class="card-header">
                <i class="menu-icon fa fa-trash" style="color:red"></i>
                <span style="color:red">Delete Project </span></a>
        </div>
        <div class="card-body">
            <p>
                {{csrf_field()}}
                    <div class="form-group">
                        <label for="name" class="control-label ">Enter the name of the project </label>
                        <input type="hidden" name="project_id" value="{{ $project_id }}"/>
                        <input id="name" name="name" type="text" class="form-control"
                        aria-required="true" aria-invalid="false" oninput="change(this.value)" required>
                    </div>
            </p>        
            <p style="color:red;display:none" id="assurance">
            Every record relating to this project will be erased. Data, files, events, everything.
        </p>



            </p>
        </div>
        <div style = "display:none" class="card-footer" id="options">
          
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
        </div>
    </form>
</div>

<script>
    function change (value) {
        if (value.toLowerCase() == '{{ $name }}'.toLowerCase()) {
            document.getElementById('assurance').style.display = "block";
            document.getElementById('options').style.display = "block";
        }else {
            document.getElementById('assurance').style.display = "none";
            document.getElementById('options').style.display = "none";
        }
    }
</script>
@endsection
