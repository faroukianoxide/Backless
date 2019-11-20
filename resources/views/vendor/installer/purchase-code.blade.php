@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.welcome.templateTitle') }}
@endsection

@section('title')
    Verify Purchase Code
@endsection


@section('container')
@if ($message == 'not_verified')
<p style="text-align:center; color:red">Your purchase has not yet been verified.
  Please verify your purchase to activate <strong>Backless</strong> for this server. </p>
@else
<p style="text-align:center; color:green">Your purchase has been verified successfully!</p>
@endif

<form action="{{ route('LaravelInstaller::check-code') }}" method="POST">
  @csrf

  @if ($message != 'verified')
    <div class="text-center">
      <input type="text" name="code" placeholder="Enter purchase code here" required/>
    </div>
    <div class="text-center">
      
        <button class="button" type="submit">
            Verify 
            <i class="fa fa-check" aria-hidden="true"></i>
        </button>
      @endif

    </div>
  </form>

  <a href="/install/permissions/" class="button" style="float:right; margin-bottom:20px;">
    
    <i class="fa fa-forward" aria-hidden="true"></i>
  </a>
@endsection
