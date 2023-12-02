@extends('layout.layout')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Returns</li>
            <li class="breadcrumb-item"></li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="d-flex justify-content-end me-1 mb-2">
            <div class="mx-2">
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-return"
                    class="btn btn-outline-secondary text-capitalize">Return product</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger my-2">
                <ul class="d-flex justify-content-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="d-flex justify-content-end me-1 mb-2">
            Filter by date:
            <div class="mx-2">
                <input type="text" value="{{ Date('Y-m-d') }} - {{ Date('Y-m-d') }}" id="return-daterange"
                    name="return-daterange" class="form-control">
            </div>
            <div class="mx-2">
                <button id="filter" class="btn btn-success text-capitalize">Filter</button>
            </div>
            <div class="mx-2">
                <button id="reset" class="btn btn-warning text-capitalize">Reset</button>
            </div>
        </div>
        @php
            use Carbon\Carbon;
        @endphp
        <div class="table-responsive">
            <table class="table table-hover" id="return-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">RETURN ID</th>
                        <th scope="col">CUSTOMER NAME</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">QUANTITY RETURNED</th>
                        <th scope="col">RETURN AMOUNT</th>
                        <th scope="col">DATE</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: right">Total:</th>
                        <th id="total_order">00.0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-return" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-return" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize">Return Product</h5>
                            </div>
                        </div>
                        <span id="response-text" class="form-text text-center text-danger"></span>
                        <div class="px-5 mb-4">
                            <div class="form-outline mb-1">
                                <input type="number" class="form-control" placeholder="Please enter order number"
                                    name="order_id" id="order_id" />
                                <label class="form-label" for="order_id">Enter order number</label>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" id="btn_check_order" class="btn btn-primary text-lowercase">check
                                    order</button>
                            </div>
                        </div>
                        <form class="px-5 py-2" action="{{ route('return.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="r_id" id="r_id">
                            <input type="hidden" name="customer_id" id="cust-id">
                            <div class="form-outline mb-4">
                                <input readonly required type="text" name="product-name" id="productName"
                                    class="form-control" />
                                <label class="form-label" for="productName">Product Name</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input readonly required type="text" name="customer-name" id="customerName"
                                    class="form-control" />
                                <label class="form-label" for="customerName">Customer Name</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input readonly required type="number" step=".01" placeholder="0.00"
                                            name="price" id="unitPrice" class="form-control" />
                                        <label class="form-label" for="unitPrice">Unit Price</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input readonly type="number" name="quantity" id="quantity"
                                            class="form-control" />
                                        <label class="form-label" for="quantity">Quantity</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">Returns</div>
                            <div class="form-outline mb-4">
                                <input type="number" name="return-quantity" id="return-quantity"
                                    class="form-control" />
                                <label class="form-label" for="return-quantity">Quantity</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
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
        $(document).ready(function() {
            var order_input = $('input#order_id'),
                error_text = $('#response-text'),
                check_order_btn = $('#btn_check_order');
            check_order_btn.on('click', function() {
                $(error_text).hide();
                $(order_input).on('focus', function() {
                    $(this).removeClass('is-invalid');
                    $(error_text).hide();
                });
                if (!$(order_input).val()) {
                    $(order_input).focus();
                } else {
                    $.ajax({
                        url: "/order/show/" + $(order_input).val(),
                        success: function(data) {
                            console.log(data);
                            $(order_input).addClass('is-valid');
                            $('#productName').val(data[0].product).addClass('active');
                            $('#customerName').val(data[0].customer).addClass('active');
                            $('#unitPrice').val(data[0].price).addClass('active');
                            $('#quantity').val(data[0].quantity).addClass('active');
                            $('#cust-id').val(data[0].customer_id).addClass('active');
                            $('#return-quantity')[0].max = data[0].quantity;
                            $('input#r_id').val(data[0].order_number);
                        },
                        error: function(res) {
                            if (res.status == 404) {
                                $(error_text).text(res.responseJSON.response);
                                $(order_input).focus();
                                $(order_input).attr('placeholder',
                                    'Please enter a valid order number');
                                $(order_input).addClass('is-invalid');
                                $(error_text).show();
                            }
                        }
                    });
                }
            })
        })
        $(document).ready(function() {
            $('input[name="return-daterange" ]').daterangepicker({
                startDate: moment().subtract(1, 'M'),
                endDate: moment()
            });
            var table = new DataTable('#return-table', {
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        if (typeof i === 'string') {
                            return i.replace(/[\$,]/g, '') * 1;
                        } else if (typeof i === 'number') {
                            return i;
                        }
                    };
                    var total = api.column(6).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var pageTotal = api.column(6, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(6).footer()).html(' ' + pageTotal);
                },
                scrollY: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('returns') }}",
                    data: function(data) {
                        data.from_date = $('input[name="return-daterange" ]')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        data.to_date = $('input[name="return-daterange" ]')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'return_id',
                        name: 'return_id'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(val) {
                            return moment(val).format('Do MMMM, YYYY')
                        }
                    }
                ],
                search: {
                    return: true,
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        className: 'btn btn-success text-capitalize',
                        title: 'returns',
                        text: 'export to excel',
                        footer: true,
                        filename: 'returns-' + moment(new Date()).format('dddd-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger text-capitalize',
                        title: 'returns',
                        text: 'save as PDF',
                        footer: true,
                        filename: 'returns-' + moment(new Date()).format('dddd-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-primary text-capitalize',
                        title: 'returns',
                        footer: true,
                        text: 'print',
                        filename: 'returns-' + moment(new Date()).format('dddd-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                ]
            });
            $('button#filter').click(function() {
                table.draw();
            });
            $('button#reset').click(function() {
                location.reload();
            });
            $('input[aria-controls="return-table"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endsection
