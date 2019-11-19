@extends('layouts.auth-master')

@section('form_content')

    <form action="/users/new/user" method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label>User Name</label>
            <input type="text" name='name' class="form-control" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
        <div class="register-link m-t-15 text-center">
            <p>Already have account ? <a href="/login"> Sign in</a></p>
        </div>
    </form>

    @if (count($errors))
        <div class="alert-danger container" style="margin:2%;">
                <ul>
                    @foreach ($errors->all() as $error )
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
        </div>
    @endif

@endsection
