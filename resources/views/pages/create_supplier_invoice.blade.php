@extends('layout.layout')
@section('content')
    <style>
        input[readonly] {
            background-color: #fff !important;
            cursor: not-allowed;
        }
    </style>
    <div class="container">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-unstyled d-flex justify-content-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <form autocomplete="off" id="form-invoice" action="{{ route('supplier.invoice.add') }}" method="post">
            <div class="container">
                <div class="d-flex justify-content-start">
                    <div class="form-group mx-2">
                        <label for="supplier" class="d-block">Supplier: </label>
                        <select required name="supplier" id="supplier" class="form-select" style="min-width: 250px">
                            <option value="" selected>Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mx-2" style="min-width: 250px">
                        <label for="category">Category:</label>
                        <input type="text" class="form-control" id="category" name="category" readonly>
                    </div>
                    <div class="form-group mx-2" style="min-width: 250px">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" readonly>
                    </div>
                    <div class="form-group mx-2" style="min-width: 250px">
                        <label for="phone">Phone Number:</label>
                        <input type="text" class="form-control" id="phone" name="phone" readonly>
                    </div>
                </div>
            </div>
            @csrf
            <div class="container">
                <div class="form-group mx-2" style="max-width: 250px">
                    <label for="supplier-invoice">Invoice Number: </label>
                    <input required type="number" placeholder="Invoice Number" class="form-control" id="supplier-invoice"
                        name="supplier-invoice" />
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group mx-2" style="max-width: 250px">
                        <label for="invoice-date">Invoice Date: </label>
                        <input required type="date" value="{{ Date('Y-m-d') }}" class="form-control" id="invoice-date"
                            name="invoice-date" />
                    </div>
                    <div class="mx-2">
                        <button class="btn btn-success mt-4" type="button" data-bs-toggle="modal" data-bs-target="#modal-saved-invoice">Saved
                            Invoices
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="supplier-id">
            <hr class="hr text-dark" />
            <h6 class="h4">Create Invoice</h6>
            <div class="table-responsive text-nowrap">
                <style>
                    #create-order> :not(caption)>*>* {
                        padding: 0;
                    }

                    #create-order .form-control,
                    #create-order .form-select {
                        border-radius: 0 !important;
                        box-shadow: none;
                    }
                </style>
                <table id="create-order" class="table table-bordered mb-0">
                    <thead>
                        <tr class="p-3">
                            <th class="col-md-4 p-3" scope="col">Product Name</th>
                            <th class="col-md-3 p-3" scope="col" title="Price">Price (GHC)</th>
                            <th class="col-md-2 p-3" scope="col">Quantity</th>
                            <th class="col-md-3 p-3" scope="col">Total (GHC)</th>
                        </tr>
                    </thead>
                    <tbody id="td-parent">
                        <tr class="form_row">
                            <td class="col-md-4">
                                <div class="form-group">
                                    <select @required(true) class="form-select" data-select-product name="product[]"
                                        id="product">
                                        <option selected value=""> Select Product </option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->name }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="price[]" onfocus="this.select()" type="number"
                                        step=".01" value="0.00" id="price" class="form-control" />
                                </div>
                            </td>
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input type="number" name="quantity[]" id="quantity" class="form-control qty" />
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
                </table>
            </div>
            <div class="modal-footer justify-content-between mb-5">
                <div class="px-0">
                    <button type="button" onclick="addNewRow()" class="btn btn-warning add-row"><i
                            class="bx bx-plus"></i>
                        Add Row
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" title="reset inputs">Reset</button>
                </div>
                <button type="submit" class="btn btn-primary text-capitalize" title="add invoice">Save</button>
            </div>
        </form>
    </div>
    @include('modals.show-invoice-modal')
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
        var select = $('select[name="supplier"]')
        select.select2();
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        select.on('change', (e) => {
            var cat = $('input[name="category"]'),
                address = $('input[name="address"]'),
                phone = $('input[name="phone"]'),
                id = $('input[name="supplier-id"]');
            $.ajax({
                url: "/supplier/data/" + e.target.value,
                success: function(res) {
                    console.log(res.data[0]);
                    cat.val(res.data[0].category).addClass('active');
                    address.val(res.data[0].address).addClass('active');
                    phone.val(res.data[0].contact).addClass('active');
                    id.val(res.data[0].id);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })
        var p, u1, u2, u3, u4, u5, u6, u7, u8, u9, u10, u11, u12, u13, u14, u15, u16, u17, u18, u19, u20, u21, u22, u23,
            u24, u25, u26, u27, u28, u29, u30;
        var p, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, p13, p14, p15, p16, p17, p18, p19, p20, p21, p22, p23,
            p24, p25, p26, p27, p28, p29, p30;
        var p, t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12, t13, t14, t15, t16, t17, t18, t19, t20, t21, t22, t23,
            t24, t25, t26, t27, t28, t29, t30;
        var row = 1;
        $(document).ready(function() {

            window.addNewRow = function() {
                var newInvoiceRow = `<tr class="form_row_${row}">
                    <td class="col-md-4">
                        <div class="">
                            <div class="">
                                <div class="form-group">
                                    <select @required(true) style="padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;" required name="product[]" id="product_${row}"
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
                            <input required type="number" name="price[]" onfocus="this.select()" value="0.00" step=".01" id="price_${row}" class="form-control"/>
                        </div>
                    </td>                      
                    <td class="col-md-2">
                        <div class="form-group">
                            <input required type="number" name="quantity[]" id="quantity_${row}"
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
                if (row <= 30) {
                    $("tbody#td-parent").append(newInvoiceRow);
                    row++;
                    $(function() {
                        $("select.select-product").select2();
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
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

                                    },
                                });
                            });
                        q1 &&
                            (q1.onkeyup = function() {
                                t1.value = Number.parseFloat(u1.value) * Number.parseFloat(q1
                                    .value);
                            });

                        p2 = $("#product_2")[0];
                        u2 = $("#price_2")[0];
                        q2 = $("#quantity_2")[0];
                        t2 = $(
                            "#total_2")[0];
                        p2 &&
                            (p2.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u2.value = res.data[0].price;

                                    },
                                });
                            });
                        q2 &&
                            (q2.onkeyup = function() {
                                t2.value = Number.parseFloat(u2.value) * Number.parseFloat(q2
                                    .value);
                            });
                        p3 = $("#product_3")[0];
                        u3 = $("#price_3")[0];
                        q3 = $("#quantity_3")[0];
                        t3 =
                            $("#total_3")[0];
                        p3 &&
                            (p3.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u3.value = res.data[0].price;

                                    },
                                });
                            });
                        q3 &&
                            (q3.onkeyup = function() {
                                t3.value = Number.parseFloat(u3.value) * Number.parseFloat(q3
                                    .value);
                            });

                        p4 = $("#product_4")[0];
                        u4 = $("#price_4")[0];
                        q4 = $("#quantity_4")[0];
                        t4 = $(
                            "#total_4")[0];
                        p4 &&
                            (p4.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u4.value = res.data[0].price;

                                    },
                                });
                            });
                        q4 &&
                            (q4.onkeyup = function() {
                                t4.value = Number.parseFloat(u4.value) * Number.parseFloat(q4
                                    .value);
                            });

                        p5 = $("#product_5")[0];
                        u5 = $("#price_5")[0];
                        q5 = $("#quantity_5")[0];
                        t5 = $(
                            "#total_5")[0];
                        p5 &&
                            (p5.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u5.value = res.data[0].price;

                                    },
                                });
                            });
                        q5 &&
                            (q5.onkeyup = function() {
                                t5.value = Number.parseFloat(u5.value) * Number.parseFloat(q5
                                    .value);
                            });

                        p6 = $("#product_6")[0];
                        u6 = $("#price_6")[0];
                        q6 = $("#quantity_6")[0];
                        t6 = $(
                            "#total_6")[0];
                        p6 &&
                            (p6.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u6.value = res.data[0].price;

                                    },
                                });
                            });
                        q6 &&
                            (q6.onkeyup = function() {
                                t6.value = Number.parseFloat(u6.value) * Number.parseFloat(q6
                                    .value);
                            });

                        p7 = $("#product_7")[0];
                        u7 = $("#price_7")[0];
                        q7 = $("#quantity_7")[0];
                        t7 = $(
                            "#total_7")[0];
                        p7 &&
                            (p7.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u7.value = res.data[0].price;

                                    },
                                });
                            });
                        q7 &&
                            (q7.onkeyup = function() {
                                t7.value = Number.parseFloat(u7.value) * Number.parseFloat(q7
                                    .value);
                            });

                        p8 = $("#product_8")[0];
                        u8 = $("#price_8")[0];
                        q8 = $("#quantity_8")[0];
                        t8 = $(
                            "#total_8")[0];
                        p8 &&
                            (p8.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u8.value = res.data[0].price;

                                    },
                                });
                            });
                        q8 &&
                            (q8.onkeyup = function() {
                                t8.value = Number.parseFloat(u8.value) * Number.parseFloat(q8
                                    .value);
                            });

                        p9 = $("#product_9")[0];
                        u9 = $("#price_9")[0];
                        q9 = $("#quantity_9")[0];
                        t9 = $(
                            "#total_9")[0];
                        p9 &&
                            (p9.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u9.value = res.data[0].price;

                                    },
                                });
                            });
                        q9 &&
                            (q9.onkeyup = function() {
                                t9.value = Number.parseFloat(u9.value) * Number.parseFloat(q9
                                    .value);
                            });
                        p10 = $("#product_10")[0];
                        u10 = $("#price_10")[0];
                        q10 = $("#quantity_10")[
                            0];
                        t10 = $("#total_10")[0];
                        p10 &&
                            (p10.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u10.value = res.data[0].price;

                                    },
                                });
                            });
                        q10 &&
                            (q10.onkeyup = function() {
                                t10.value = Number.parseFloat(u10.value) * Number.parseFloat(q10
                                    .value);
                            });
                        p11 = $("#product_11")[0];
                        u11 = $("#price_11")[0];
                        q11 = $("#quantity_11")[
                            0];
                        t11 = $("#total_11")[0];
                        p11 &&
                            (p11.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u11.value = res.data[0].price;

                                    },
                                });
                            });
                        q11 &&
                            (q11.onkeyup = function() {
                                t11.value = Number.parseFloat(u11.value) * Number.parseFloat(q11
                                    .value);
                            });
                        p12 = $("#product_12")[0];
                        u12 = $("#price_12")[0];
                        q12 = $("#quantity_12")[
                            0];
                        t12 = $("#total_12")[0];
                        p12 &&
                            (p12.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u12.value = res.data[0].price;

                                    },
                                });
                            });
                        q12 &&
                            (q12.onkeyup = function() {
                                t12.value = Number.parseFloat(u12.value) * Number.parseFloat(q12
                                    .value);
                            });
                        p13 = $("#product_13")[0];
                        u13 = $("#price_13")[0];
                        q13 = $("#quantity_13")[
                            0];
                        t13 = $("#total_13")[0];
                        p13 &&
                            (p13.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u13.value = res.data[0].price;

                                    },
                                });
                            });
                        q13 &&
                            (q13.onkeyup = function() {
                                t13.value = Number.parseFloat(u13.value) * Number.parseFloat(q13
                                    .value);
                            });
                        p14 = $("#product_14")[0];
                        u14 = $("#price_14")[0];
                        q14 = $("#quantity_14")[
                            0];
                        t14 = $("#total_14")[0];
                        p14 &&
                            (p14.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u14.value = res.data[0].price;

                                    },
                                });
                            });
                        q14 &&
                            (q14.onkeyup = function() {
                                t14.value = Number.parseFloat(u14.value) * Number.parseFloat(q14
                                    .value);
                            });
                        p15 = $("#product_15")[0];
                        u15 = $("#price_15")[0];
                        q15 = $("#quantity_15")[
                            0];
                        t15 = $("#total_15")[0];
                        p15 &&
                            (p15.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u15.value = res.data[0].price;

                                    },
                                });
                            });
                        q15 &&
                            (q15.onkeyup = function() {
                                t15.value = Number.parseFloat(u15.value) * Number.parseFloat(q15
                                    .value);
                            });
                        p16 = $("#product_16")[0];
                        u16 = $("#price_16")[0];
                        q16 = $("#quantity_16")[
                            0];
                        t16 = $("#total_16")[0];
                        p16 &&
                            (p16.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u16.value = res.data[0].price;

                                    },
                                });
                            });
                        q16 &&
                            (q16.onkeyup = function() {
                                t16.value = Number.parseFloat(u16.value) * Number.parseFloat(q16
                                    .value);
                            });
                        p17 = $("#product_17")[0];
                        u17 = $("#price_17")[0];
                        q17 = $("#quantity_17")[
                            0];
                        t17 = $("#total_17")[0];
                        p17 &&
                            (p17.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u17.value = res.data[0].price;

                                    },
                                });
                            });
                        q17 &&
                            (q17.onkeyup = function() {
                                t17.value = Number.parseFloat(u17.value) * Number.parseFloat(q17
                                    .value);
                            });
                        p18 = $("#product_18")[0];
                        u18 = $("#price_18")[0];
                        q18 = $("#quantity_18")[
                            0];
                        t18 = $("#total_18")[0];
                        p18 &&
                            (p18.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u18.value = res.data[0].price;

                                    },
                                });
                            });
                        q18 &&
                            (q18.onkeyup = function() {
                                t18.value = Number.parseFloat(u18.value) * Number.parseFloat(q18
                                    .value);
                            });
                        p19 = $("#product_19")[0];
                        u19 = $("#price_19")[0];
                        q19 = $("#quantity_19")[
                            0];
                        t19 = $("#total_19")[0];
                        p19 &&
                            (p19.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u19.value = res.data[0].price;

                                    },
                                });
                            });
                        q19 &&
                            (q19.onkeyup = function() {
                                t19.value = Number.parseFloat(u19.value) * Number.parseFloat(q19
                                    .value);
                            });
                        p20 = $("#product_20")[0];
                        u20 = $("#price_20")[0];
                        q20 = $("#quantity_20")[
                            0];
                        t20 = $("#total_20")[0];
                        p20 &&
                            (p20.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u20.value = res.data[0].price;

                                    },
                                });
                            });
                        q20 &&
                            (q20.onkeyup = function() {
                                t20.value = Number.parseFloat(u20.value) * Number.parseFloat(q20
                                    .value);
                            });
                        p21 = $("#product_21")[0];
                        u21 = $("#price_21")[0];
                        q21 = $("#quantity_21")[
                            0];
                        t21 = $("#total_21")[0];
                        p21 &&
                            (p21.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u21.value = res.data[0].price;

                                    },
                                });
                            });
                        q21 &&
                            (q21.onkeyup = function() {
                                t21.value = Number.parseFloat(u21.value) * Number.parseFloat(q21
                                    .value);
                            });
                        p22 = $("#product_22")[0];
                        u22 = $("#price_22")[0];
                        q22 = $("#quantity_22")[
                            0];
                        t22 = $("#total_22")[0];
                        p22 &&
                            (p22.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u22.value = res.data[0].price;

                                    },
                                });
                            });
                        q22 &&
                            (q22.onkeyup = function() {
                                t22.value = Number.parseFloat(u22.value) * Number.parseFloat(q22
                                    .value);
                            });
                        p23 = $("#product_23")[0];
                        u23 = $("#price_23")[0];
                        q23 = $("#quantity_23")[
                            0];
                        t23 = $("#total_23")[0];
                        p23 &&
                            (p23.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u23.value = res.data[0].price;

                                    },
                                });
                            });
                        q23 &&
                            (q23.onkeyup = function() {
                                t23.value = Number.parseFloat(u23.value) * Number.parseFloat(q23
                                    .value);
                            });
                        p24 = $("#product_24")[0];
                        u24 = $("#price_24")[0];
                        q24 = $("#quantity_24")[
                            0];
                        t24 = $("#total_24")[0];
                        p24 &&
                            (p24.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u24.value = res.data[0].price;

                                    },
                                });
                            });
                        q24 &&
                            (q24.onkeyup = function() {
                                t24.value = Number.parseFloat(u24.value) * Number.parseFloat(q24
                                    .value);
                            });
                        p25 = $("#product_25")[0];
                        u25 = $("#price_25")[0];
                        q25 = $("#quantity_25")[
                            0];
                        t25 = $("#total_25")[0];
                        p25 &&
                            (p25.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u25.value = res.data[0].price;

                                    },
                                });
                            });
                        q25 &&
                            (q25.onkeyup = function() {
                                t25.value = Number.parseFloat(u25.value) * Number.parseFloat(q25
                                    .value);
                            });
                        p26 = $("#product_26")[0];
                        u26 = $("#price_26")[0];
                        q26 = $("#quantity_26")[
                            0];
                        t26 = $("#total_26")[0];
                        p26 &&
                            (p26.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u26.value = res.data[0].price;

                                    },
                                });
                            });
                        q26 &&
                            (q26.onkeyup = function() {
                                t26.value = Number.parseFloat(u26.value) * Number.parseFloat(q26
                                    .value);
                            });
                        p27 = $("#product_27")[0];
                        u27 = $("#price_27")[0];
                        q27 = $("#quantity_27")[
                            0];
                        t27 = $("#total_27")[0];
                        p27 &&
                            (p27.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u27.value = res.data[0].price;

                                    },
                                });
                            });
                        q27 &&
                            (q27.onkeyup = function() {
                                t27.value = Number.parseFloat(u27.value) * Number.parseFloat(q27
                                    .value);
                            });
                        p28 = $("#product_28")[0];
                        u28 = $("#price_28")[0];
                        q28 = $("#quantity_28")[
                            0];
                        t28 = $("#total_28")[0];
                        p28 &&
                            (p28.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u28.value = res.data[0].price;

                                    },
                                });
                            });
                        q28 &&
                            (q28.onkeyup = function() {
                                t28.value = Number.parseFloat(u28.value) * Number.parseFloat(q28
                                    .value);
                            });
                        p29 = $("#product_29")[0];
                        u29 = $("#price_29")[0];
                        q29 = $("#quantity_29")[
                            0];
                        t29 = $("#total_29")[0];
                        p29 &&
                            (p29.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u29.value = res.data[0].price;

                                    },
                                });
                            });
                        q29 &&
                            (q29.onkeyup = function() {
                                t29.value = Number.parseFloat(u29.value) * Number.parseFloat(q29
                                    .value);
                            });
                        p30 = $("#product_30")[0];
                        u30 = $("#price_30")[0];
                        q30 = $("#quantity_30")[
                            0];
                        t30 = $("#total_30")[0];
                        p30 &&
                            (p30.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u30.value = res.data[0].price;

                                    },
                                });
                            });
                        q30 &&
                            (q30.onkeyup = function() {
                                t30.value = Number.parseFloat(u30.value) * Number.parseFloat(q30
                                    .value);
                            });
                    });
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
                $array.push(t9 ? t9.value : 0);
                $array.push(t10 ? t10.value : 0);
                $array.push(t11 ? t11.value : 0);
                $array.push(t12 ? t12.value : 0);
                $array.push(t13 ? t13.value : 0);
                $array.push(t14 ? t14.value : 0);
                $array.push(t15 ? t15.value : 0);
                $array.push(t16 ? t16.value : 0);
                $array.push(t17 ? t17.value : 0);
                $array.push(t18 ? t18.value : 0);
                $array.push(t19 ? t19.value : 0);
                $array.push(t20 ? t20.value : 0);
                $array.push(t21 ? t21.value : 0);
                $array.push(t22 ? t22.value : 0);
                $array.push(t23 ? t23.value : 0);
                $array.push(t24 ? t24.value : 0);
                $array.push(t25 ? t25.value : 0);
                $array.push(t26 ? t26.value : 0);
                $array.push(t27 ? t27.value : 0);
                $array.push(t28 ? t28.value : 0);
                $array.push(t29 ? t29.value : 0);
                $array.push(t30 ? t30.value : 0);
            });
            $(document).on("click", "#removeRow", function(ev) {
                ev.preventDefault();
                var currentRow = $(this).parent().parent();
                $(currentRow).remove();
                row--;
            });

            $("select[data-select-product]").select2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            // $.fn.select2.defaults.set("theme", "bootstrap-5");
        });
    </script>
@endsection
