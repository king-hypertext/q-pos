@extends('layout.layout')
@section('content')
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <a href="{{ route('invoice.orders') }}" class="nav-link active" aria-selected="true">orders invoices</a>
            <a href="{{ route('invoice.supliers') }}" class="nav-link">suppliers</a>
        </div>
    </nav>
    @php
        use Carbon\Carbon;
        // dd($invoices);
        // dd($orders);
    @endphp
    <div class="table-responsive">
        <table class="table table-striped" id="order_invoice">
            <thead>
                <tr>
                    <th>#invoice Number</th>
                    <th>Date</th>
                    <th>Customer</th>
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
                            <a href="{{ route('invoice.orders.get', [$invoice->token]) }}" class="btn btn-primary"
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
            new DataTable('#order_invoice', {
                scrollY: false,
                processing: true,
                pageLength: 100,
                
            });
            document.querySelector('input[aria-controls="order_invoice"]').placeholder = "Search table";
            $('input[aria-controls="order_invoice"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection
