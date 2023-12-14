@extends('layout.layout')
@section('content')
    <nav>
        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
            <a href="{{ route('invoice.orders') }}" class="nav-link">orders</a>
            <a href="{{ route('invoice.supliers') }}" class="nav-link active" aria-selected="true">suppliers</a>
        </div>
    </nav>
    <div class="table-responsive">
        <table class="table table-striped">
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
                <tr>
                    <td>1</td>
                    <td>{{ Date('Y-M-d') }}</td>
                    <td>Allied</td>
                    <td>15,005</td>
                    <td>
                        <a href="#" class="btn btn-primary" target="_blank">View</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
