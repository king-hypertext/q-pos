@extends('layout.layout')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customers</li>
            <li class="breadcrumb-item"></li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="d-flex justify-content-end me-1 mb-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-addCustomer">Add
                Worker</button>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="d-flex justify-content-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($customers)
            <div class="table-responsive">
                <table class="table table-hover" id="customer-table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Customer Image</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Address</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Date of Birth</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td scope="row">{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>
                                    <img src="{{ asset('assets/images/customers/' . $customer->image) }}"
                                        alt="product-image" class="product-image-table-view">
                                </td>
                                <td>{{ $customer->contact }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->gender }}</td>
                                <td>{{ $customer->date_of_birth }}</td>
                                <td>
                                    <form action='{{ route('customer.delete', ["$customer->id"]) }}' method="post">
                                        <button type="button" id="{{ $customer->id }}"
                                            class="btn_edit btn btn-primary text-uppercase my-1"
                                            title="Edit {{ $customer->name }}">
                                            Edit
                                        </button>
                                        <a target="_blank" rel="noopener noreferrer" href="{{ route('customer.show', [$customer->id]) }}" class="btn btn-success">
                                            Create Order
                                        </a>
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $customer->id }}" readonly>
                                        <button onclick="confirmDelete(event)" class="btn btn-danger text-uppercase my-1"
                                            title="delete {{ $customer->name }}">
                                            delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">No Data in the database</div>
        @endif
        @include('modals.add-customer-modal')
    </div>
    <div class="modal fade" id="modal-edit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize" id="modal-title"></h5>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <img id="customer-image" src="" alt="Product Image"
                                class="rounded-circle product-image bg-light d-none" />
                        </div>
                        <form class="px-5 py-2" action="{{ route('customer.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="customer_id">
                            <div class="form-outline mb-4">
                                <input required type="text" name="customer-name" id="customertName"
                                    class="form-control" />
                                <label class="form-label" for="customertName">Worker Name</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="dob">Date of Birth</label>
                                    <input type="date" max="{{ Date('Y-m-d') }}" name="dob" id="dob"
                                        class="form-control" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select name="gender" id="cust-gender" class="form-select">
                                        <option disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <input required type="text" onfocus="this.type='number'" name="contact"
                                    id="customerContact" class="form-control" />
                                <label class="form-label" for="customerContact">Contact</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" name="address" id="customerAddress" class="form-control" />
                                <label class="form-label" for="customerAddress">Address</label>
                            </div>
                            <div class="image-preview-wrapper-s">
                                <img src="" alt="" class="image-preview-s" id="cust-image"
                                    style="width: 55px; height: 55px;">
                            </div>
                            <div class="mb-4">
                                <label for="customerImage">Customer Image</label>
                                <input required type="file" onchange="previewImageFromServer()" name="customer-image"
                                    accept="image/*" id="customer_image" class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Customer</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" id="modal-edit-close">Discard</button>
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
        var image_wrapper = $('.image-preview-wrapper-s');
        image_wrapper.show();

        function previewImageFromServer() {
            var img = document.querySelector('img.image-preview-s');
            var input_data = document.getElementById('customer_image');
            var file_preview = input_data.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                console.log(e.target.result);
                img.src = e.target.result;
            });
            filereader.readAsDataURL(file_preview);
        }
        $(document).ready(function() {
            $(document).on('click', 'button.btn_edit', function(e) {
                var customer_id = e.target.id;
                var dob = $('input#dob'),
                    name = $('input#customertName'),
                    gender = $('select#cust-gender'),
                    contact = $('input#customerContact'),
                    address = $('input#customerAddress');
                modal_title = $('#modal-title')[0];
                $.ajax({
                    url: "/customer/edit/" + customer_id,
                    success: function(res) {
                        $('#customer_id').val(res.id);
                        name.val(res.name);
                        dob.val(res.date_of_birth);
                        gender.selected = res.gender;
                        contact.val(res.contact);
                        address.val(res.address);
                        $('img#cust-image')[0].src = "/assets/images/customers/" + res.image;
                        modal_title.textContent = `Edit Customer ${res.name}`;
                    }
                });
                $('#modal-edit').modal('show');
                $('button#modal-edit-close').on('click', function() {
                    $('#modal-edit').modal('hide');
                });
            })
        });
        window.confirmDelete = function(e) {
            e.preventDefault();
            var form = e.target.form;
            Swal.fire({
                title: "Confirm Delete!",
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
        var table = new DataTable('#customer-table', {
            processing: true,
            search: {
                return: true,
            },
            pageLength: 200,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: 'Export to Excel',
                    className: 'btn btn-success text-capitalize',
                    filename: 'customers',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Save as PDF',
                    className: 'btn btn-danger text-capitalize',
                    filename: 'customers',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: 'print',
                    className: 'btn btn-primary text-capitalize',
                    filename: 'customers',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6]
                    }
                },
            ]
        });
        $('input[aria-controls="customer-table"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    </script>
@endsection
