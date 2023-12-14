<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <title>Invoice</title>
</head>

<body>
    <style>
        .invoice-title h2,
        .invoice-title h3 {
            display: inline-block;
        }

        .table>tbody>tr>.no-line {
            border-top: none;
        }

        .table>thead>tr>.thick-line {
            border-bottom-width: 2px;
        }

        .table>tbody>tr>.thick-line {
            border-top-width: 2px;
        }
    </style>
    </head>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="invoice-title">
                            <!-- insert picture here  -->
                            <h2>Invoice</h2>
                            <h5 class="float-end">
                                Date: Wednesday 17th December, 2023<br>
                                Invoice # 12345
                            </h5>
                        </div>
                        <div class="invoice-title">
                            <h3> Invice # 22121</h3>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 ">
                                <address>
                                    <strong> Business Details:</strong><br>
                                    Isaac Boamah<br>
                                    1234 Main<br>
                                    Apt. 4B<br>
                                    Springfield, ST 54321
                                </address>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <address>
                                    <strong>Worker Details :</strong><br>
                                    Rita <br>
                                    ID: 24552321232<br>
                                    0244455555 <br>
                                    Apt. 4B<br>
                                    Kumasi
                                </address>
                            </div>
                        </div>
                        <!-- <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Payment Method:</strong><br>
                                        Visa ending **** 4242<br>
                                        jsmith@email.com
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <address>
                                        <strong>Order Date:</strong><br>
                                        March 7, 2014<br><br>
                                    </address>
                                </div>
                            </div> -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Order summary</strong></h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <td><strong>Item</strong></td>
                                                <td class="text-center"><strong>Price</strong></td>
                                                <td class="text-center"><strong>Quantity</strong></td>
                                                <td class="text-end"><strong>Totals</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>BS-200</td>
                                                <td class="text-center">$10.99</td>
                                                <td class="text-center">1</td>
                                                <td class="text-end">$10.99</td>
                                            </tr>
                                            <tr>
                                                <td>BS-400</td>
                                                <td class="text-center">$20.00</td>
                                                <td class="text-center">3</td>
                                                <td class="text-end">$60.00</td>
                                            </tr>
                                            <tr>
                                                <td>BS-1000</td>
                                                <td class="text-center">$600.00</td>
                                                <td class="text-center">1</td>
                                                <td class="text-end">$600.00</td>
                                            </tr>



                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><strong> Notes</strong></h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-condensed">
                                                    <tr>
                                                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                                                        Quod,
                                                        molestias, dicta distinctio similique laboriosam quidem illo
                                                        veritatis recusandae dolore laudantium earum reiciendis
                                                        laborum
                                                        magnam, praesentium maiores? Debitis ut soluta ab.
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><strong> Total</strong></h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-condensed">
                                                    <tr>

                                                        <td class="thick-line text-center"><strong>Subtotal</strong>
                                                        </td>
                                                        <td class="thick-line text-end">$670.99</td>
                                                    </tr>
                                                    <tr>

                                                        <td class="no-line text-center"><strong>Shipping</strong>
                                                        </td>
                                                        <td class="no-line text-end">$15</td>
                                                    </tr>
                                                    <tr>

                                                        <td class="no-line text-center"><strong>Total</strong></td>
                                                        <td class="no-line text-end">$685.99</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
