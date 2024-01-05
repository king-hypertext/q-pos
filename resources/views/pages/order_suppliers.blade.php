@extends('layout.layout')
@section('content')
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <a href="{{ route('orders.customer') }}" class="nav-link">Customers</a>
            <a href="{{ route('orders.supplier') }}" class="nav-link active" aria-selected="true">suppliers</a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="d-flex justify-content-end me-1 mb-2">
            Filter by date:
            <div class="mx-2">
                <input type="text" value="{{ Date('Y-m-d') }} - {{ Date('Y-m-d') }}" id="suppliers_order-daterange"
                    name="suppliers_order-daterange" class="form-control">
            </div>
            <div class="mx-2">
                <button id="filter" class="btn btn-success text-capitalize">Filter</button>
            </div>
            <div class="mx-2">
                <button id="reset" class="btn btn-warning text-capitalize">Reset</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="suppliers_order-table" class="display">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">ORDER NUMBER</th>
                        <th scope="col">SUPPLIER</th>
                        <th scope="col">CATEGORY</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">PRICE(GHS)</th>
                        <th scope="col">AMOUNT(GHS)</th>
                        <th scope="col">DAY</th>
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
                        <th></th>
                        <th style="text-align: right !important" align="right">Total:</th>
                        <th id="total_order">00.0</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
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
                        window.location.href = "/suppliers_order/delete/" + link.id;
                    }
                })
        }
        $(document).ready(function() {
            $('input[name="suppliers_order-daterange" ]').daterangepicker({
                startDate: moment().subtract(1, 'M'),
                endDate: moment()
            });

            function addCell(tr, content, colSpan = 1) {
                let td = document.createElement('th');

                td.colSpan = colSpan;
                td.textContent = content;

                tr.appendChild(td);
            }
            var table = new DataTable('#suppliers_order-table', {
                scrollY: false,
                processing: true,
                serverSide: true,
                pageLength: 500,
                ajax: {
                    url: "{{ route('orders.supplier') }}",
                    data: function(data) {
                        data.from_date = $('input[name="suppliers_order-daterange" ]')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        data.to_date = $('input[name="suppliers_order-daterange" ]')
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
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'category',
                        name: 'category'
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
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(val) {
                            return moment(val).format('Do MMMM, YYYY')
                        }
                    }
                ],
                order: [
                    [4, 'asc']
                ],
                rowGroup: {
                    startRender: null,
                    endRender: function(rows, group) {
                        let totalAmount =
                            rows
                            .data()
                            .pluck('amount').map((str) => parseInt(str)).reduce((a, b) => a + b, 0);

                        totalAmount = $.fn.dataTable.render
                            .number(',', '.', 2, 'GHS ')
                            .display(totalAmount);

                        let totalQuantity =
                            rows
                            .data()
                            .pluck('quantity')
                            .reduce(function(a, b) {
                                return a + b
                            }, 0);

                        let tr = document.createElement('tr');

                        addCell(tr, 'Sum for ' + group, 5);
                        addCell(tr, totalQuantity.toFixed(0));
                        addCell(tr, '');
                        addCell(tr, totalAmount);

                        return tr;
                    },
                    dataSrc: 'product'
                },
                drawCallback: function() {
                    var api = this.api(),
                        sum = 0,
                        last = null;
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    api.column(4, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr><th style="background-color: #d1d1d1;" colspan="10">' +
                                group + '</th></tr>');
                            last = group;
                        }
                    });
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
                    var total = api.column(7).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var pageTotal = api.column(7, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(7).footer()).html(' ' + pageTotal);
                },
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
            $('input[aria-controls="suppliers_order-table"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
        $(document).ready(function() {
            $(document).on('click', '.btn_return_order', function(e) {
                $('#return-quantity').attr('autofocus', 'true');
                $.ajax({
                    url: "/suppliers_order/show/" + e.target.id,
                    success: function(data) {
                        console.log(data);
                        $('#productName').val(data.product).addClass('active');
                        $('#customerName').val(data.customer).addClass('active');
                        $('#unitPrice').val(data.price).addClass('active');
                        $('#quantity').val(data.quantity).addClass('active');
                        $('#cust-id').val(data.customer_id).addClass('active');
                        $('#return-quantity').max = data.quantity;
                        $('input#r_id').val(data.order_number);
                    }
                });
                $('#modal-return').modal('show');
                $('[data-bs-dismiss="modal"]').on('click', () => {
                    $('#modal-return').modal('hide');
                })
            })
        })
    </script>
@endsection
