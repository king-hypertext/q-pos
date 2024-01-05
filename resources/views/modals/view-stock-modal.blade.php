<div class="modal fade" id="view-stock-modal" tabindex="-1" data-bs-backdrop="static" aria-modal="true"
    aria-labelledby="view-stock-modal" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenteredScrollableTitle">Saved Orders:
                    {{ $customer->name }}</h1>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    @if (count($orders) === 0)
                        <tr align="center">
                            <td colspan="4">
                                No Records Found
                            </td>
                        </tr>
                    @else
                        Select date and filter
                    @endif
                    <form id="filter-order" method="post">
                        Filter Order:
                        <input type="date" class="form-control form-control-sm d-inline-block" style="width: auto"
                            value="{{ Date('Y-m-d') }}" max="{{ Date('Y-m-d') }}" name="date" id="date" />
                        <input type="hidden" name="id" value="{{ $customer->id }}" />
                        <button type="submit" class="btn btn-sm btn-primary d-inline-block">Filter</button>
                    </form>
                </div>
                <div class="d-flex justify-content-center d-none">
                    <div class="alert alert-danger text-center" style="max-width: 50%!important" id="filter-response">
                    </div>
                </div>

                {{-- <form id="form-invoice" action="{{ route('order.show.save', [$customer->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="customer" value="{{ $customer->name }}">
                    <input type="hidden" name="date" value="{{ Date('Y-m-d') }}">
                    <input type="hidden" name="order_date" value="{{ Date('Y-m-d') }}">
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

                                @if (count($orders) === 0)
                                    <tr align="center">
                                        <td colspan="4">
                                            No Data Available
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($orders as $index => $order)
                                        <tr class="form_row">
                                            <td class="col-md-4">
                                                <div class="">
                                                    <div class="">
                                                        <div class="form-group">
                                                            <select required name="product[]"
                                                                id="product_{{ $index + 1 }}" class="form-select">
                                                                <option selected> {{ $order->product }} </option>
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
                                                    <input readonly type="number" name="price[]" type="text"
                                                        value="{{ $order->price }}" id="price_{{ $index + 1 }}"
                                                        class="form-control u-price" value="0" />
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input required type="number" name="quantity[]"
                                                        id="quantity_{{ $index + 1 }}"
                                                        class="form-control u-quantity"
                                                        value="{{ $order->quantity }}" />
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div class="form-group">
                                                    <input readonly type="text" value="{{ $order->amount }}"
                                                        name="total[]" id="total_{{ $index + 1 }}"
                                                        class="form-control u-total" />
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-delete" id="{{ $order->id }}"
                                                    value="{{ $order->id }}" type="button"
                                                    title="delete order">delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-between mt-3">
                        <div class="px-0">
                        </div>
                        <button type="submit"
                            class="btn btn-success text-capitalize  @empty($orders->all()) disabled @endempty"
                            title="add invoice">Save
                            Order</button>
                    </div>
                </form> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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
{{-- @section('js') --}}
<script>
    const showSuccessAlert = Swal.mixin({
        position: 'top-end',
        toast: true,
        timer: 6500,
        showConfirmButton: false,
        timerProgressBar: false,
    });

    $(document).ready(function() {
        $('#filter-order').on('submit', function(e) {
            e.preventDefault();
            console.log(e);
            var date = e.currentTarget[0].value;
            $.ajax({
                url: "{{ route('order.filter') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": e.currentTarget[1].value,
                    "date": date
                },
                success: function(res) {
                    if (res.success) {
                        console.log(res);
                        window.open(res.success + "?date=" + res.date, "_self");
                    } else if (res.empty) {
                        console.log(res.empty);
                        alert(res.empty)
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            })
        });
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
    })
</script>
{{-- @endsection --}}
