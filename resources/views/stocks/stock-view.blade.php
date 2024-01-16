<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Open Stock For {{ now()->format('Y-m-d') }}</title>
</head>
<style>
    @media print {
        .container-sm {
            display: none;
        }

        .container-sm>.table {
            display: block;
        }

    }

    table,
    td,
    tr, th {
        border: 1px solid #222;
        border-collapse: collapse;
    }
</style>

<body>
        <table style="width: 100%">
            <thead>
                <tr>
                    <th colspan="4" class="text-center">Open Stock on {{ $date }}</th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>
