@extends('layout.layout')
<style>
    .card {
        background-color: #fff;
        color: #fff !important;
        border-radius: 10px;
        border: none;
        position: relative;
        margin-bottom: 30px;
        box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
    }

    .l-bg-blue-dark {
        background: linear-gradient(to right, #373b44, #4286f4) !important;
        color: #fff;
    }

    .card .card-statistic-3 .card-icon-large .fas,
    .card .card-statistic-3 .card-icon-large .far,
    .card .card-statistic-3 .card-icon-large .fab,
    .card .card-statistic-3 .card-icon-large .fal {
        font-size: 110px;
    }

    .card .card-statistic-3 .card-icon {
        text-align: center;
        line-height: 50px;
        margin-left: 15px;
        color: #000;
        position: absolute;
        right: -5px;
        top: 20px;
        opacity: 0.1;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }

    .l-bg-green {
        background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
        color: #fff;
    }

    .l-bg-orange {
        background: linear-gradient(to right, #f9900e, #ffba56) !important;
        color: #fff;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }
</style>
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            <li class="breadcrumb-item"></li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Today Orders</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $today_orders }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('orders') }}" class="btn btn-outline-light">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Orders</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $orders }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('orders') }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Suppliers</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $suppliers }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('suppliers') }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Workers</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $customers }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('customers') }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h6 class="h3 fw-semibold">Products Stats</h6>
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Products</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $all_products }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('products') }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Low Stock</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $low_stock }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('products.query', ['low_stock']) }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Out of Stock</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $out_of_stock }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('products.query', ['out_of_stock']) }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class=""></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Expired Products</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{ $expired }}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span><a href="{{ route('products.query', ['expired']) }}" class="btn btn-outline-light">View</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
