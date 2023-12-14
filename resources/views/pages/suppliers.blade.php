@extends('layout.layout')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
            <li class="breadcrumb-item"></li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="d-flex justify-content-end me-1 mb-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-addSupplier">Add
                Supplier</button>
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
        <div class="table-responsive">
            <table class="table table-hover" id="supplier-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">SUPPLIER</th>
                        <th scope="col">CONTACT</th>
                        <th scope="col">ADDRESS</th>
                        <th>Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                @if ($suppliers)
                    <tbody>
                        @foreach ($suppliers as $index => $supplier)
                            <tr>
                                <td scope="row">{{ $index + 1 }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->contact }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ Carbon::parse($supplier->created_at)->format('Y-M-d') }}</td>
                                <td>
                                    <form action='{{ route('supplier.delete', ["$supplier->id"]) }}' method="post">
                                        <button type="button" id="{{ $supplier->id }}"
                                            class="btn btn-primary text-uppercase btn_edit"
                                            title="Edit supplier {{ $supplier->name }}">Edit</button>
                                        @csrf
                                        <a href="{{ route('supplier.invoice.show', [$supplier->name]) }}" class="btn btn-success">Create Invoice</a>
                                        <input type="hidden" name="id" value="{{ $supplier->id }}" readonly>
                                        <button onclick="confirmDelete(event)" class="btn btn-danger text-uppercase"
                                            title="delete {{ $supplier->name }}">
                                            delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-edit-supplier" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-edit-supplier" aria-hidden="true">
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
                            <img src="" alt="Product Image" class="d-none rounded-circle product-image bg-light" />
                        </div>
                        <form class="px-5 py-2" action="{{ route('supplier.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="supplier-id">
                            <div class="form-outline mb-3">
                                <input type="text" name="supplier-name" id="supplierName" class="form-control" />
                                <label class="form-label" for="supplierName">Supplier Name</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" name="contact" id="contact" class="form-control" />
                                <label class="form-label" for="contact">Supplier's Contact</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" name="address" id="supplierAddress" class="form-control" />
                                <label class="form-label" for="supplierAddress">Supplier's Address</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Supplier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.add-supplier-modal')
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
        $(document).ready(function() {
            $(document).on('click', 'button.btn_edit', function(e) {
                var supplier_id = e.target.id;
                var contact = $('input#contact'),
                    name = $('input#supplierName'),
                    address = $('input#supplierAddress');
                modal_title = $('#modal-title')[0];
                $.ajax({
                    url: "/supplier/edit/" + supplier_id,
                    success: function(res) {
                        console.log(res);
                        $('#modal-edit-supplier').modal('show');
                        $('#supplier-id').val(res.id);
                        name.val(res.name);
                        contact.val(res.contact);
                        address.val(res.address)
                        modal_title.textContent = `Edit Supplier ${res.name}`;
                    }
                })
            })
        });
        var table = new DataTable('#supplier-table', {
            search: {
                return: true,
            },
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
            ]
        });
        $('button.dt-button.buttons-print').addClass('btn btn-primary');
        $('button.dt-button.buttons-pdf').addClass('btn btn-danger');
        $('button.dt-button.buttons-excel').addClass('btn btn-success');
        $('input[aria-controls="supplier-table"]').on('keyup', function() {
            table.search(this.value).draw();
        })
    </script>
@endsection
