@extends('layout.layout')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize">Add New Product</h5>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="list-unstyled alert alert-danger my-2">
                                <ul class="d-flex justify-content-center">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('product.add') }}" class="px-5 py-2" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-outline mb-4">
                                <input required type="text" value="{{ @old('product-name') }}" name="product-name"
                                    id="productName" class="form-control" />
                                <label class="form-label" for="productName">Product Name</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input required type="number" value="{{ @old('unit-price') }}" step=".01"
                                            placeholder="0.00" name="unit-price" id="unitPrice" class="form-control" />
                                        <label class="form-label" for="unitPrice">Unit Price</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input type="text" name="batch-number" value="{{ @old('batch-number') }}"
                                            id="batchNumber" class="form-control" />
                                        <label class="form-label" for="batchNumber">Batch Number</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <label for="supplier" class="form-label">Supplier</label>
                                <select required name="supplier" id="supplier" class="form-select">
                                    <option selected disabled>Select Supplier</option>
                                    @php
                                        use App\Models\Suppliers;
                                        $suppliers = Suppliers::select('name')->get();
                                    @endphp
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="productionDate">Manufacturing Date</label>
                                    <input type="date" max="{{ Date('Y-m-d') }}" value="{{ @old('prod-date') }}"
                                        name="prod-date" id="productionDate" class="form-control" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="expiryDate">Expiry Date</label>
                                    <input type="date" min="{{ Date('Y-m-d') }}" value="{{ @old('expiry-date') }}"
                                        name="expiry-date" id="expiryDate" class="form-control" />
                                </div>
                            </div>
                            <div class="image-preview-wrapper">
                                <img src="" alt="" class="image-preview" style="width: 55px; height: 55px;">
                            </div>
                            <div class="mb-4">
                                <label for="productImage">Product Image</label>
                                <input required type="file" onchange="previewImage()" name="product-image"
                                    id="productImage" class="form-control" accept="image/*" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Save </button>
                            {{-- <button type="submit" class="btn btn-primary btn-block">Save And Close</button> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @php
            use App\Models\v1\Products;
            $products = Products::all();
        @endphp
        <div class="col-md-4">
            <div class="container">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Available Product Lists</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var img_wrapper = $('.image-preview-wrapper');
        img_wrapper.hide();

        function previewImage() {
            var image = document.querySelector('img.image-preview');
            var input = document.getElementById('productImage');
            var file = input.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                image.src = e.target.result;
                img_wrapper.show();
            });
            filereader.readAsDataURL(file);
        }
    </script>
@endsection
