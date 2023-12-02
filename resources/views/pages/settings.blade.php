@extends('layout.layout')
@section('content')
    Settings
    <style>
        .product-image-view {
            width: 200px;
            height: auto;
            border-radius: 50%;
            background-size: cover;
            background: #2222;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="d-flex justify-content-center">
                    @if (auth()->user()->user_image == '')
                        <img src="{{ asset('assets/images/avatar-1.png') }}" alt="product-image" class="product-image-view">
                    @else
                        <img src="{{ asset('assets/images/admin/' . auth()->user()->user_image) }}" alt="product-image"
                            class="product-image-view">
                    @endif
                </div>
                <h6 class="h5 text-center">{{ auth()->user()->name }}</h6>
            </div>
            <div class="col-6 col-md-6">
                <div class="card shadow border-0">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="d-flex justify-content-center">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h5 class="h5 text-center pt-5">User Profile</h5>
                    <form class="px-5 py-2" action="{{ url('/update-profile') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-outline mb-4">
                            <input required type="text" value="{{ auth()->user()->name }}" name="name" id="name"
                                class="form-control form-control-lg" />
                            <label class="form-label" for="name">User Name</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input required type="text" value="{{ auth()->user()->username }}" name="user-name"
                                id="user-name" class="form-control form-control-lg" />
                            <label class="form-label" for="user-name">User Name</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input required type="text" value="{{ auth()->user()->date_of_birth }}"
                                onfocus="this.type='date'" name="dob" id="dob"
                                class="form-control form-control-lg" />
                            <label class="form-label" for="dob">Date of Birth</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input disabled type="text" value="admin" name="user-type" id="batchNumber"
                                class="form-control form-control-lg" />
                            <label class="form-label" for="batchNumber">User Type</label>
                        </div>
                        <div class="d-flex justify-content-center">
                            <img id="product-image" src="" alt="Product Image"
                                class="rounded-circle product-image bg-light d-none" />
                        </div>
                        <div class="form-outline mb-4">
                            <input required type="password" name="password" id="user-password"
                                class="form-control form-control-lg" />
                            <label class="form-label" for="user-password">New Password</label>
                        </div>
                        <div class="mb-4">
                            <label for="userImage">Profile Image</label>
                            <input type="file" onchange="previewImage()" name="user-image" id="userImage"
                                class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <script>
            const showSuccessAlert = Swal.mixin({
                position: 'top-end',
                toast: true,
                timer: 6500,
                showConfirmButton: false,
                timerProgressBar: false,
            });
            showSuccessAlert.fire({
                icon: 'success',
                text: '{{ session('success') }}',
                padding: '10px',
                width: 'auto'
            });
        </script>
    @endif
@endsection
@section('js')
    <script>
        function previewImage() {
            var image = document.getElementById('product-image');
            const input = document.getElementById('userImage');
            const file = input.files[0];
            console.log(file);
            const filereader = new FileReader();
            filereader.readAsDataURL(file);
            filereader.addEventListener('load', () => {
                // image.classList.remove('d-none')
                // console.log(this.result);
                image.src = this.result;
            });
        }
    </script>
@endsection
