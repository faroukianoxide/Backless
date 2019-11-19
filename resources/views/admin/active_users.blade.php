@extends('layouts.admin-master')

@section('right-panel')
<div class="main text-center">
    <div class="card-body">
        <table class="table table-condensed table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Date Created</th>
                    <!-- issue: you might want to rethink the clickablility of the table -->
                </tr>
            </thead>
            <tbody>
                <?php $sn = 0; ?>
                @foreach ($users as $user )
                <!--issue: dont forget to always give notification of any actions performed -->

                    <tr class="table-row sortable" style = "cursor: pointer;" title = {{$user->name}}
                        onclick="window.location = '/admin/users/active/{{$user->id}}' "> <!-- issue change this to html code and not javascript -->
                        <!-- issue: i'm thinking to user somthing like user/media instead of just /media as above -->
                        <!-- issue: sha remember to change all the hardcoded routes -->
                        <th scope="row">{{++$sn}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->created_at}}</td>

                        <!-- create actions button -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<!-- I may not need to create a token on signingup through API -->
@endsection
