@extends('layouts.user-master')

@section('right-panel')

@if (count($errors))
<div class="alert  alert-success alert-dismissible fade show" role="alert">

        @foreach ($errors->all() as $error)

        @if ($error == 'project_deleted')
            The project has been deleted
            
        @endif
            
            
        @endforeach

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
@endif


    <div class="content mt-3">

            <div class="row">
                    <div class="col">
                        <div class="card text-white bg-flat-color-1">
                            <div class="card-body pb-0">
                                    <p class="text-light">Data Store Usage</p>
                                <h4 class="mb-0">
                                        <span style="font-size: 30px">
                                    @if ($planUsage['store']<1024)
                                    {{ ($planUsage['store']/1000) }}
                                    
                                    @else
                                    {{ ($planUsage['store']) }}</span>
                                    @endif
                                                                       
                                </h4>
                                <span>MB</span>
                                
                                
                            </div>
            
                        </div>
                    </div>
                    <!--/.col-->
            
                    <div class="col">
                        <div class="card text-white bg-flat-color-2">
                            <div class="card-body pb-0">
                                <h4 class="mb-0">
                                    <p class="text-light">Storage Used</p>
                                        <span style="font-size: 30px">
                                    @if ($planUsage['storage']< (1024*1024))
                                    {{ round($planUsage['storage']/(1024*1024), 4)}}
                                    @else
                                    {{ round($usassge[1], 4) }}
                                    @endif

                                     
                                    
                                </h4>
                                <span>GB</span>
                                
            
                            </div>
                        </div>
                    </div>
                    <!--/.col-->
                    </div>

        
        <!-- BEGIN LOGS -->
        <div class="content mt-3">
            <div class="col">
                <aside class="profile-nav alt">
                    @if (count($projects) == 0) 
                        <section class="card">
                    @else
                        <section>
                    @endif
                        <div class="card-header user-header alt bg-dark">
                            <div class="media">
                                <div class="media-body">
                                    <span class="text-light display-6">Your projects
                                        @if (count($projects))
                                            - {{count($projects)}} in total
                                        @endif

                                    </span>
                                    
                                </div>
                                <button type="button" class="btn  mb-1 fa fa-plus" style="border-radius:10px"
                                    data-toggle="modal" data-target="#createprojectmodal">
                                    New
                                </button>
                            </div>
                        </div>
                        
                        <div class="row my-4">
                        @foreach ($projects as $project )


                        
                            <div class="col-md-4">
                                <div class="card">
                                    <a href="/projects/{{ $project->id }}" style="width:100%;height:70%;
                                    display:block;position: absolute;left:0;top:0;text-decoration:none;z-index:2;
                                    "></a>

                                    <div class="card-body">
                                        <div class="mx-auto d-block">

                                            

                                            <h5 class="text-sm-center mt-2 mb-1">{{ $project->name }}</h5><br />
                                            <div class="text-sm-center">ID: {{ $project->id }}</div><br/>
                                            <div class="text-sm-center">Created: {{ $project->created_at }}</div>
                                            


                                            <div class="text-sm-center">
                                                Data store:
                                            @if ($usage[$project->id][0]<1024)
                                            {{ ($usage[$project->id][0]/1000).'MB' }}
                                            @else
                                            {{ ($usage[$project->id][0]).'MB' }}
                                            @endif
                                            </div>

                                            <!-- display storage -->
                                            <div class="text-sm-center">
                                                Storage:
                                            @if ($usage[$project->id][1]< (1024*1024))
                                            {{ round($usage[$project->id][1]/(1024*1024), 4).'GB' }}
                                            @else
                                            {{ round($usage[$project->id][1], 4).'GB' }}
                                            @endif
                                            </div>
                                            

                                        </div>

                                        <div class="card-text text-sm-center">
                                            <a class="btn pull-right" 
                                            href="/projects/{{ $project->id }}/settings"><i class="fa fa-gear"></i></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @if (count($projects) == 0)
                                <div class="card-body" style="text-align: center">
                                    <span style="font-size: 20px; font-family:'Lucida Sans', 'Lucida Sans Regular'">
                                        You have no projects yet. Now make something great!!!</span><br/>
                                        <button class="btn btn-lg" data-toggle="modal" data-target="#createprojectmodal"><strong>Create project</strong></button>
                                </div>
                            @else

                            <div class="col-md-4">
                                <button class="card text-white bg-flat-color-1 btn" style="height:90%;
                                padding-top:30px;padding-left:20px" data-toggle="modal" data-target="#createprojectmodal">

                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <i class="fa fa-plus"></i>
                                            <span>Create new project</span>

                                        </div>


                                    </div>
                                </button>
                            </div>
                            @endif
                        </div>


                    </section>
                </aside>

            </div>
        </div>
        <!-- END LOGS -->






    </div> <!-- .content -->

    <!-- START SCROLL MODAL -->
    <div class="modal fade" id="createprojectmodal" tabindex="-1" role="dialog" aria-labelledby="createprojectmodalLabel"
        data-backdrop="static" aria-hidden="true" style="margin-top:150px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createprojectmodalLabel">CREATE A NEW PROJECT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/projects/new" method="POST">
                    <div class="modal-body">
                        <!-- issue: don't forget to make the files downloadable -->
                        <p>
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="project_name" class="control-label ">Project Name</label>
                                <input id="project_name" name="name" type="text" class="form-control"
                                    placeholder="e.g My first mobile app" aria-required="true" aria-invalid="false"
                                    required>
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

</div><!-- /#right-panel -->

@endsection