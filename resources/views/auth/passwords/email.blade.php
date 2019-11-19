@extends('layouts.auth-master')

@section('form_content')

    <form action = "{{route('password.email')}}" method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label>Email address</label>
            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name ="email" placeholder="Email" required>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Send Password Reset Link</button>
    </form>
@endsection

