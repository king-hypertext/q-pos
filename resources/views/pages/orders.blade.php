@extends('layout.layout')
@section('content')
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <a href="{{ route('orders.customer') }}" class="nav-link active" aria-selected="true">Customers</a>
            <a href="{{ route('orders.supplier') }}" class="nav-link">suppliers</a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="d-flex justify-content-end me-1 mb-2">
            Filter by date:
            <div class="mx-2">
                <input type="text" value="{{ Date('Y-m-d') }} - {{ Date('Y-m-d') }}" id="order-daterange"
                    name="order-daterange" class="form-control">
            </div>
            <div class="mx-2">
                <button id="filter" class="btn btn-success text-capitalize">Filter</button>
            </div>
            <div class="mx-2">
                <button id="reset" class="btn btn-warning text-capitalize">Reset</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="order-table" class="display">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">ORDER_ID</th>
                        <th scope="col">WORKER</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">AMOUNT</th>
                        <th title="return quantity" scope="col">RETURNS</th>
                        <th scope="col">DATE</th>
                        <th scope="col">DAY</th>
                        <th scope="col">ACTION</th>
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
                        <th></th>
                        <th></th>
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
                                        <label class="form-label" for="quantity">Quantity Ordered</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">Returns</div>
                            <div class="form-outline mb-4">
                                <input type="number" name="return-quantity" id="return-quantity" class="form-control" />
                                <label class="form-label" for="return-quantity">Return Quantity</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Discard</button>
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
        window.confirmDelete = function(e) {
            e.preventDefault();
            var link = e.target;
            console.log(link, link.id);
            var form =
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
                        window.location.href = "/order/delete/" + link.id;
                    }
                })
        }
        const showSuccessAlert = Swal.mixin({
            position: 'top-end',
            toast: true,
            timer: 6500,
            showConfirmButton: false,
            timerProgressBar: false,
        });
        $(document).ready(function() {
            $('input[name="order-daterange" ]').daterangepicker({
                startDate: moment().subtract(1, 'M'),
                endDate: moment()
            });

            function addCell(tr, content, colSpan = 1) {
                let td = document.createElement('th');
                td.colSpan = colSpan;
                td.textContent = content;
                tr.appendChild(td);
            }
            var table = new DataTable('#order-table', {
                order: [
                    [3, 'asc']
                ],
                rowGroup: {
                    startRender: null,
                    endRender: function(rows, group) {
                        let totalAmount =
                            rows
                            .data()
                            .pluck('amount').map((str) => parseInt(str)).reduce((a, b) => a + b, 0);

                        totalAmount = $.fn.dataTable.render
                            .number(',', '.', 2, 'GHS')
                            .display(totalAmount);

                        let totalQuantity =
                            rows
                            .data()
                            .pluck('quantity')
                            .reduce(function(a, b) {
                                return a + b
                            }, 0);

                        let tr = document.createElement('tr');

                        addCell(tr, 'Sum for ' + group, 4);
                        addCell(tr, totalQuantity.toFixed(0));
                        addCell(tr, '');
                        addCell(tr, totalAmount);
                        addCell(tr, rows.data().pluck('return_quantity').reduce(function(a, b) {
                            return a + b
                        }, 0));

                        return tr;
                    },
                    dataSrc: 'product'
                },
                drawCallback: function() {
                    var api = this.api(),
                        last = null;
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    api.column(3, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr><th style="background-color: #d1d1d1;" colspan="11">' +
                                group + '</th></tr>');
                            last = group;
                        }
                    })
                },
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
                    $(api.column(6).footer()).html('GHS ' + pageTotal);
                },
                scrollY: false,
                processing: true,
                serverSide: true,
                pageLength: 500,
                ajax: {
                    url: "{{ route('orders.customer') }}",
                    data: function(data) {
                        data.from_date = $('input[name="order-daterange" ]')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        data.to_date = $('input[name="order-daterange" ]')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'order_number',
                        name: 'order_number'
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
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'return_quantity',
                        name: 'return_quantity'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(val) {
                            return moment(val).format('Do MMMM, YYYY')
                        }
                    },
                    {
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'Action',
                        name: 'Action'
                    }
                ],
                search: {
                    return: true,
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export to Excel',
                        className: 'btn btn-success text-capitalize',
                        footer: true,
                        title: 'orders ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Save as PDF',
                        className: 'btn btn-danger text-capitalize',
                        footer: true,
                        title: 'orders ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'print',
                        className: 'btn btn-primary text-capitalize',
                        footer: true,
                        title: 'orders ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
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
            $('input[aria-controls="order-table"]').on('keyup', function() {
                table.search(this.value).draw();
            });
            //handle return form
            $(document).on('click', '.btn-reset', function(e) {
                var Id = Number.parseInt(e.currentTarget.id);
                var order_id = e.currentTarget.dataset.id,
                    order_qty = e.currentTarget.dataset.order_quantity;
                    console.log(order_qty);
                $.ajax({
                    url: '/return/reset/' + Id + '/' + order_id,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "order_quantity": order_qty
                    },
                    success: function(res) {
                        if (res.success) {
                            showSuccessAlert.fire({
                                icon: 'success',
                                text: res.success,
                                padding: '10px',
                                width: 'auto'
                            });
                            table.draw();
                        }
                    },
                    error: function(res) {
                        console.log(res);
                    }
                })
                console.log(e.currentTarget, order_id);
            });
            $(document).on('click', '.btn-return', function(e) {
                e.preventDefault();
                var quantity_to_return = Number.parseInt(e.currentTarget.form[0].value);
                if (!quantity_to_return) {
                    alert('quantity field is required')
                    table.draw();
                } else {
                    if (quantity_to_return > e.currentTarget.form[0].id) {
                        alert('The entered value is greater than the ordered quantity');
                    } else {
                        $.ajax({
                            url: '/return/add',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "order_quantity": e.currentTarget.form[0].id,
                                "product": e.currentTarget.form[4].value,
                                "return_quantity": quantity_to_return,
                                "order_date": e.currentTarget.form[5].value,
                                "customer_id": e.currentTarget.form[2].value,
                                "order_id": e.currentTarget.form[3].value,
                                "customer": e.currentTarget.form[1].value,
                                "returns": e.currentTarget.form[6].value
                            },
                            success: function(res) {
                                console.log(res);
                                if (res.success) {
                                    quantity_to_return == 0;
                                    e.currentTarget.form.reset();
                                    showSuccessAlert.fire({
                                        icon: 'success',
                                        text: res.success,
                                        padding: '10px',
                                        width: 'auto'
                                    });
                                    table.draw();
                                } else if (res.failed) {
                                    quantity_to_return == 0;
                                    e.currentTarget.form.reset();
                                    showSuccessAlert.fire({
                                        icon: 'warning',
                                        text: res.failed,
                                        padding: '10px',
                                        width: 'auto'
                                    });
                                    table.draw();
                                }
                            }
                        })
                    }
                }
            });
        })
    </script>
@endsection
