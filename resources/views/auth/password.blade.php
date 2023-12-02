@extends('auth.layout')
@section('content')
@if (session('authorize'))
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card p-5 border-0 mt-5" style="max-width: 520px">
                <form action="{{ url('/auth/new-password') }}" method="POST">
                    @csrf
                    <div class="text-center">
                        <img src="assets/images/logo-black.png" alt="logo">
                    </div>
                    <h3 class="h5 text-center text-primary py-2">
                        New Password
                    </h3>
                    <div class="form-outline mb-4">
                        <input type="password" autofocus name="new-password" id="new-password" class="form-control form-control-lg" />
                        <label class="form-label" for="new-password">New Password</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Sunmit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    Not Authorized
@endif
@endsection
