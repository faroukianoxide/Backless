    @extends('layouts.user-master')

    @section('right-panel')

    @if (count($errors))
<div class="alert  alert-success alert-dismissible fade show" role="alert">

        @foreach ($errors->all() as $error)

        @if ($error == 'migrated')
            You have successfully been migrated
            
        @endif
            
            
        @endforeach

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
@endif
    
    <div id="root" class="container" style="margin-top:15px">
            <div class="row mx-4">
                
        @foreach ($plans as $plan )

            

            
                <div class="col">
                    <aside class="profile-nav alt">
                        <section class="card">
                            <div class="card-header user-header alt bg-dark">
                                <div class="media">
                                    <div class="media-body">
                                    <h2 class="text-light display-6">{{$plan->name}}</h2>

                                    @if ($plan->id == Auth::user()->plan_id)
                                    <i class="fa fa-check   " style="color:white"></i>
                                    @endif
                                    </div>
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
                                        <i class="fa fa-save"></i> Storage Limit (GB) <span class="pull-right">{{$plan->storage_limit}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fa fa-th-list"></i> DataStore Limit (MB) <span class="pull-right">{{$plan->store_limit}}</span>
                                            </li>
                                   

                            </ul>

                            
                            @if ($plan->id != Auth::user()->plan_id)
                            
                            

                                <div class="card-footer">

                                <button type="reset" class="btn btn-primary btn-sm" onclick="showMigrationModal({{ $plan->id }})">
                                        <i class="fa fa-check"></i> Subscribe
                                        
                                </button>
                                    <!-- issue: all admin routes must be admin protected-->
                                    <!-- since we now have suspension and deletion, an account has to be extra verified before login -->
                                </div>
                            @endif
                        

                        </section>
                    </aside>

                </div>
                @endforeach
            </div>

            <div class="modal fade" id="migrationModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" data-backdrop = "static"
            aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
    
                            <div class="card">
                                    <div class="card-header">Migrate to this plan</div>
                                    <div class="card-body">
                                        <form action="/user/plans/migrate" method="post" novalidate="novalidate">
                                            @csrf
                                                Are you sure you want to migrate to this plan?
                                                <input type="hidden" name="plan_id" id="mig_plan"/>                                                    
                                            <div>
                                                    <div class="pull-right">
                                           
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-seconday" >
                                                                    <i class="fa fa-check"></i> Yes
                                                                </button>
                                                                
                                                            
                                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>


            <script>

                function showMigrationModal (plan_id) {
                    jQuery.noConflict();
                    jQuery('#mig_plan').val(plan_id);
                    jQuery('#migrationModal').modal('show');
                }
            </script>

    </div>

    @endsection
