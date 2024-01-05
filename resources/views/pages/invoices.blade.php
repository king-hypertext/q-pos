<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>

<body>
    <style type="text/css">
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        td {
            border: 1px solid #233;
            text-align: left;
            padding-left: 5px;
        }

        .row {
            display: flex;
            flex: 0 0 auto;
            /* flex-direction: column; */

        }

        .flex-culomn {
            flex-direction: column;
        }

        .align-end {
            place-content: end;
            place-items: end;
        }

        .invoice-header {
            margin: 20px 5px;
        }

        .flex-between {
            place-content: space-between;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 1;
        }

        .unstyled {
            padding-left: 0;
            list-style: none;
        }

        .page-header {
            text-align: center;
            font-weight: bolder;
            margin-bottom: 14px;
        }

        .bold {
            font-weight: 600;
            font-size: 15px;
            display: inline;
        }

        .page-image {
            display: block;
            margin: 5px auto;
            text-align: center;
        }

        .page-image img {
            display: inline
        }

        .page-image span {
            margin-bottom: 5px !important;
            padding-bottom: 10px;
            display: block;
        }

        .text-capitalized {
            text-transform: uppercase;
        }

        h5,
        h4,
        h3 {
            margin: 0 !important;
        }
    </style>
    <div class="container">
        @php
            use Carbon\Carbon;
        @endphp
        <div class="page-header">
            Q-POS Invoice
        </div>
        <div class="page-image">
            <span> SHELL MEDYAK MR B.</span>
        </div>
        <div class="invoice-header">
            <div class="row align-end">
                <div class="flex-column">Date: {{ Date('Y-M-d') }}</div>
            </div>
            <div class="row align-end">
                <div class="flex-column">Invoice Number : 667890</div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th align="left">
                        <h5>From:</h2>
                    </th>
                    <th align="left">
                        <h5>To:</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <ul class="unstyled text-capitalized">
                            <li>
                                <h1 class="bold">Name:</h1> {{ auth()->user()->name }}
                            </li>
                            <li>
                                <h1 class="bold">Location:</h1>
                            </li>
                            <li>
                                <h1 class="bold">Contact:</h1>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul class="unstyled text-capitalized ">
                            <li>
                                <h1 class="bold"> Name:</h1> {{ $supplier->name }}
                            </li>
                            <li>
                                <h1 class="bold"> Location:</h1> {{ $supplier->address }}
                            </li>
                            <li>
                                <h1 class="bold"> Contact:</h1> {{ $supplier->contact }}
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3>Invoice summary</h3>
        <table class="table ">
            <thead>
                <tr class="tr">
                    <th class="th"><b>#</b></th>
                    <th class="th">Item</th>
                    <th class="th" class="text-center">Price</th>
                    <th class="th" class="text-center">Quantity</th>
                    <th class="th" class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $key => $invoice)
                    <tr class="tr">
                        <td class="td">{{ $key + 1 }}</td>
                        <td class="td">{{ $invoice->product }}</td>
                        <td class="td" class="text-center">{{ $invoice->price }}</td>
                        <td class="td" class="text-center">{{ $invoice->quantity }}</td>
                        <td class="td amount" class="text-end">{{ $invoice->amount }}</td>
                    </tr>
                @endforeach
                <tr style="border: 0 #fff">
                    <td class="td" style="border: 0 #fff" colspan="3"></td>
                    <th class="th">Sum Total</th>
                    <td class="td total">{{ $total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- <script>
        const sumNumbers = (array) => array.reduce((totalNumbers, Number) => totalNumbers + Number, 0);
        var arr = [];
        var total = document.querySelector('.total');
        var td_amount = Array.from(document.querySelectorAll('.amount')).forEach(td => {
            arr.push(Number.parseFloat(td.innerText));
            total.textContent = sumNumbers(arr).toFixed(2);
        })
    </script> --}}
</body>

</html>
