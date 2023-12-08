@extends('auth.layout')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card p-5 border-0 mt-5" style="max-width: 520px">
                <form action="{{ url('/auth/verify-secret-code') }}" method="POST">
                    @csrf
                    <h3 class="h5 text-center text-primary py-2">
                        Verify Secret Code
                    </h3>
                    @if ($errors->any())
                        <div style="z-index: 1"
                            class="animate__animated animate__zoomIn alert alert-danger p-1 rounded-0 text-center">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="alert alert-secondary">
                        Hello Admin, please enter your secret code to verify that it's you. 
                    </div>
                    @if (session('error'))
                        <h6 class="h6 text-danger text-center">{{ session('error') }}</h6>
                    @endif
                    <div class="form-group mb-4">
                        <label for="user">Enter your secret code</label>
                        <input autofocus type="number" maxlength="6" name="secret-code" id="user" class="form-control" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
