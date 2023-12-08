@extends('layout.layout')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
            <li class="breadcrumb-item"></li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-striped" id="product-enquiry-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">IMAGE</th>
                        <th scope="col">PRICE(GHS)</th>
                        <th scope="col">AVAILABLE QTY</th>
                        <th scope="col">SUPPLIED BY</th>
                        <th scope="col">PROD. DATE</th>
                        <th scope="col">EXPIRY DATE</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                @php
                    use Carbon\Carbon;
                @endphp
                <tbody>
                    @foreach ($data as $key => $product)
                        <tr>
                            <td scope="row">{{ $key + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <img src="{{ asset('assets/images/products/' . $product->image) }}" alt="product-image"
                                    class="product-image-table-view" />
                            </td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->supplier }}</td>
                            <td>{{Carbon::parse( $product->prod_date)->format('Y-M-d') }}</td>
                            <td>{{Carbon::parse( $product->expiry_date)->format('Y-M-d') }}</td>
                            <td>
                                <form action='{{ route('product.delete', ["$product->id"]) }}' method="post">
                                    <button type="button" id="{{ $product->id }}"
                                        class="btn btn-success text-uppercase btn_top_up my-1"
                                        title="Top Up Quantity for {{ $product->name }}">Top Up</button>
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}" readonly>
                                    <button onclick="confirmDelete(event)" class="btn btn-danger text-uppercase my-1"
                                        title="delete {{ $product->name }}">
                                        delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- modal top up --}}
    <div class="modal fade" id="modal-top-up" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-addCustomer" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-2">
                            <div class="divider-text">
                                <h5 class="h5 text-capitalize" id="modal-title"></h5>
                            </div>
                        </div>
                        <form class="px-5 py-2" action="{{ route('product.store') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="product-id">
                            <div class="form-outline mb-4">
                                <input type="text" name="product-name" id="product-name" class="form-control" disabled />
                                <label class="form-label" for="product-name">Product Name</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input type="text" onfocus="this.type='number'" name="aval-quantity"
                                            id="aval-quantity" class="form-control" disabled />
                                        <label class="form-label" for="aval-quantity">Available Quantity</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input type="text" name="supplier" id="supplier" class="form-control"
                                            disabled />
                                        <label class="form-label" for="supplier">Supplier</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-outline">
                                <input required type="text" autofocus onfocus="this.type='number'" name="quantity" id="quantity"
                                    class="form-control" />
                                <label class="form-label" for="quantity">Quantity</label>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="qty-info">Description about the top up</label>
                                <textarea name="update_info" rows="4" class="form-control" id="qty-info"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Top Up</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" id="modal-top-up-close">Discard</button>
                </div>
            </div>
        </div>
    </div>
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
            $(document).on('click', 'button.btn_top_up', function(e) {
                $('textarea[name="update_info"]').val(
                    "If quantity is been top up, write some description about the top up\neg. increase in quantity by 10"
                );
                var product_id = e.target.id;
                var quantity = $('input#aval-quantity'),
                    name = $('input#product-name'),
                    supplier = $('input#supplier');
                modal_title = $('#modal-title')[0];
                $.ajax({
                    url: "/product/edit/" + product_id,
                    success: function(res) {
                        console.log(res);
                        $('#product-id').val(res.id);
                        name.val(res.name);
                        quantity.val(res.quantity);
                        supplier.val(res.supplier)
                        modal_title.textContent = `Top Up ${res.name}`;
                    }
                });
                $('#modal-top-up').modal('show');
                $('button#modal-top-up-close').on('click', function() {
                    $('#modal-top-up').modal('hide');
                });
            })
            var table = new DataTable('#product-enquiry-table', {
                processing: true,
                search: {
                    return: true,
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export to Excel',
                        className: 'btn btn-success text-capitalize',
                        title: document.title,
                        filename: document.title.replace('Q-POS | ', '') + '-' + moment((new Date()))
                            .format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        text: 'Save as PDF',
                        className: 'btn btn-danger text-capitalize',
                        title: document.title,
                        extend: 'pdf',
                        filename: document.title.replace('Q-POS | ', '') + '-' + moment((new Date()))
                            .format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        text: 'Print',
                        className: 'btn btn-primary text-capitalize',
                        title: document.title,
                        extend: 'print',
                        filename: document.title.replace('Q-POS | ', '') + '-' + moment((new Date()))
                            .format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7]
                        }
                    },
                ]
            });
            $('input[aria-controls="product-enquiry-table"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection
