@extends('layout.layout')
@section('content')
    <style>
        .product-image-view {
            width: 200px;
            height: auto;
            max-height: 200px;
            /* border-radius: 50%; */
            background-size: cover;
            background: #2222;
        }
    </style>
    @php
        use Carbon\Carbon;
        use App\Models\Suppliers;
        $suppliers = Suppliers::select('category')->get();
    @endphp
    <div class="container">
        {{-- <div class="row"> --}}
        <div class="col-sm-12 col-md-5 mt-3">
            <div class="d-flex justify-content-start">
                @if ($data->product_image !== '')
                    <img src="{{ asset('assets/images/products/' . $data->product_image) }}" alt="{{ $data->product_image }}"
                        class="product-image-view">
                @else
                    <img src="" alt="product-image" class="product-image-view">
                @endif
                <table class="table table-striped ms-5" id="product-table" style="max-width: 500px">
                    <tr>
                        <th scope="col">ID</th>
                        <td scope="row">{{ $data->id }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Product Name</th>
                        <td>{{ $data->product_name }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Unit Price</th>
                        <td>{{ $data->price }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Available Qty</th>
                        <td>{{ $data->quantity }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Supplied By</th>
                        <td>{{ $data->supplier_name }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Expiry Date</th>
                        <td>{{ Carbon::parse($data->expiry_date)->format('Y-M-d') }}</td>
                    </tr>
                    {{-- </thead> --}}
                </table>
            </div>
        </div>
        <div class="col-sm-12 col-md-7 mt-4 card shadow border-0">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="d-flex justify-content-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="divider my-0">
                <div class="divider-text">
                    <h5 class="h3 text-capitalize">Edit / Update {{ $data->product_name }}</h5>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <img id="product-image" src="" alt="Product Image"
                    class="rounded-circle product-image bg-light d-none" />
            </div>
            <form class="px-5 py-2" action="{{ route('product.update', [$data->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-outline mb-4">
                    <input required type="text" value="{{ $data->product_name }}" name="product-name" id="productName"
                        class="form-control" />
                    <label class="form-label" for="productName">Product Name</label>
                </div>
                <div class="form-outline mb-4">
                    <input required type="text" value="{{ $data->price }}" onfocus="this.type='number'"
                        name="unit-price" id="unitPrice" class="form-control" />
                    <label class="form-label" for="unitPrice">Unit Price</label>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="form-outline">
                            <input disabled type="text" value="{{ $data->quantity }}" onfocus="this.type='number'"
                                name="qty" id="quantity" class="form-control" />
                            <label class="form-label" for="quantity">Available Quantity</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-outline">
                            <input type="text" value="{{ $data->batch_number }}" name="batch-number" id="batchNumber"
                                class="form-control" />
                            <label class="form-label" for="batchNumber">Batch Number</label>
                        </div>
                    </div>
                </div>
                <div class="form-outline mb-4">
                    <label for="supplier" class="form-label">Supplier</label>
                    <select required name="supplier" id="supplier" class="form-select">
                        <option selected value="{{ $data->supplier_name }}">{{ $data->supplier_name }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->category }}">{{ $supplier->category }}</option>
                            @if ($data->supplier_name == 'shell')
                            @elseif ($data->supplier_name == 'allied')
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label" for="productionDate">Manufacturing Date</label>
                        <input type="date" value="{{ $data->prod_date }}" max="{{ Date('Y-m-d') }}" name="prod-date"
                            id="productionDate" class="form-control" />
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="expiryDate">Expiry Date</label>
                        <input required type="date" value="{{ $data->expiry_date }}" min="{{ Date('Y-m-d') }}"
                            name="exp-date" id="expiryDate" class="form-control" />
                    </div>
                </div>
                <div class="image-preview-wrapper">
                    <img src="" alt="" class="image-preview" style="width: 55px; height: 55px;">
                </div>
                <div class="mb-4">
                    <label for="productImage">Product Image</label>
                    <input type="file" onchange="previewImage()" name="product-image" id="productImage"
                        class="form-control" accept="image/*" />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Product</button>
            </form>
        </div>
    </div>
    <script>
        var img_wrapper = $('.image-preview-wrapper');
        img_wrapper.hide();

        function previewImage() {
            var image = document.querySelector('img.image-preview');
            const input = document.getElementById('productImage');
            const file = input.files[0];
            console.log(image);
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                image.src = e.target.result;
                img_wrapper.show();
            });
            filereader.readAsDataURL(file);
        }
    </script>
@endsection
@section('js')
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
