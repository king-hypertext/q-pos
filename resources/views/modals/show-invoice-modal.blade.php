<div class="modal fade" id="modal-saved-invoice" tabindex="-1" data-bs-backdrop="static" aria-modal="true"
    aria-labelledby="modal-saved-invoice" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenteredScrollableTitle">Saved Orders:</h1>
                <div class="">
                    Select date and filter
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-center">
                        <div class="alert alert-danger text-center  d-none" style="max-width: 50%!important"
                            id="filter-response">
                        </div>
                    </div>
                    <form id="form-filter-invoice" method="post">
                        <div class="d-flex justify-content-between">
                            <div class="form-group mx-2">
                                <label for="supplier">Select Supplier</label>
                                <select required style="z-index: 1099!important" name="select-supplier"
                                    id="select-supplier" class="form-select">
                                    <option value="" selected>Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mx-2">
                                <label for="invoice-date">Invoice Date</label>
                                <input required type="date" value="{{ Date('Y-m-d') }}" class="form-control" name="invoice-date"
                                    id="invoice-date" />
                            </div>
                            <div class="form-group mx-2">
                                <label for="filter">Filter</label>
                                <button type="submit" class="btn btn-primary d-block">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-center d-none">
                    <div class="alert alert-danger text-center" style="max-width: 50%!important" id="filter-response">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-filter-invoice').on('submit', function(e) {
            e.preventDefault();
            console.log(e);
            var s = e.currentTarget[0].value,
                d = e.currentTarget[1].value;
            console.log(s, d);
            $.ajax({
                type: "POST",
                url: "{{ route('supplier.filter.order') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "date": d,
                    "supplier": s
                },
                success: function(res) {
                    if (res.success) {
                        console.log(res);
                        window.open(res.success + "?date=" + res.date, "_self");
                    } else if (res.empty) {
                        console.log(res.empty);
                        alert(res.empty);
                    }
                },
                error: function(res) {
                    console.log(res);
                }
            });
        });
    });
</script>
