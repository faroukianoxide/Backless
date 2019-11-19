@extends('layouts.project-master')

@section('right-panel')
<div class="main">

    @if (count($errors))
    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
        @if ($errors->has('json_error'))
        <span class="badge badge-pill badge-danger">Error</span> {{$errors->first('json_error')}}
        <br />
        @endif
        @foreach ($errors->all() as $error)
        <span class="badge badge-pill badge-danger">Error</span> {{$error}}
        <br />
        @endforeach

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    @endif

    <div class="card mx-4 my-4">
        <div class="card-header">
            <strong>Authorization Details</strong> "{{session('projectName')}}"
            <span> - {{ count($auths) }} in total</span>
        </div>
        <div class="card-body">
            <table class="table table-condensed table-hover">

                <tbody>
                    <?php $sn = 0; ?>
                    @foreach ($auths as $auth )
                    <tr class="table-row sortable" style="cursor: pointer;"
                        onclisck="window.location = '/user/data/{{$auth->id}}' ">
                        <!-- change this to html code and not javascript -->
                        <td>

                            <div class="menus-item-has-children dropdown">
                                <span href="#" style="cursor: pointer;" class="dropdown-togsgle" data-toggle="dropdown"
                                    aria-haspopup="truse" aria-expanded="false">
                                    <i class="menu-icon fa fa-plus-square"></i> id:{{$auth->id}}</span>
                                <br />
                                <div class="sub-menu children dropdown-menu px-4" style="width:inherit">

                                    <pre><code class="language-json">{{$auth->data}}</code></pre>
                                </div>
                            </div>
                        </td>
                        <td><i class="fa fa-clock"></i> <i>{{$auth->created_at}}</i></td>
                        <td><span onclick="confirmDeletion('{{$auth->id}}')"><i class="fa fa-trash"></i> Delete</span>
                        </td>


                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- START SCROLL MODAL -->
    <div class="modal fade" id="deletionModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel"
        data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollmodalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/user/data/new" method="POST">
                    <div class="modal-body">
                        <p>
                            Are you sure you want to delete this record?

                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="deleteAuth()">Yes</button>
                        <button type="submit" class="btn btn-primary" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END SCROLL MODAL -->
</div>

<script>
    var auth_id

    function confirmDeletion(id) {
        auth_id = id;
        jQuery.noConflict();
        jQuery("#deletionModal").modal("show")

    }

    function deleteAuth() {

        jQuery.ajaxSetup({
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
            }
        });

        jQuery.ajax({
            type: "POST",
            url: "/projects/{{ $project_id }}/auths/"+auth_id+"/delete",
            data: {auth_id: auth_id},

            success: function (response){
                window.location = window.location;
       }
        })

    }

</script>

@endsection