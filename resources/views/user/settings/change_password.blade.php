@extends('layouts.auth-master')

@section('form_content')

    <form action = '/login' method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" class="form-control" name = "old_password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" class="form-control" name = "new_password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name = "confirm_password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Change Password</button>
    </form>

@endsection
