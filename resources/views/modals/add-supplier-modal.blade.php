<div class="modal fade" id="modal-addSupplier" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="modal-addSupplier" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="dialog">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-2">
                <div class="row justify-content-center">
                    <div class="divider my-0">
                        <div class="divider-text">
                            <h5 class="h3 text-capitalize">Add New Supplier</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <img src="" alt="Product Image" class="d-none rounded-circle product-image bg-light" />
                    </div>
                    <form class="px-5 py-2" action="{{ route('supplier.add') }}" method="POST">
                        @csrf
                        <div class="form-outline mb-3">
                            <input required type="text" value="{{ @old('supplier-name') }}" name="supplier-name"
                                id="supplierName" class="form-control" />
                            <label class="form-label" for="supplierName">Supplier Name</label>
                        </div>
                        <div class="form-outline mb-3">
                            <select name="category" id="category" class="form-select">
                                <option value="" selected>Select Category</option>
                                <option value="allied">ALLIED</option>
                                <option value="shell">SHELL</option>
                            </select>
                        </div>
                        <div class="form-outline mb-4">
                            <input required type="text" value="{{ @old('contact') }}" name="contact" id="contact"
                                class="form-control" />
                            <label class="form-label" for="contact">Supplier's Contact</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input required type="text" value="{{ @old('address') }}" name="address"
                                id="supplierAddress" class="form-control" />
                            <label class="form-label" for="supplierAddress">Supplier's Address</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Add Supplier</button>
                    </form>
                </div>
            </div>
            <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Discard</button>
            </div>
        </div>
    </div>
</div>
