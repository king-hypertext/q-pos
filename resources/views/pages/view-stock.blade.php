@extends('layout.layout')
@section('content')
    <div class="container">
        @php
            use Illuminate\Support\Facades\DB;
            $products = DB::table('products')
                ->where('quantity', '>', 0)
                ->get(['name']);
            $empty_q = DB::table('products')
                ->where('quantity', '<', 1)
                ->count('name');
            $empty_p = DB::table('products')->get(['*']);
        @endphp
        <div class="row">
            <div class="d-flex">
                <img src="{{ asset('assets/images/customers/' . $customer->image) }}" alt="customer-image"
                    style="background: #333;background-size: cover;width: 250px;">
                <div class="info mx-3">
                    <h2 class="h2 text-uppercase"> {{ $customer->name }} </h2>
                    <h6 class="h4 text-capitalize"> {{ $customer->gender }} </h6>
                    <h6 class="h4 text-capitalize"> {{ $customer->address }} </h6>
                    <h6 class="h4 text-capitalize"> {{ $customer->contact }} </h6>
                </div>
            </div>
        </div>
        @if ($empty_q !== 0)
            <div class="alert alert-danger mt-2 text-center">Some Products are out of stock, please top up now to see them
                in the list</div>
        @endif
        @if (count($empty_p) == 0)
            <div class="alert alert-danger mt-2 text-center">There are no products available, please <a href="{{ route('products') }}" class="btn btn-link text-lowercase">Add Products</a> </div>
        @endif
        <div class="container mt-2">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="d-flex justify-content-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <form id="form-invoice" action="{{ route('order.add') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $customer->id }}">
            <input type="hidden" name="customer" value="{{ $customer->name }}">
            <input type="hidden" name="date" value="{{ Date('Y-m-d') }}">
            <hr class="hr text-dark" />
            <div class="table-responsive text-nowrap">
                <table class="table table-borderless mb-0">
                    <thead>
                        <tr class="">
                            <th class="col-md-4" scope="col">Product Name</th>
                            <th class="col-md-3" scope="col" title="Price">Unit Price</th>
                            <th class="col-md-2" scope="col">Quantity</th>
                            <th class="col-md-3" scope="col">Total (GHC)</th>
                        </tr>
                    </thead>
                    <tbody id="td-parent">
                        <tr class="form_row">
                            <td class="col-md-4">
                                <div class="">
                                    <div class="">
                                        <div class="form-group">
                                            <select required data-select-product name="product[]" id="product"
                                                class="form-select">
                                                <option selected disabled> Select Product </option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->name }}">
                                                        {{ $product->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" name="price[]" type="text"
                                        onfocus="this.type='number'" value="0" id="price" class="form-control"
                                        value="0" />
                                </div>
                            </td>
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input required type="text" name="quantity[]" onfocus="this.type='number'"
                                        id="quantity" class="form-control qty" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" value="0" name="total[]" id="total"
                                        class="form-control" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tr>
                        <td colspan="3" class="text-end fw-bold mt-1">Sum Total</td>
                        <td>
                            <input readonly type="number" step="01" class="" id="total_amt" name="total_amt"
                                value="0.00" />
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <div class="px-0">
                    <button type="button" onclick="addNewRow()" class="btn btn-warning add-row"><i class="bx bx-plus"></i>
                        Add Row
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" title="reset inputs">Reset</button>
                </div>
                <button type="submit" class="btn btn-primary text-capitalize" title="add invoice">Save Order</button>
            </div>
        </form>
        {{-- <div class="table-responsive">
            <table class="table table-striped" id="customer-stock-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product / Item</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Available Qty</th>
                        <th scope="col">Total</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date From</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <style>
                    input[data-input-total],
                    input[data-input-total] {
                        width: 70px;
                        max-width: 100%;
                    }
                </style>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td scope="row">{{ $key + 1 }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <input data-input-total value="0" onfocus="this.type='number'" type="text"
                                    name="" id="" />
                            </td>
                            <td>
                                <input readonly value="0" data-input-total type="text" name=""
                                    id="">
                            </td>
                            <td>720</td>
                            <td>today</td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td scope="row">1</td>
                        <td>oil</td>
                        <td>20</td>
                        <td>2</td>
                        <td>40</td>
                        <td>18</td>
                        <td>720</td>
                        <td>today</td>
                        <td>
                            <form action='#' method="post">
                                <a href="#" class="btn btn-primary text-lowercase me-2">View</a>
                                @csrf
                                <input type="hidden" name="id" value="" readonly>
                                <button onclick="confirmDelete(event)" class="btn btn-danger text-lowercase"
                                    title="delete stock">
                                    delete
                                </button>
                            </form>
                        </td>
                    </tr> --} }
                </tbody>
            </table>
        </div> --}}
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
    {{-- @include('modals.issue-stock') --}}
@endsection
@section('js')
    <script>
        $("[data-select-product]").select2({
            theme: "bootstrap-5",
        });
        // p for product
        var p, p1, p2, p3, p4, p5, p6, p7, p8, u, u1, u2, u3, u4, u5, u6, u7, u8, q, q1, q2, q3, q4, q5, q6, q7, q8, t, t1,
            t2, t3, t4, t5, t6, t7, t8;
        var row = 1;
        window.addNewRow = function() {
            var newInvoiceRow = `<tr class="form_row_${row}">
                            <td class="col-md-4">
                                <div class="">
                                    <div class="">
                                        <div class="form-group">
                                            <select required name="product[]" id="product_${row}"
                                                class="form-select select-product">
                                                <option value="" selected disabled> Select Product </option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->name }}">
                                                        {{ $product->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" name="price[]" type="text"
                                        onfocus="this.type='number'" value="0" id="price_${row}" class="form-control"
                                        value="0" />
                                </div>
                            </td>
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input required type="text" name="quantity[]" onfocus="this.type='number'" id="quantity_${row}"
                                        class="form-control qty" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" value="0" name="total[]" id="total_${row}"
                                        class="form-control total" />
                                </div>
                            </td>
                            <td>
                                <button style="font-weight: 900;font-size: 20px; margin: 0;" type="button" class="btn btn-sm btn-danger" id="removeRow" title="Click to remove row"        >
                                    <span style="padding-top: 8px">&times;</span>
                                </button>
                            </td>
                        </tr>`;
            if (row < 9) {
                $("tbody#td-parent").append(newInvoiceRow);
                $(function() {
                    $(".select-product").select2({
                        theme: "bootstrap-5",
                    });
                    p1 = $("#product_1")[0];
                    u1 = $("#price_1")[0];
                    q1 = $("#quantity_1")[0];
                    t1 = $("#total_1")[0];
                    p1 &&
                        (p1.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u1.value = res.data[0].price;
                                    q1.max = res.data[0].quantity;
                                },
                            });
                        });
                    q1 &&
                        (q1.onkeyup = function() {
                            t1.value = Number.parseFloat(u1.value) * Number.parseFloat(q1.value);
                        });

                    p2 = $("#product_2")[0];
                    u2 = $("#price_2")[0];
                    q2 = $("#quantity_2")[0];
                    t2 = $("#total_2")[0];
                    p2 &&
                        (p2.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u2.value = res.data[0].price;
                                    q2.max = res.data[0].quantity;
                                },
                            });
                        });
                    q2 &&
                        (q2.onkeyup = function() {
                            t2.value = Number.parseFloat(u2.value) * Number.parseFloat(q2.value);
                        });

                    p3 = $("#product_3")[0];
                    u3 = $("#price_3")[0];
                    q3 = $("#quantity_3")[0];
                    t3 = $("#total_3")[0];
                    p3 &&
                        (p3.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u3.value = res.data[0].price;
                                    q3.max = res.data[0].quantity;
                                },
                            });
                        });
                    q3 &&
                        (q3.onkeyup = function() {
                            t3.value = Number.parseFloat(u3.value) * Number.parseFloat(q3.value);
                        });

                    p4 = $("#product_4")[0];
                    u4 = $("#price_4")[0];
                    q4 = $("#quantity_4")[0];
                    t4 = $("#total_4")[0];
                    p4 &&
                        (p4.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u4.value = res.data[0].price;
                                    q4.max = res.data[0].quantity;
                                },
                            });
                        });
                    q4 &&
                        (q4.onkeyup = function() {
                            t4.value = Number.parseFloat(u4.value) * Number.parseFloat(q4.value);
                        });

                    p5 = $("#product_5")[0];
                    u5 = $("#price_5")[0];
                    q5 = $("#quantity_5")[0];
                    t5 = $("#total_5")[0];
                    p5 &&
                        (p5.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u5.value = res.data[0].price;
                                    q5.max = res.data[0].quantity;
                                },
                            });
                        });
                    q5 &&
                        (q5.onkeyup = function() {
                            t5.value = Number.parseFloat(u5.value) * Number.parseFloat(q5.value);
                        });

                    p6 = $("#product_6")[0];
                    u6 = $("#price_6")[0];
                    q6 = $("#quantity_6")[0];
                    t6 = $("#total_6")[0];
                    p6 &&
                        (p6.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u6.value = res.data[0].price;
                                    q6.max = res.data[0].quantity;
                                },
                            });
                        });
                    q6 &&
                        (q6.onkeyup = function() {
                            t6.value = Number.parseFloat(u6.value) * Number.parseFloat(q6.value);
                        });

                    p7 = $("#product_7")[0];
                    u7 = $("#price_7")[0];
                    q7 = $("#quantity_7")[0];
                    t7 = $("#total_7")[0];
                    p7 &&
                        (p7.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u7.value = res.data[0].price;
                                    q7.max = res.data[0].quantity;
                                },
                            });
                        });
                    q7 &&
                        (q7.onkeyup = function() {
                            t7.value = Number.parseFloat(u7.value) * Number.parseFloat(q7.value);
                        });

                    p8 = $("#product_8")[0];
                    u8 = $("#price_8")[0];
                    q8 = $("#quantity_8")[0];
                    t8 = $("#total_8")[0];
                    p8 &&
                        (p8.onchange = function(elem) {
                            elem = elem.target.value;
                            $.ajax({
                                method: "GET",
                                url: "/product/price?q=" + elem,
                                success: function(res) {
                                    u8.value = res.data[0].price;
                                    q8.max = res.data[0].quantity;
                                },
                            });
                        });
                    q8 &&
                        (q8.onkeyup = function() {
                            t8.value = Number.parseFloat(u8.value) * Number.parseFloat(q8.value);
                        });
                });
                row++;
            }
        };
        p = $("#product")[0];
        u = $("#price")[0];
        q = $("#quantity")[0];
        t = $("#total")[0];
        p &&
            (p.onchange = function(elem) {
                elem = elem.target.value;
                $.ajax({
                    url: "/product/price?q=" + elem,
                    success: function(res) {
                        u.value = res.data[0].price;
                        q.max = res.data[0].quantity;
                    },
                });
            });
        q &&
            (q.onkeyup = function() {
                t.value = Number.parseFloat(u.value) * Number.parseFloat(q.value)
            });
        const sumNumbers = (arr) => arr.reduce((totalNumbers, Num) =>
            Number.parseFloat(totalNumbers) + Number.parseFloat(Num), 0);
        $(document).on("keyup", ".qty", function() {
            var $array = [];
            $array.push(t.value);
            $array.push(t1 ? t1.value : 0);
            $array.push(t2 ? t2.value : 0);
            $array.push(t3 ? t3.value : 0);
            $array.push(t4 ? t4.value : 0);
            $array.push(t5 ? t5.value : 0);
            $array.push(t6 ? t6.value : 0);
            $array.push(t7 ? t7.value : 0);
            $array.push(t8 ? t8.value : 0);
            $("#total_amt").val(sumNumbers($array));
        });
        $(document).on("click", "#removeRow", function(ev) {
            ev.preventDefault();
            var currentRow = $(this).parent().parent();
            $(currentRow).remove();
            row--;
        });
    </script>
@endsection
