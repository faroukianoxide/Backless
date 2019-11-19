@extends('layouts.auth-master')

@section('form_content')

    <form action = '/admin/change_password' method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" class="form-control" name = "old_password" placeholder="Old Password" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" class="form-control" name = "new_password" placeholder= "New Password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name = "confirm_new_password" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Change Password</button>
    </form>

    <hr/>
    <a class = "btn btn-small btn-primary" href = {{route('Dashboard')}}> Go home </a>
    @if ($message != null)

        @if ($message == 'success')
            <div class="alert alert-success">
                Your Password has been changed successfully
            </div>
        @else
            <div class="alert alert-danger">
                Your old password did not match
            </div>
        @endif

    @endif

    @if (count($errors))
        <div class = "alert alert-danger">
            <ul class="container">
                @foreach ($errors->all() as $error )
                    <li>{{$error}}</li>
                @endforeach

            </ul>

        </div>
    @endif

@endsection
