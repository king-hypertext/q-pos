@extends('layout.layout')
@section('content')
    @php
        use Carbon\Carbon;
        // dd($invoices);
    @endphp
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <a href="{{ route('invoice.orders') }}" class="nav-link">orders</a>
            <a href="{{ route('invoice.supliers') }}" class="nav-link active" aria-selected="true">suppliers</a>
        </div>
    </nav>
    <div class="table-responsive">
        <table class="table table-striped" id="supplier_invoice">
            <thead>
                <tr>
                    <th>#invoice Number</th>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ Carbon::parse($invoice->created_at)->format('Y-M-d') }}</td>
                        <td>{{ $invoice->name }}</td>
                        <td>{{ $invoice->amount }}</td>
                        <td>
                            <a href="{{ route('invoice.supliers.get', [$invoice->token]) }}" class="btn btn-primary"
                                target="_blank">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            var table = new DataTable('#supplier_invoice', {
                scrollY: false,
                processing: true,
                pageLength: 100,
                
            });
            document.querySelector('input[aria-controls="supplier_invoice"]').placeholder = "Search table";
            $('input[aria-controls="supplier_invoice"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection