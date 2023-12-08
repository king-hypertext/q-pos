@extends('layout.layout')
@section('content')
    @php
        use Illuminate\Support\Facades\DB;
        $products = DB::table('products')->get(['name']);
    @endphp
    <div class="card shadow border-0">
        <h1 class="card-title text-center">
            Today saved Orders: {{ $customer->name }}
        </h1>
        <div class="card-body">
            <button id="delete_all" type="button" class="btn btn-warning text-capitalize d-none">delete all</button>

            <form id="form-invoice" action="{{ route('order.show.save', [$customer->id]) }}" method="post">
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
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr class="form_row">
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
                                                class="form-control" value="0" />
                                        </div>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="form-group">
                                            <input required type="number" name="quantity[]"
                                                id="quantity_{{ $index + 1 }}" class="form-control qty"
                                                value="{{ $order->quantity }}" />
                                        </div>
                                    </td>
                                    <td class="col-md-3">
                                        <div class="form-group">
                                            <input readonly type="text" value="{{ $order->amount }}" name="total[]"
                                                id="total_{{ $index + 1 }}" class="form-control" />
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
                    </div>
                    <button type="submit" class="btn btn-success text-capitalize" title="add invoice">Save Order</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const showSuccessAlert = Swal.mixin({
            position: 'top-end',
            toast: true,
            timer: 6500,
            showConfirmButton: false,
            timerProgressBar: false,
        });
        var delete_all_btn = $('button#delete_all');
        console.log(delete_all_btn);
        $(delete_all_btn).on('click', function() {
            var ids = [];
            $('.btn-delete').each(function() {
                ids.push($(this).val());
            });
            $.ajax({
                url: '/order/saved/today/delete',
                type: 'PUT',
                data: {
                    "order_ids": ids,
                    "_token": '{{ csrf_token() }}'
                },
                success: function(res) {
                    console.log("success ", res);
                },
                error: function(res) {
                    console.log(res);
                }
            })
        })
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
        })
    </script>
@endsection
