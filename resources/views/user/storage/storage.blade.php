@extends('layouts.project-master')


@section('right-panel')

<div class="main">
    <form method="post" 
    action="/projects/{{ $project_id}}/storage/upload?mode=web&path={{ $path }}" 
    enctype="multipart/form-data" id="fileForm">
        {{csrf_field()}}
        <input type="file" id = "uploadFileButton" name = "file" style="display: none" 
        onchange="document.getElementById('fileForm').submit();">
      </form>

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

        <div class="row mx-4">

                <div class="col-sm-6 sm-md-12">
                    <strong>Folder</strong> {{ $path }}
                   <!-- <input type="search" class="form-control" placeholder="Filter projects" aria-controls="bootstrap-data-table-export">
                    </div><button class="btn borderless mx-2"><i class="fa fa-search"></i></button> -->
                    <button class="btn pull-right" 
                    onclick = "document.getElementById('uploadFileButton').dispatchEvent(new MouseEvent('click'))">
                    <i class="fa fa-upload"></i> Upload</button>
                    <button class="mx-2 btn pull-right" data-toggle="modal" data-target="#createFolderModal">
                        <i class="fa fa-folder"></i> New Folder</button></div>

        </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <th>Name</th>
                <th>Date Created</th>
                <th>Asset ID</th>
                <th>Actions</th>

            </thead>




            <tbody>

                @if (count($assets) == 0 && count($folders) == 0)

                <td colspan="4">
                    <div style="text-align:center">
                        Nothing is in this folder
                    </div>
                </td>
                @endif  

                    @foreach ($folders as $folder )

                    <tr style="cursor:context-menu"><td>
                        <i class="fa fa-folder"></i> <strong>{{$folder->name}}</strong>
                        </td>
                    <td>{{ $folder->created_at }}</td>
                    <td><strong>NA</strong></td>
                   
                    <td>
                        <a href="/projects/{{ $project_id }}/storage?path={{ $folder->path }}">
                        <i class="fa fa-folder-open"></i></a>  
                        <a onclick="deleteFolder('{{ $folder->path }}')"><i class="fa fa-trash"></i></a>
                        <a onclick="renameFolder()"><i class="fa fa-edit"></i></a>
                    </td>
                    </tr>

                @endforeach
                @foreach ($assets as $asset )

                    <tr><td>
                        <i class="fa fa-file"></i> <strong>{{$asset->file_native_name}}</strong>
                        </td>
                    <td> <i>{{ $asset->created_at }}</i></td>
                    <td>{{ $asset->access_url }}</td>
                    <td>
                        <a href="storage/download/{{ $asset->id }}"><i class="fa fa-download"></i></a>
                        <a class = "btn" onclick="deleteFile('{{ $asset->id }}')"><i class="fa fa-trash"></i></a>
                        
                    </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    </div>


</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
aria-hidden="true" style="margin-top:150px;">
   <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="scrollmodalLabel"><i class="fa fa-folder"></i>Create a new folder</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <form action="/projects/{{ $project_id }}/folder/new" method="POST">
               <div class="modal-body">
                   <!-- issue: don't forget to make the files downloadable -->
                   <p>
                       {{csrf_field()}}
                           <div class="form-group">
                               <label for="name" class="control-label ">Folder Name</label>
                               <input id="name" name="name" type="text" class="form-control"
                               aria-required="true" aria-invalid="false" required>
                               Create folder in folder '{{ $path }}'
                               <input type="hidden" name="parent" value="{{ $path }}"/>
                           </div>
                   </p>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                   <button type="submit" class="btn btn-primary">Create</button>
               </div>
           </form>
       </div>
   </div>
</div>
<!-- END Create Folder Modal -->

<!-- START DELETE FOLDER  MODAL -->
<div class="modal fade" id="deleteFolderModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
aria-hidden="true" style="padding-right: 10px;">
   <div class="modal-dialog modal-sg" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="scrollmodalLabel">Delete Folder</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
          
               <div class="modal-body">
                   
                   <p>
                       Are you sure you want to delete this folder?
                   </p>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                   <button onclick="makeDeleteFolder()" class="btn btn-primary">Yes</button>
               </div>
         
       </div>
   </div>
</div>
<!-- END DELETE FOLDER MODAL -->

<!-- START DELETE FOLDER  MODAL -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
aria-hidden="true" style="padding-right: 10px;">
   <div class="modal-dialog modal-sg" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="scrollmodalLabel">Delete File</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
          
               <div class="modal-body">
                   
                   <p>
                       Are you sure you want to delete this file?
                   </p>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                   <button onclick="makeDeleteFile()" class="btn btn-primary">Yes</button>
               </div>
         
       </div>
   </div>
</div>
<!-- END DELETE FOLDER MODAL -->
<script>
let folderPath;
let fileID;

function deleteFolder (path) {
    folderPath = path;
    jQuery.noConflict();
    jQuery('#deleteFolderModal').modal('show');
}
function makeDeleteFolder () {

   jQuery.noConflict();
   jQuery.ajaxSetup({
       beforeSend: function (xhr) {
           xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
       }
   });
   jQuery.ajax({
       url: "storage/delete/folder",
       type: 'POST',
       data: {type: 'web', folder_path: folderPath},
       success: function (error){
           window.location = window.location;
       }
   });
}

function deleteFile (id) {

    fileID = id;
    jQuery.noConflict();
    jQuery('#deleteFileModal').modal('show');
}

function makeDeleteFile () {

   jQuery.noConflict();
   jQuery.ajaxSetup({
       beforeSend: function (xhr) {
           xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
       }
   });
   jQuery.ajax({
       url: "storage/delete/file",
       type: 'POST',
       data: {type: 'web', file_id: fileID},
       success: function (response){
           window.location = window.location;
       }
   });
    
}

function renameFolder (id) {
    folderID = id;
    jQuery.noConflict();
    jQuery('#deleteFolderModal').modal('show');
}
</script>
@endsection
