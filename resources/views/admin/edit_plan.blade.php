@extends('layouts.admin-master')

@section('right-panel')
<div class="card-header user-header alt">
        <div class="media">
            
        </div>
    </div>
<div id="root" class="container" style="margin-top:15px">

            <div class="card">
            <div class="card-header">Make changes to the "{{ $plan->name }}" plan</div>
                            <div class="card-body">
                                <form action="/admin/plans/{{ $plan->id }}/change" method="post" novalidate="novalidate">
                                    @csrf
                                        <div class="form-group">
                                                <label for="name" class="control-label mb-1">Name</label>
                                                <input id="name" name="name" type="text" class="form-control" value="{{ $plan->name}}">
                                            </div>
                                    <div class="form-group">
                                        <label for="projects" class="control-label mb-1">Projects</label>
                                        <input id="projects" name="projects" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $plan->projects }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="storage" class="control-label mb-1">Storage Limit (GB)</label>
                                        <input id="storage" name="storage_limit" type="text" class="form-control" aria-required="true" value="{{ $plan->storage_limit }}">
                                    </div>

                                    <div class="form-group">
                                            <label for="store" class="control-label mb-1">Data Store Size (MB)</label>
                                            <input id="store" name="store_limit" type="text" class="form-control" value="{{ $plan->store_limit }}">
                                        </div>

                                    <div>
                                        <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                            <i class="fa fa-check"></i>
                                            <span>Save Changes</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

</div>


    <div class="modal fade" id="suspendmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
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
                        Note that this account will not be able to serve requests until you activate it
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                    <form action="/admin/suspend" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="suspend_user" value="1"/>
                        <input type="hidden" name="user_id" />
                        <button type="submit" class="btn btn-warning">I'm aware</button> <!--issue: implement delete user account -->
                    </form>
                </div>

            </div>
        </div>
    </div>



@endsection
