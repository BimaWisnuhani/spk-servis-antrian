<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {{-- Meta Viewport harus di dalam head agar mobile-friendly bekerja maksimal --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - SPK Prioritas Antrian</title>

    {{-- Custom fonts for this template --}}
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    {{-- Custom styles for this template --}}
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* CSS tambahan untuk merapikan tampilan jargon di header */
        .topbar-jargon {
            font-size: 0.85rem;
            color: #4e73df;
            font-style: italic;
            border-left: 1px solid #e3e6f0;
            padding-left: 1rem;
            margin-left: 1rem;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Content Wrapper --}}
        <div id="content-wrapper" class="d-flex flex-column">

            {{-- Main Content --}}
            <div id="content">

                {{-- Topbar --}}
                @include('layouts.topbar')

                {{-- Page Content --}}
                <div class="container-fluid">
                    {{-- Judul Halaman dinamis --}}
                    @yield('content')
                </div>

            </div>
            {{-- End of Main Content --}}

        </div>
        {{-- End of Content Wrapper --}}

    </div>
    {{-- End of Page Wrapper --}}

    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')

</body>
</html>
