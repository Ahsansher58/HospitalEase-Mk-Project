<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>@yield('title', 'Hospital Ease')</title>

    @yield('favicon')

    <!--Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--Bootstrap 5 CSS -->
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!--Style CSS -->
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet" />

    <!-- Slider CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/slick.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/slick-theme.min.css') }}" />

    <!--Calander CSS-->
    <link href="{{ asset('assets/frontend/css/flatpickr.css') }}" rel="stylesheet" />

</head>

<body class="light-style">
    @yield('content')
</body>

</html>
