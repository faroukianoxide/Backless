    @extends('layouts.admin-master')

    @section('right-panel')
    <div class="card-header user-header alt">
            <div class="media">
                <div class="media-body">
                <button class="btn pull-right"  data-toggle="modal" data-target="#createmodal"><i class="fa fa-plus"></i> New</button>
                </div>
            </div>
        </div>
    <div id="root" class="container" style="margin-top:15px">
            <div class="row">
        @foreach ($plans as $plan )

            
                <div class="col-md-6">
                    <div class="profile-nav alt">
                        <div class="card">
                            <div class="card-header user-header alt bg-dark">
                                <div class="media">
                                    <div class="media-body">
                                    <h2 class="text-light">{{ $plan->name}}</h2>
                                    @if ($plan->id == 1) 
                                     <span class="badge badge-success">Default</span>
                                    @endif
                                    </div>
                                    <span class="text-light"> <i class="fa fa-users"></i>
                                        <span class="badge badge-success">{{$subscribers[$plan->id]}}</span>
                                    </span>
                                </div>
                            </div>


                            <ul class="list-group list-group-flush">
                                 <li class="list-group-item">
                                 <i class="fa fa-briefcase"></i> Projects <span class=" pull-right ">
                                     @if ($plan->projects == -1)
                                        {{'Unlimited'}}
                                    @else
                                     {{$plan->projects}}
                                    @endif
                                    </span>
                                 </li>
                            
                                    <li class="list-group-item">
                                        <i class="fa fa-hdd-o"></i> Storage Limit (GB) <span class="pull-right">{{$plan->storage_limit}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fa fa-calendar-o"></i> DataStore Limit (MB) <span class="pull-right">{{$plan->store_limit}}</span>
                                            </li>
                                    
                                    <li class="list-group-item">
                                        <i class="fa fa-calendar"></i> Date Created <span class="pull-right">{{ $plan->created_at}}</span>
                                        </li>
                                
                                
                            </ul>

                            <div class="card-footer">

                            <a type="reset" class="btn btn-warning btn-sm" href="/admin/plans/{{$plan->id}}">
                                    <i class="fa fa-dot-circle-o"></i> Edit Plan
                                </a>
                                @if ($plan->id != 1)
                                <button type="reset" class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $plan->id }})">
                                    <i class="fa fa-ban"></i> Delete Plan
                                </button>
                                @endif
                            
                            </div>

                        </div>
                    </div>

                </div>
                @endforeach
            </div>

       

    </div>

    <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
        aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                        <div class="card">
                                <div class="card-header">Create a new plan</div>
                                <div class="card-body">
                                    <form action="/admin/plans/new" method="post" novalidate="novalidate">
                                        @csrf
                                            <div class="form-group">
                                                    <label for="name" class="control-label mb-1">Name</label>
                                                    <input id="name" name="name" type="text" class="form-control" aria-invalid="false">
                                                </div>
                                        <div class="form-group">
                                            <label for="projects" class="control-label mb-1"> Projects</label>
                                            <input id="projects" name="projects" type="text" class="form-control" aria-required="true" aria-invalid="false" value="10">
                                        </div>
                                        <div class="form-group">
                                            <label for="storage" class="control-label mb-1"> Storage Limit (GB)</label>
                                            <input id="storage" name="storage_limit" type="text" class="form-control" aria-required="true" aria-invalid="false">
                                        </div>

                                        <div class="form-group">
                                                <label for="store" class="control-label mb-1">Data Store Size (MB)</label>
                                                <input id="store" name="store_limit" type="text" class="form-control" aria-invalid="false">
                                            </div>
                                                
                                        <div>
                                            <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                                <i class="fa fa-check"></i>
                                                <span>Create Plan</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
        aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                        <div class="card">
                                <div class="card-header">Delete Plan</div>
                                <div class="card-body">
                                    <form action="/admin/plans/delete" method="post" novalidate="novalidate">
                                        @csrf
                                        <p>
                                           You are about to delete a plan on which some users might be on. Are you sure 
                                           you really want to do this, it cannot be undone?
                                           
                                        </p>
                                        <input type="hidden" id="plan_id" name="plan_id"/>

                                    <div class="pull-right">
                                           
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger" >
                                                    <i class="fa fa-ban"></i> Yes
                                                </button>
                                                
                                            
                                            </div>
                                        </form>
                                    
                                </div>

                               
                                
            
                            </div>

                            
                </div>
            </div>
        </div>

        <script>


            function showDeleteModal (plan_id) {

                jQuery('#plan_id').val(plan_id);
                jQuery('#deleteModal').modal('show');

            }
        
        </script>

    @endsection
