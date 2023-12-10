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
                    <a href="{{ route('order.show.update', [$customer->id]) }}" target="_blank" rel="noopener noreferrer"
                        role="link" title="click to edit and update today orders"
                        class="btn btn-success text-lowercase">saved orders</a>
                </div>
            </div>
        </div>
        @if ($empty_q !== 0)
            <div class="alert alert-danger mt-2 text-center">Some Products are out of stock, please top up now to see them
                in the list</div>
        @endif
        @if (count($empty_p) == 0)
            <div class="alert alert-danger mt-2 text-center">There are no products available, please <a
                    href="{{ route('products') }}" class="btn btn-link text-lowercase">Add Products</a> </div>
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
                            <th class="col-md-2" scope="col">Quantity</th>
                            <th class="col-md-3" scope="col" title="Price">Unit Price (GHC)</th>
                            <th class="col-md-3" scope="col">Total (GHC)</th>
                        </tr>
                    </thead>
                    <tbody id="td-parent">
                        <tr class="form_row">
                            <td class="col-md-4">
                                <div class="">
                                    <div class="">
                                        <div class="form-group">
                                            <select style="padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;"
                                                class="form-select form-select-lg" autofocus required data-select-product
                                                name="product[]" id="product">
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
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input required type="number" name="quantity[]" id="quantity"
                                        class="form-control qty" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" name="price[]" type="text"
                                        onfocus="this.type='number'" value="0" id="price" class="form-control"
                                        value="0" />
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
        var p, u1, u2, u3, u4, u5, u6, u7, u8, u9, u10, u11, u12, u13, u14, u15, u16, u17, u18, u19, u20, u21, u22, u23,
            u24,
            u25, u26, u27, u28, u29, u30, u31, u32, u33, u34, u35, u36, u37, u38, u39, u40, u41, u42, u43, u44, u45, u46,
            u47, u48, u49, u50, u51, u52, u53, u54, u55, u56, u57, u58, u59, u60, u61, u62, u63, u64, u65, u66, u67, u68,
            u69, u70, u71, u72, u73, u74, u75, u76, u77, u78, u79, u80, u81, u82, u83, u84, u85, u86, u87, u88, u89, u90,
            u91, u92, u93, u94, u95, u96, u97, u98, u99;
        var p, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, p13, p14, p15, p16, p17, p18, p19, p20, p21, p22, p23,
            p24,
            p25, p26, p27, p28, p29, p30, p31, p32, p33, p34, p35, p36, p37, p38, p39, p40, p41, p42, p43, p44, p45, p46,
            p47, p48, p49, p50, p51, p52, p53, p54, p55, p56, p57, p58, p59, p60, p61, p62, p63, p64, p65, p66, p67, p68,
            p69, p70, p71, p72, p73, p74, p75, p76, p77, p78, p79, p80, p81, p82, p83, p84, p85, p86, p87, p88, p89, p90,
            p91, p92, p93, p94, p95, p96, p97, p98, p99;
        var p, t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12, t13, t14, t15, t16, t17, t18, t19, t20, t21, t22, t23,
            t24,
            t25, t26, t27, t28, t29, t30, t31, t32, t33, t34, t35, t36, t37, t38, t39, t40, t41, t42, t43, t44, t45, t46,
            t47, t48, t49, t50, t51, t52, t53, t54, t55, t56, t57, t58, t59, t60, t61, t62, t63, t64, t65, t66, t67, t68,
            t69, t70, t71, t72, t73, t74, t75, t76, t77, t78, t79, t80, t81, t82, t83, t84, t85, t86, t87, t88, t89, t90,
            t91, t92, t93, t94, t95, t96, t97, t98, t99;
        var row = 1;
        $(document).ready(function() {

            window.addNewRow = function() {
                var newInvoiceRow = `<tr class="form_row_${row}">
                            <td class="col-md-4">
                                <div class="">
                                    <div class="">
                                        <div class="form-group">
                                            <select style="padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;" required name="product[]" id="product_${row}"
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
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input required type="number" name="quantity[]" id="quantity_${row}"
                                        class="form-control qty" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="number" name="price[]" type="text" value="0" id="price_${row}" class="form-control"
                                        value="0" />
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
                if (row <= 99) {
                    $("tbody#td-parent").append(newInvoiceRow);
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
                                        q1.max = res.data[0].quantity;
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
                                        q2.max = res.data[0].quantity;
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
                                        q3.max = res.data[0].quantity;
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
                                        q4.max = res.data[0].quantity;
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
                                        q5.max = res.data[0].quantity;
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
                                        q6.max = res.data[0].quantity;
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
                                        q7.max = res.data[0].quantity;
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
                                        q8.max = res.data[0].quantity;
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
                                        q9.max = res.data[0].quantity;
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
                                        q10.max = res.data[0].quantity;
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
                                        q11.max = res.data[0].quantity;
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
                                        q12.max = res.data[0].quantity;
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
                                        q13.max = res.data[0].quantity;
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
                                        q14.max = res.data[0].quantity;
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
                                        q15.max = res.data[0].quantity;
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
                                        q16.max = res.data[0].quantity;
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
                                        q17.max = res.data[0].quantity;
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
                                        q18.max = res.data[0].quantity;
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
                                        q19.max = res.data[0].quantity;
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
                                        q20.max = res.data[0].quantity;
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
                                        q21.max = res.data[0].quantity;
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
                                        q22.max = res.data[0].quantity;
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
                                        q23.max = res.data[0].quantity;
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
                                        q24.max = res.data[0].quantity;
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
                                        q25.max = res.data[0].quantity;
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
                                        q26.max = res.data[0].quantity;
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
                                        q27.max = res.data[0].quantity;
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
                                        q28.max = res.data[0].quantity;
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
                                        q29.max = res.data[0].quantity;
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
                                        q30.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q30 &&
                            (q30.onkeyup = function() {
                                t30.value = Number.parseFloat(u30.value) * Number.parseFloat(q30
                                    .value);
                            });
                        p31 = $("#product_31")[0];
                        u31 = $("#price_31")[0];
                        q31 = $("#quantity_31")[
                            0];
                        t31 = $("#total_31")[0];
                        p31 &&
                            (p31.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u31.value = res.data[0].price;
                                        q31.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q31 &&
                            (q31.onkeyup = function() {
                                t31.value = Number.parseFloat(u31.value) * Number.parseFloat(q31
                                    .value);
                            });
                        p32 = $("#product_32")[0];
                        u32 = $("#price_32")[0];
                        q32 = $("#quantity_32")[
                            0];
                        t32 = $("#total_32")[0];
                        p32 &&
                            (p32.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u32.value = res.data[0].price;
                                        q32.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q32 &&
                            (q32.onkeyup = function() {
                                t32.value = Number.parseFloat(u32.value) * Number.parseFloat(q32
                                    .value);
                            });
                        p33 = $("#product_33")[0];
                        u33 = $("#price_33")[0];
                        q33 = $("#quantity_33")[
                            0];
                        t33 = $("#total_33")[0];
                        p33 &&
                            (p33.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u33.value = res.data[0].price;
                                        q33.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q33 &&
                            (q33.onkeyup = function() {
                                t33.value = Number.parseFloat(u33.value) * Number.parseFloat(q33
                                    .value);
                            });
                        p34 = $("#product_34")[0];
                        u34 = $("#price_34")[0];
                        q34 = $("#quantity_34")[
                            0];
                        t34 = $("#total_34")[0];
                        p34 &&
                            (p34.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u34.value = res.data[0].price;
                                        q34.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q34 &&
                            (q34.onkeyup = function() {
                                t34.value = Number.parseFloat(u34.value) * Number.parseFloat(q34
                                    .value);
                            });
                        p35 = $("#product_35")[0];
                        u35 = $("#price_35")[0];
                        q35 = $("#quantity_35")[
                            0];
                        t35 = $("#total_35")[0];
                        p35 &&
                            (p35.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u35.value = res.data[0].price;
                                        q35.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q35 &&
                            (q35.onkeyup = function() {
                                t35.value = Number.parseFloat(u35.value) * Number.parseFloat(q35
                                    .value);
                            });
                        p36 = $("#product_36")[0];
                        u36 = $("#price_36")[0];
                        q36 = $("#quantity_36")[
                            0];
                        t36 = $("#total_36")[0];
                        p36 &&
                            (p36.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u36.value = res.data[0].price;
                                        q36.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q36 &&
                            (q36.onkeyup = function() {
                                t36.value = Number.parseFloat(u36.value) * Number.parseFloat(q36
                                    .value);
                            });
                        p37 = $("#product_37")[0];
                        u37 = $("#price_37")[0];
                        q37 = $("#quantity_37")[
                            0];
                        t37 = $("#total_37")[0];
                        p37 &&
                            (p37.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u37.value = res.data[0].price;
                                        q37.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q37 &&
                            (q37.onkeyup = function() {
                                t37.value = Number.parseFloat(u37.value) * Number.parseFloat(q37
                                    .value);
                            });
                        p38 = $("#product_38")[0];
                        u38 = $("#price_38")[0];
                        q38 = $("#quantity_38")[
                            0];
                        t38 = $("#total_38")[0];
                        p38 &&
                            (p38.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u38.value = res.data[0].price;
                                        q38.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q38 &&
                            (q38.onkeyup = function() {
                                t38.value = Number.parseFloat(u38.value) * Number.parseFloat(q38
                                    .value);
                            });
                        p39 = $("#product_39")[0];
                        u39 = $("#price_39")[0];
                        q39 = $("#quantity_39")[
                            0];
                        t39 = $("#total_39")[0];
                        p39 &&
                            (p39.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u39.value = res.data[0].price;
                                        q39.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q39 &&
                            (q39.onkeyup = function() {
                                t39.value = Number.parseFloat(u39.value) * Number.parseFloat(q39
                                    .value);
                            });
                        p40 = $("#product_40")[0];
                        u40 = $("#price_40")[0];
                        q40 = $("#quantity_40")[
                            0];
                        t40 = $("#total_40")[0];
                        p40 &&
                            (p40.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u40.value = res.data[0].price;
                                        q40.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q40 &&
                            (q40.onkeyup = function() {
                                t40.value = Number.parseFloat(u40.value) * Number.parseFloat(q40
                                    .value);
                            });
                        p41 = $("#product_41")[0];
                        u41 = $("#price_41")[0];
                        q41 = $("#quantity_41")[
                            0];
                        t41 = $("#total_41")[0];
                        p41 &&
                            (p41.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u41.value = res.data[0].price;
                                        q41.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q41 &&
                            (q41.onkeyup = function() {
                                t41.value = Number.parseFloat(u41.value) * Number.parseFloat(q41
                                    .value);
                            });
                        p42 = $("#product_42")[0];
                        u42 = $("#price_42")[0];
                        q42 = $("#quantity_42")[
                            0];
                        t42 = $("#total_42")[0];
                        p42 &&
                            (p42.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u42.value = res.data[0].price;
                                        q42.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q42 &&
                            (q42.onkeyup = function() {
                                t42.value = Number.parseFloat(u42.value) * Number.parseFloat(q42
                                    .value);
                            });
                        p43 = $("#product_43")[0];
                        u43 = $("#price_43")[0];
                        q43 = $("#quantity_43")[
                            0];
                        t43 = $("#total_43")[0];
                        p43 &&
                            (p43.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u43.value = res.data[0].price;
                                        q43.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q43 &&
                            (q43.onkeyup = function() {
                                t43.value = Number.parseFloat(u43.value) * Number.parseFloat(q43
                                    .value);
                            });
                        p44 = $("#product_44")[0];
                        u44 = $("#price_44")[0];
                        q44 = $("#quantity_44")[
                            0];
                        t44 = $("#total_44")[0];
                        p44 &&
                            (p44.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u44.value = res.data[0].price;
                                        q44.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q44 &&
                            (q44.onkeyup = function() {
                                t44.value = Number.parseFloat(u44.value) * Number.parseFloat(q44
                                    .value);
                            });
                        p45 = $("#product_45")[0];
                        u45 = $("#price_45")[0];
                        q45 = $("#quantity_45")[
                            0];
                        t45 = $("#total_45")[0];
                        p45 &&
                            (p45.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u45.value = res.data[0].price;
                                        q45.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q45 &&
                            (q45.onkeyup = function() {
                                t45.value = Number.parseFloat(u45.value) * Number.parseFloat(q45
                                    .value);
                            });
                        p46 = $("#product_46")[0];
                        u46 = $("#price_46")[0];
                        q46 = $("#quantity_46")[
                            0];
                        t46 = $("#total_46")[0];
                        p46 &&
                            (p46.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u46.value = res.data[0].price;
                                        q46.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q46 &&
                            (q46.onkeyup = function() {
                                t46.value = Number.parseFloat(u46.value) * Number.parseFloat(q46
                                    .value);
                            });
                        p47 = $("#product_47")[0];
                        u47 = $("#price_47")[0];
                        q47 = $("#quantity_47")[
                            0];
                        t47 = $("#total_47")[0];
                        p47 &&
                            (p47.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u47.value = res.data[0].price;
                                        q47.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q47 &&
                            (q47.onkeyup = function() {
                                t47.value = Number.parseFloat(u47.value) * Number.parseFloat(q47
                                    .value);
                            });
                        p48 = $("#product_48")[0];
                        u48 = $("#price_48")[0];
                        q48 = $("#quantity_48")[
                            0];
                        t48 = $("#total_48")[0];
                        p48 &&
                            (p48.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u48.value = res.data[0].price;
                                        q48.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q48 &&
                            (q48.onkeyup = function() {
                                t48.value = Number.parseFloat(u48.value) * Number.parseFloat(q48
                                    .value);
                            });
                        p49 = $("#product_49")[0];
                        u49 = $("#price_49")[0];
                        q49 = $("#quantity_49")[
                            0];
                        t49 = $("#total_49")[0];
                        p49 &&
                            (p49.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u49.value = res.data[0].price;
                                        q49.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q49 &&
                            (q49.onkeyup = function() {
                                t49.value = Number.parseFloat(u49.value) * Number.parseFloat(q49
                                    .value);
                            });
                        p50 = $("#product_50")[0];
                        u50 = $("#price_50")[0];
                        q50 = $("#quantity_50")[
                            0];
                        t50 = $("#total_50")[0];
                        p50 &&
                            (p50.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u50.value = res.data[0].price;
                                        q50.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q50 &&
                            (q50.onkeyup = function() {
                                t50.value = Number.parseFloat(u50.value) * Number.parseFloat(q50
                                    .value);
                            });
                        p51 = $("#product_51")[0];
                        u51 = $("#price_51")[0];
                        q51 = $("#quantity_51")[
                            0];
                        t51 = $("#total_51")[0];
                        p51 &&
                            (p51.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u51.value = res.data[0].price;
                                        q51.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q51 &&
                            (q51.onkeyup = function() {
                                t51.value = Number.parseFloat(u51.value) * Number.parseFloat(q51
                                    .value);
                            });
                        p52 = $("#product_52")[0];
                        u52 = $("#price_52")[0];
                        q52 = $("#quantity_52")[
                            0];
                        t52 = $("#total_52")[0];
                        p52 &&
                            (p52.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u52.value = res.data[0].price;
                                        q52.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q52 &&
                            (q52.onkeyup = function() {
                                t52.value = Number.parseFloat(u52.value) * Number.parseFloat(q52
                                    .value);
                            });
                        p53 = $("#product_53")[0];
                        u53 = $("#price_53")[0];
                        q53 = $("#quantity_53")[
                            0];
                        t53 = $("#total_53")[0];
                        p53 &&
                            (p53.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u53.value = res.data[0].price;
                                        q53.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q53 &&
                            (q53.onkeyup = function() {
                                t53.value = Number.parseFloat(u53.value) * Number.parseFloat(q53
                                    .value);
                            });
                        p54 = $("#product_54")[0];
                        u54 = $("#price_54")[0];
                        q54 = $("#quantity_54")[
                            0];
                        t54 = $("#total_54")[0];
                        p54 &&
                            (p54.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u54.value = res.data[0].price;
                                        q54.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q54 &&
                            (q54.onkeyup = function() {
                                t54.value = Number.parseFloat(u54.value) * Number.parseFloat(q54
                                    .value);
                            });
                        p55 = $("#product_55")[0];
                        u55 = $("#price_55")[0];
                        q55 = $("#quantity_55")[
                            0];
                        t55 = $("#total_55")[0];
                        p55 &&
                            (p55.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u55.value = res.data[0].price;
                                        q55.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q55 &&
                            (q55.onkeyup = function() {
                                t55.value = Number.parseFloat(u55.value) * Number.parseFloat(q55
                                    .value);
                            });
                        p56 = $("#product_56")[0];
                        u56 = $("#price_56")[0];
                        q56 = $("#quantity_56")[
                            0];
                        t56 = $("#total_56")[0];
                        p56 &&
                            (p56.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u56.value = res.data[0].price;
                                        q56.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q56 &&
                            (q56.onkeyup = function() {
                                t56.value = Number.parseFloat(u56.value) * Number.parseFloat(q56
                                    .value);
                            });
                        p57 = $("#product_57")[0];
                        u57 = $("#price_57")[0];
                        q57 = $("#quantity_57")[
                            0];
                        t57 = $("#total_57")[0];
                        p57 &&
                            (p57.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u57.value = res.data[0].price;
                                        q57.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q57 &&
                            (q57.onkeyup = function() {
                                t57.value = Number.parseFloat(u57.value) * Number.parseFloat(q57
                                    .value);
                            });
                        p58 = $("#product_58")[0];
                        u58 = $("#price_58")[0];
                        q58 = $("#quantity_58")[
                            0];
                        t58 = $("#total_58")[0];
                        p58 &&
                            (p58.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u58.value = res.data[0].price;
                                        q58.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q58 &&
                            (q58.onkeyup = function() {
                                t58.value = Number.parseFloat(u58.value) * Number.parseFloat(q58
                                    .value);
                            });
                        p59 = $("#product_59")[0];
                        u59 = $("#price_59")[0];
                        q59 = $("#quantity_59")[
                            0];
                        t59 = $("#total_59")[0];
                        p59 &&
                            (p59.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u59.value = res.data[0].price;
                                        q59.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q59 &&
                            (q59.onkeyup = function() {
                                t59.value = Number.parseFloat(u59.value) * Number.parseFloat(q59
                                    .value);
                            });
                        p60 = $("#product_60")[0];
                        u60 = $("#price_60")[0];
                        q60 = $("#quantity_60")[
                            0];
                        t60 = $("#total_60")[0];
                        p60 &&
                            (p60.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u60.value = res.data[0].price;
                                        q60.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q60 &&
                            (q60.onkeyup = function() {
                                t60.value = Number.parseFloat(u60.value) * Number.parseFloat(q60
                                    .value);
                            });
                        p61 = $("#product_61")[0];
                        u61 = $("#price_61")[0];
                        q61 = $("#quantity_61")[
                            0];
                        t61 = $("#total_61")[0];
                        p61 &&
                            (p61.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u61.value = res.data[0].price;
                                        q61.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q61 &&
                            (q61.onkeyup = function() {
                                t61.value = Number.parseFloat(u61.value) * Number.parseFloat(q61
                                    .value);
                            });
                        p62 = $("#product_62")[0];
                        u62 = $("#price_62")[0];
                        q62 = $("#quantity_62")[
                            0];
                        t62 = $("#total_62")[0];
                        p62 &&
                            (p62.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u62.value = res.data[0].price;
                                        q62.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q62 &&
                            (q62.onkeyup = function() {
                                t62.value = Number.parseFloat(u62.value) * Number.parseFloat(q62
                                    .value);
                            });
                        p63 = $("#product_63")[0];
                        u63 = $("#price_63")[0];
                        q63 = $("#quantity_63")[
                            0];
                        t63 = $("#total_63")[0];
                        p63 &&
                            (p63.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u63.value = res.data[0].price;
                                        q63.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q63 &&
                            (q63.onkeyup = function() {
                                t63.value = Number.parseFloat(u63.value) * Number.parseFloat(q63
                                    .value);
                            });
                        p64 = $("#product_64")[0];
                        u64 = $("#price_64")[0];
                        q64 = $("#quantity_64")[
                            0];
                        t64 = $("#total_64")[0];
                        p64 &&
                            (p64.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u64.value = res.data[0].price;
                                        q64.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q64 &&
                            (q64.onkeyup = function() {
                                t64.value = Number.parseFloat(u64.value) * Number.parseFloat(q64
                                    .value);
                            });
                        p65 = $("#product_65")[0];
                        u65 = $("#price_65")[0];
                        q65 = $("#quantity_65")[
                            0];
                        t65 = $("#total_65")[0];
                        p65 &&
                            (p65.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u65.value = res.data[0].price;
                                        q65.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q65 &&
                            (q65.onkeyup = function() {
                                t65.value = Number.parseFloat(u65.value) * Number.parseFloat(q65
                                    .value);
                            });
                        p66 = $("#product_66")[0];
                        u66 = $("#price_66")[0];
                        q66 = $("#quantity_66")[
                            0];
                        t66 = $("#total_66")[0];
                        p66 &&
                            (p66.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u66.value = res.data[0].price;
                                        q66.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q66 &&
                            (q66.onkeyup = function() {
                                t66.value = Number.parseFloat(u66.value) * Number.parseFloat(q66
                                    .value);
                            });
                        p67 = $("#product_67")[0];
                        u67 = $("#price_67")[0];
                        q67 = $("#quantity_67")[
                            0];
                        t67 = $("#total_67")[0];
                        p67 &&
                            (p67.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u67.value = res.data[0].price;
                                        q67.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q67 &&
                            (q67.onkeyup = function() {
                                t67.value = Number.parseFloat(u67.value) * Number.parseFloat(q67
                                    .value);
                            });
                        p68 = $("#product_68")[0];
                        u68 = $("#price_68")[0];
                        q68 = $("#quantity_68")[
                            0];
                        t68 = $("#total_68")[0];
                        p68 &&
                            (p68.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u68.value = res.data[0].price;
                                        q68.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q68 &&
                            (q68.onkeyup = function() {
                                t68.value = Number.parseFloat(u68.value) * Number.parseFloat(q68
                                    .value);
                            });
                        p69 = $("#product_69")[0];
                        u69 = $("#price_69")[0];
                        q69 = $("#quantity_69")[
                            0];
                        t69 = $("#total_69")[0];
                        p69 &&
                            (p69.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u69.value = res.data[0].price;
                                        q69.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q69 &&
                            (q69.onkeyup = function() {
                                t69.value = Number.parseFloat(u69.value) * Number.parseFloat(q69
                                    .value);
                            });
                        p70 = $("#product_70")[0];
                        u70 = $("#price_70")[0];
                        q70 = $("#quantity_70")[
                            0];
                        t70 = $("#total_70")[0];
                        p70 &&
                            (p70.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u70.value = res.data[0].price;
                                        q70.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q70 &&
                            (q70.onkeyup = function() {
                                t70.value = Number.parseFloat(u70.value) * Number.parseFloat(q70
                                    .value);
                            });
                        p71 = $("#product_71")[0];
                        u71 = $("#price_71")[0];
                        q71 = $("#quantity_71")[
                            0];
                        t71 = $("#total_71")[0];
                        p71 &&
                            (p71.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u71.value = res.data[0].price;
                                        q71.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q71 &&
                            (q71.onkeyup = function() {
                                t71.value = Number.parseFloat(u71.value) * Number.parseFloat(q71
                                    .value);
                            });
                        p72 = $("#product_72")[0];
                        u72 = $("#price_72")[0];
                        q72 = $("#quantity_72")[
                            0];
                        t72 = $("#total_72")[0];
                        p72 &&
                            (p72.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u72.value = res.data[0].price;
                                        q72.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q72 &&
                            (q72.onkeyup = function() {
                                t72.value = Number.parseFloat(u72.value) * Number.parseFloat(q72
                                    .value);
                            });
                        p73 = $("#product_73")[0];
                        u73 = $("#price_73")[0];
                        q73 = $("#quantity_73")[
                            0];
                        t73 = $("#total_73")[0];
                        p73 &&
                            (p73.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u73.value = res.data[0].price;
                                        q73.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q73 &&
                            (q73.onkeyup = function() {
                                t73.value = Number.parseFloat(u73.value) * Number.parseFloat(q73
                                    .value);
                            });
                        p74 = $("#product_74")[0];
                        u74 = $("#price_74")[0];
                        q74 = $("#quantity_74")[
                            0];
                        t74 = $("#total_74")[0];
                        p74 &&
                            (p74.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u74.value = res.data[0].price;
                                        q74.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q74 &&
                            (q74.onkeyup = function() {
                                t74.value = Number.parseFloat(u74.value) * Number.parseFloat(q74
                                    .value);
                            });
                        p75 = $("#product_75")[0];
                        u75 = $("#price_75")[0];
                        q75 = $("#quantity_75")[
                            0];
                        t75 = $("#total_75")[0];
                        p75 &&
                            (p75.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u75.value = res.data[0].price;
                                        q75.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q75 &&
                            (q75.onkeyup = function() {
                                t75.value = Number.parseFloat(u75.value) * Number.parseFloat(q75
                                    .value);
                            });
                        p76 = $("#product_76")[0];
                        u76 = $("#price_76")[0];
                        q76 = $("#quantity_76")[
                            0];
                        t76 = $("#total_76")[0];
                        p76 &&
                            (p76.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u76.value = res.data[0].price;
                                        q76.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q76 &&
                            (q76.onkeyup = function() {
                                t76.value = Number.parseFloat(u76.value) * Number.parseFloat(q76
                                    .value);
                            });
                        p77 = $("#product_77")[0];
                        u77 = $("#price_77")[0];
                        q77 = $("#quantity_77")[
                            0];
                        t77 = $("#total_77")[0];
                        p77 &&
                            (p77.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u77.value = res.data[0].price;
                                        q77.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q77 &&
                            (q77.onkeyup = function() {
                                t77.value = Number.parseFloat(u77.value) * Number.parseFloat(q77
                                    .value);
                            });
                        p78 = $("#product_78")[0];
                        u78 = $("#price_78")[0];
                        q78 = $("#quantity_78")[
                            0];
                        t78 = $("#total_78")[0];
                        p78 &&
                            (p78.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u78.value = res.data[0].price;
                                        q78.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q78 &&
                            (q78.onkeyup = function() {
                                t78.value = Number.parseFloat(u78.value) * Number.parseFloat(q78
                                    .value);
                            });
                        p79 = $("#product_79")[0];
                        u79 = $("#price_79")[0];
                        q79 = $("#quantity_79")[
                            0];
                        t79 = $("#total_79")[0];
                        p79 &&
                            (p79.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u79.value = res.data[0].price;
                                        q79.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q79 &&
                            (q79.onkeyup = function() {
                                t79.value = Number.parseFloat(u79.value) * Number.parseFloat(q79
                                    .value);
                            });
                        p80 = $("#product_80")[0];
                        u80 = $("#price_80")[0];
                        q80 = $("#quantity_80")[
                            0];
                        t80 = $("#total_80")[0];
                        p80 &&
                            (p80.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u80.value = res.data[0].price;
                                        q80.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q80 &&
                            (q80.onkeyup = function() {
                                t80.value = Number.parseFloat(u80.value) * Number.parseFloat(q80
                                    .value);
                            });
                        p81 = $("#product_81")[0];
                        u81 = $("#price_81")[0];
                        q81 = $("#quantity_81")[
                            0];
                        t81 = $("#total_81")[0];
                        p81 &&
                            (p81.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u81.value = res.data[0].price;
                                        q81.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q81 &&
                            (q81.onkeyup = function() {
                                t81.value = Number.parseFloat(u81.value) * Number.parseFloat(q81
                                    .value);
                            });
                        p82 = $("#product_82")[0];
                        u82 = $("#price_82")[0];
                        q82 = $("#quantity_82")[
                            0];
                        t82 = $("#total_82")[0];
                        p82 &&
                            (p82.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u82.value = res.data[0].price;
                                        q82.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q82 &&
                            (q82.onkeyup = function() {
                                t82.value = Number.parseFloat(u82.value) * Number.parseFloat(q82
                                    .value);
                            });
                        p83 = $("#product_83")[0];
                        u83 = $("#price_83")[0];
                        q83 = $("#quantity_83")[
                            0];
                        t83 = $("#total_83")[0];
                        p83 &&
                            (p83.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u83.value = res.data[0].price;
                                        q83.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q83 &&
                            (q83.onkeyup = function() {
                                t83.value = Number.parseFloat(u83.value) * Number.parseFloat(q83
                                    .value);
                            });
                        p84 = $("#product_84")[0];
                        u84 = $("#price_84")[0];
                        q84 = $("#quantity_84")[
                            0];
                        t84 = $("#total_84")[0];
                        p84 &&
                            (p84.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u84.value = res.data[0].price;
                                        q84.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q84 &&
                            (q84.onkeyup = function() {
                                t84.value = Number.parseFloat(u84.value) * Number.parseFloat(q84
                                    .value);
                            });
                        p85 = $("#product_85")[0];
                        u85 = $("#price_85")[0];
                        q85 = $("#quantity_85")[
                            0];
                        t85 = $("#total_85")[0];
                        p85 &&
                            (p85.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u85.value = res.data[0].price;
                                        q85.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q85 &&
                            (q85.onkeyup = function() {
                                t85.value = Number.parseFloat(u85.value) * Number.parseFloat(q85
                                    .value);
                            });
                        p86 = $("#product_86")[0];
                        u86 = $("#price_86")[0];
                        q86 = $("#quantity_86")[
                            0];
                        t86 = $("#total_86")[0];
                        p86 &&
                            (p86.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u86.value = res.data[0].price;
                                        q86.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q86 &&
                            (q86.onkeyup = function() {
                                t86.value = Number.parseFloat(u86.value) * Number.parseFloat(q86
                                    .value);
                            });
                        p87 = $("#product_87")[0];
                        u87 = $("#price_87")[0];
                        q87 = $("#quantity_87")[
                            0];
                        t87 = $("#total_87")[0];
                        p87 &&
                            (p87.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u87.value = res.data[0].price;
                                        q87.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q87 &&
                            (q87.onkeyup = function() {
                                t87.value = Number.parseFloat(u87.value) * Number.parseFloat(q87
                                    .value);
                            });
                        p88 = $("#product_88")[0];
                        u88 = $("#price_88")[0];
                        q88 = $("#quantity_88")[
                            0];
                        t88 = $("#total_88")[0];
                        p88 &&
                            (p88.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u88.value = res.data[0].price;
                                        q88.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q88 &&
                            (q88.onkeyup = function() {
                                t88.value = Number.parseFloat(u88.value) * Number.parseFloat(q88
                                    .value);
                            });
                        p89 = $("#product_89")[0];
                        u89 = $("#price_89")[0];
                        q89 = $("#quantity_89")[
                            0];
                        t89 = $("#total_89")[0];
                        p89 &&
                            (p89.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u89.value = res.data[0].price;
                                        q89.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q89 &&
                            (q89.onkeyup = function() {
                                t89.value = Number.parseFloat(u89.value) * Number.parseFloat(q89
                                    .value);
                            });
                        p90 = $("#product_90")[0];
                        u90 = $("#price_90")[0];
                        q90 = $("#quantity_90")[
                            0];
                        t90 = $("#total_90")[0];
                        p90 &&
                            (p90.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u90.value = res.data[0].price;
                                        q90.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q90 &&
                            (q90.onkeyup = function() {
                                t90.value = Number.parseFloat(u90.value) * Number.parseFloat(q90
                                    .value);
                            });
                        p91 = $("#product_91")[0];
                        u91 = $("#price_91")[0];
                        q91 = $("#quantity_91")[
                            0];
                        t91 = $("#total_91")[0];
                        p91 &&
                            (p91.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u91.value = res.data[0].price;
                                        q91.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q91 &&
                            (q91.onkeyup = function() {
                                t91.value = Number.parseFloat(u91.value) * Number.parseFloat(q91
                                    .value);
                            });
                        p92 = $("#product_92")[0];
                        u92 = $("#price_92")[0];
                        q92 = $("#quantity_92")[
                            0];
                        t92 = $("#total_92")[0];
                        p92 &&
                            (p92.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u92.value = res.data[0].price;
                                        q92.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q92 &&
                            (q92.onkeyup = function() {
                                t92.value = Number.parseFloat(u92.value) * Number.parseFloat(q92
                                    .value);
                            });
                        p93 = $("#product_93")[0];
                        u93 = $("#price_93")[0];
                        q93 = $("#quantity_93")[
                            0];
                        t93 = $("#total_93")[0];
                        p93 &&
                            (p93.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u93.value = res.data[0].price;
                                        q93.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q93 &&
                            (q93.onkeyup = function() {
                                t93.value = Number.parseFloat(u93.value) * Number.parseFloat(q93
                                    .value);
                            });
                        p94 = $("#product_94")[0];
                        u94 = $("#price_94")[0];
                        q94 = $("#quantity_94")[
                            0];
                        t94 = $("#total_94")[0];
                        p94 &&
                            (p94.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u94.value = res.data[0].price;
                                        q94.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q94 &&
                            (q94.onkeyup = function() {
                                t94.value = Number.parseFloat(u94.value) * Number.parseFloat(q94
                                    .value);
                            });
                        p95 = $("#product_95")[0];
                        u95 = $("#price_95")[0];
                        q95 = $("#quantity_95")[
                            0];
                        t95 = $("#total_95")[0];
                        p95 &&
                            (p95.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u95.value = res.data[0].price;
                                        q95.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q95 &&
                            (q95.onkeyup = function() {
                                t95.value = Number.parseFloat(u95.value) * Number.parseFloat(q95
                                    .value);
                            });
                        p96 = $("#product_96")[0];
                        u96 = $("#price_96")[0];
                        q96 = $("#quantity_96")[
                            0];
                        t96 = $("#total_96")[0];
                        p96 &&
                            (p96.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u96.value = res.data[0].price;
                                        q96.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q96 &&
                            (q96.onkeyup = function() {
                                t96.value = Number.parseFloat(u96.value) * Number.parseFloat(q96
                                    .value);
                            });
                        p97 = $("#product_97")[0];
                        u97 = $("#price_97")[0];
                        q97 = $("#quantity_97")[
                            0];
                        t97 = $("#total_97")[0];
                        p97 &&
                            (p97.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u97.value = res.data[0].price;
                                        q97.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q97 &&
                            (q97.onkeyup = function() {
                                t97.value = Number.parseFloat(u97.value) * Number.parseFloat(q97
                                    .value);
                            });
                        p98 = $("#product_98")[0];
                        u98 = $("#price_98")[0];
                        q98 = $("#quantity_98")[
                            0];
                        t98 = $("#total_98")[0];
                        p98 &&
                            (p98.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u98.value = res.data[0].price;
                                        q98.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q98 &&
                            (q98.onkeyup = function() {
                                t98.value = Number.parseFloat(u98.value) * Number.parseFloat(q98
                                    .value);
                            });
                        p99 = $("#product_99")[0];
                        u99 = $("#price_99")[0];
                        q99 = $("#quantity_99")[
                            0];
                        t99 = $("#total_99")[0];
                        p99 &&
                            (p99.onchange = function(elem) {
                                elem = elem.target.value;
                                $.ajax({
                                    method: "GET",
                                    url: "/product/price?q=" + elem,
                                    success: function(res) {
                                        u99.value = res.data[0].price;
                                        q99.max = res.data[0].quantity;
                                    },
                                });
                            });
                        q99 &&
                            (q99.onkeyup = function() {
                                t99.value = Number.parseFloat(u99.value) * Number.parseFloat(q99
                                    .value);
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
                $array.push(t31 ? t31.value : 0);
                $array.push(t32 ? t32.value : 0);
                $array.push(t33 ? t33.value : 0);
                $array.push(t34 ? t34.value : 0);
                $array.push(t35 ? t35.value : 0);
                $array.push(t36 ? t36.value : 0);
                $array.push(t37 ? t37.value : 0);
                $array.push(t38 ? t38.value : 0);
                $array.push(t39 ? t39.value : 0);
                $array.push(t40 ? t40.value : 0);
                $array.push(t41 ? t41.value : 0);
                $array.push(t42 ? t42.value : 0);
                $array.push(t43 ? t43.value : 0);
                $array.push(t44 ? t44.value : 0);
                $array.push(t45 ? t45.value : 0);
                $array.push(t46 ? t46.value : 0);
                $array.push(t47 ? t47.value : 0);
                $array.push(t48 ? t48.value : 0);
                $array.push(t49 ? t49.value : 0);
                $array.push(t50 ? t50.value : 0);
                $array.push(t51 ? t51.value : 0);
                $array.push(t52 ? t52.value : 0);
                $array.push(t53 ? t53.value : 0);
                $array.push(t54 ? t54.value : 0);
                $array.push(t55 ? t55.value : 0);
                $array.push(t56 ? t56.value : 0);
                $array.push(t57 ? t57.value : 0);
                $array.push(t58 ? t58.value : 0);
                $array.push(t59 ? t59.value : 0);
                $array.push(t60 ? t60.value : 0);
                $array.push(t61 ? t61.value : 0);
                $array.push(t62 ? t62.value : 0);
                $array.push(t63 ? t63.value : 0);
                $array.push(t64 ? t64.value : 0);
                $array.push(t65 ? t65.value : 0);
                $array.push(t66 ? t66.value : 0);
                $array.push(t67 ? t67.value : 0);
                $array.push(t68 ? t68.value : 0);
                $array.push(t69 ? t69.value : 0);
                $array.push(t70 ? t70.value : 0);
                $array.push(t71 ? t71.value : 0);
                $array.push(t72 ? t72.value : 0);
                $array.push(t73 ? t73.value : 0);
                $array.push(t74 ? t74.value : 0);
                $array.push(t75 ? t75.value : 0);
                $array.push(t76 ? t76.value : 0);
                $array.push(t77 ? t77.value : 0);
                $array.push(t78 ? t78.value : 0);
                $array.push(t79 ? t79.value : 0);
                $array.push(t80 ? t80.value : 0);
                $array.push(t81 ? t81.value : 0);
                $array.push(t82 ? t82.value : 0);
                $array.push(t83 ? t83.value : 0);
                $array.push(t84 ? t84.value : 0);
                $array.push(t85 ? t85.value : 0);
                $array.push(t86 ? t86.value : 0);
                $array.push(t87 ? t87.value : 0);
                $array.push(t88 ? t88.value : 0);
                $array.push(t89 ? t89.value : 0);
                $array.push(t90 ? t90.value : 0);
                $array.push(t91 ? t91.value : 0);
                $array.push(t92 ? t92.value : 0);
                $array.push(t93 ? t93.value : 0);
                $array.push(t94 ? t94.value : 0);
                $array.push(t95 ? t95.value : 0);
                $array.push(t96 ? t96.value : 0);
                $array.push(t97 ? t97.value : 0);
                $array.push(t98 ? t98.value : 0);
                $array.push(t99 ? t99.value : 0);
                $("#total_amt").val(sumNumbers($array));
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
