@extends('layouts.admin-master')

@section('right-panel')

<div id="root" class="container" style="margin-top:15px">
    <!-- issue: i may want to include plans here -->
    <div class="row">
        <div class="col col-md-9 mx-4 my-2">
            <aside class="profile-nav alt">
                <section class="card">
                    
                        
                        <div class="card-body">
                            <div class="card-title">
                                <h3 class="text-center title-2">Create User</h3>
                            </div>
                            <hr>
                            <form action="/users/new/admin" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="control-label mb-1">Name</label>
                                    <input id="name" name="name" class="form-control" aria-required="true" aria-invalid="false" type="text">
                                </div>
                                <div class="form-group has-success">
                                    <label for="email" class="control-label mb-1">Email</label>
                                    <input id="email" name="email" class="form-control cc-name valid" type="email">
                                    <span class="help-block field-validation-valid" data-valmsg-for="cc-name" data-valmsg-replace="true"></span>
                                </div>
                                
                                </div>
                                <div>
                                    <button  type="submit" class="btn btn-lg btn-block primary">
                                        <span id="payment-button-amount">Create</span>
                                        <span id="payment-button-sending" style="display:none;">Sending…</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    

                </section>
            </aside>

        </div>
    </div>

</div>





@endsection
