@extends('layouts.auth-master')

@section('form_content')

    <form action = '/admin/login' method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name = "password" placeholder="Password" required>
        </div>
        <button type="submit"  class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
    </form>

@endsection
