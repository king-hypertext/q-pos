@extends('layout.layout')
@section('content')
    @php
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;
        $products = DB::table('products')->get(['name']);
    @endphp
    <div class="card shadow border-0">
        <h1 class="card-title text-center">
            {{ $customer->name }} - Order date:
            @php
                echo Carbon::parse($date)->format('Y-M-d');
            @endphp
        </h1>
        <div class="card-body">
            <form id="form-invoice" action="{{ route('order.show.save', [$customer->id]) }}" method="post">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="customer" value="{{ $customer->name }}">
                <input type="hidden" name="date" value="{{ Date('Y-m-d') }}">
                <input type="hidden" name="order_date" value="{{ $date }}">
                <hr class="hr text-dark" />
                <div class="table-responsive text-nowrap">
                    <table class="table table-borderless mb-0">
                        <thead>
                            <tr class="">
                                <th></th>
                                <th class="col-md-4" scope="col">Product Name</th>
                                <th class="col-md-3" scope="col" title="Price">Unit Price</th>
                                <th class="col-md-2" scope="col">Quantity</th>
                                <th class="col-md-3" scope="col">Total (GHC)</th>
                            </tr>
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr class="form_row">
                                    <td class="col-md-1">
                                        <input class="select_id" onchange="getId(this)" type="checkbox" class=""
                                            name="ids[]" id="ids_{{ $index + 1 }}" value="{{ $order->id }}" />
                                    </td>
                                    <td class="col-md-4">
                                        <div class="">
                                            <div class="">
                                                <div class="form-group">
                                                    <select required data-select-product name="product[]"
                                                        id="product_{{ $index + 1 }}" class="form-select">
                                                        <option selected> {{ $order->product }} </option>
                                                        {{-- @foreach ($products as $product)
                                                    <option value="{{ $product->name }}">
                                                        {{ $product->name }} </option>
                                                @endforeach --}}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-md-3">
                                        <div class="form-group">
                                            <input readonly type="number" name="price[]" type="text"
                                                value="{{ $order->price }}" id="price_{{ $index + 1 }}"
                                                class="form-control u-price" value="0" />
                                        </div>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="form-group">
                                            <input required type="number" name="quantity[]"
                                                id="quantity_{{ $index + 1 }}" class="form-control u-quantity"
                                                value="{{ $order->quantity }}" />
                                        </div>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="form-group">
                                            <input readonly type="text" value="{{ $order->amount }}" name="total[]"
                                                id="total_{{ $index + 1 }}" class="form-control u-total" />
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-delete" id="{{ $order->id }}"
                                            value="{{ $order->id }}" type="button" title="delete order">delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between mt-3">
                    <div class="px-0">
                        <button type="button" disabled class="btn btn-danger btn-delete-all">delete selected</button>
                    </div>
                    <button type="submit" class="btn btn-success text-capitalize" title="add invoice">Save Order</button>
                </div>
            </form>
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
        function getId() {}
        $(document).on('click', '.btn-delete-all', function(e) {
            var ids = [];
            var checkedValue;
            $('.select_id:checked').each(function() {
                checkedValue = $(this).val();
                if (!checkedValue) {

                } else {
                    ids.push(checkedValue)
                    console.log(ids);
                }
            });
           /*  $.ajax({
                url: '{{ route('delete.order.multiple') }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                },
                success: function(res) {
                    console.log(res);
                },
                error: function(res) {
                    console.log(res);
                }
            }) */
        });
        $(document).on('click', '.btn-delete', function(e) {
            $.ajax({
                url: "/order/delete/" + e.target.id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(res) {
                    console.log("success ", res);
                    if (res.success) {
                        location.reload();
                        showSuccessAlert.fire({
                            icon: 'success',
                            text: res.success,
                            padding: '10px',
                            width: 'auto'
                        })
                    }
                },
                error: function(res) {
                    console.log("error ", res);
                }
            })
        });
        $(document).ready(function() {
            $(document).on('keyup', '.u-quantity', function(elem) {
                var name = total = elem.target.parentElement.parentElement.parentElement.children[0]
                    .children[0].children[0].children[0].children[0].value;
                var price = elem.target.parentElement.parentElement.parentElement.children[1].children[0]
                    .children[0].value,
                    total = elem.target.parentElement.parentElement.parentElement.children[3].children[0]
                    .children[0];
                quantity = elem.target.value;
                $.ajax({
                    method: "GET",
                    url: "/product/price?q=" + name,
                    success: function(res) {
                        elem.target.max = res.data[0].quantity;
                    },
                });
                total.value = price * quantity;
            });
        })
    </script>
@endsection
