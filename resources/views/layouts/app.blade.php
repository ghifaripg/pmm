<!DOCTYPE html>
<html>

<head>
    @livewireStyles

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">

    <!-- Canonical SEO -->
    <link rel="canonical" href="https://www.creative-tim.com/product/impact-design-system" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}"
        type="text/css">
    <!-- Page plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/dist/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>


<body>
    @livewireScripts

    @include('partials.nav')

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Argon Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Core -->
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <!-- Optional JS -->
    <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Argon JS -->
    <script src="{{ asset('assets/js/dashboard.js?v=1.2.0') }}"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    @stack('scripts')

</body>

</html>
