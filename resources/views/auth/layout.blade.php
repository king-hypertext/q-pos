<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}" />
    <link rel="stylesheet" href="{{ url('assets/fonts/font-awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/mdb/mdb.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/index.css') }}">
    <script src="{{ url('assets/plugins/jquery-ui-1.13.2/external/jquery/jquery.js') }}"></script>
    <title>Q-POS | {{ $title ?? '' }}</title>
</head>

<body>
    @yield('content')
</body>
<script src="{{ url('assets/plugins/mdb/js/mdb.min.js') }}"></script>

</html>
