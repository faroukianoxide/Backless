@extends('layouts.auth-master')

@section('form_content')

    <form action = '/login' method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label>Email address</label>
            <input type="email" class="form-control" name ="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name = "password" placeholder="Password" required>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox"> Remember Me
            </label>
            <label class="pull-right">
                <a href="/password/reset">Forgotten Password?</a>
            </label>

        </div>
        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
        <div class="register-link m-t-15 text-center">
            <p>Don't have account ? <a href="/signup"> Sign Up Here</a></p>
        </div>
    </form>

    @if ($message == 'wrong details')

        <div class="alert alert-danger container">
            The details you provided were not correct
        </div>

    @endif

    @if (count($errors))
        <div class="alert alert-danger container" style="margin:2%;">
                <ul>
                    @foreach ($errors->all() as $error )
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
        </div>
    @endif

@endsection
