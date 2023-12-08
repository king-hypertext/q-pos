<div class="modal fade" id="modal-addCustomer" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="modal-addCustomer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="dialog">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-2">
                <div class="row justify-content-center">
                    <div class="divider my-0">
                        <div class="divider-text">
                            <h5 class="h3 text-capitalize">Add New Worker</h5>
                        </div>
                    </div>
                    <form autocomplete="off" class="px-5 py-2" action="{{ route('customer.add') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-outline mb-4">
                            <input required type="text" name="customer-name" id="cusName" placeholder="Enter worker name"
                                class="form-control autofocus" autofocus />
                            <label class="form-label" for="cusName">Worker Name</label>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="birth-date">Date of Birth</label>
                                <input type="date" max="{{ Date('Y-m-d') }}" name="dob" id="birth-date"
                                    class="form-control" />
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="gender">Gender</label>
                                <select name="gender" id="worker-gender" class="form-select">
                                    <option selected disabled>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-outline mb-4">
                            <input required type="number" name="contact"
                                id="cusContact" class="form-control" />
                            <label class="form-label" for="customerContact">Contact</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input type="text" name="address" id="cusAddress" class="form-control" />
                            <label class="form-label" for="cusAddress">Address</label>
                        </div>
                        <div class="image-preview-wrapper">
                            <img src="" alt="" class="image-preview" style="width: 55px; height: 55px;">
                        </div>
                        <div class="mb-4">
                            <label for="customerImage">Customer Image</label>
                            <input required type="file" onchange="preview()" name="customer-image" id="customerImage"
                                accept="image/*" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Add Customer</button>
                    </form>
                </div>
            </div>
            <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Discard</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var img_wrapper = $('.image-preview-wrapper');
        img_wrapper.hide();

        function preview() {
            var image = document.querySelector('img.image-preview');
            var input = $('input[type="file"]#customerImage')[0];
            var file = input.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                image.src = e.target.result;
                img_wrapper.show();
            });
            filereader.readAsDataURL(file);
        }
    </script>
</div>
