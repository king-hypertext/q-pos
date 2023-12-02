@extends('auth.layout')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card p-5 border-0 mt-5" style="max-width: 520px">
                <form action="{{ url('/auth/reset-password') }}" method="POST">
                    @csrf
                    <h3 class="h5 text-center text-primary py-2">
                        Reset Your Password
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
                    @if (session('error'))
                        <h6 class="h6 text-danger text-center">{{ session('error') }}</h6>
                    @endif
                    <div class="form-group mb-4">
                        <label for="user">Enter your date of birth</label>
                        <input autofocus type="date" name="dob" id="user" class="form-control" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
