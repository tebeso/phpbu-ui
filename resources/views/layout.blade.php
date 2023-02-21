<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="top-0 fixed-top bg-white shadow" style="height:50px; line-height: 50px">
    <div class="logo shadow-lg px-lg-2"><a href="{{ url('/') }}">PHPBU Restore</a></div>
    <div class="arrow-right"></div>
    <div class="float-start" style="margin-left:40px;">
        @yield('breadcrumb')
    </div>

    @if(Auth::check())
        <div class="float-end" style="margin-right:10px;">
            <button id="scan-backups" class="btn btn-danger" ic-get-from="{{ url('/scan-backups') }}">Scan for Backups
            </button>
            <button class="btn btn-danger"><a href="{{ url('/logout') }}">Logout</a></button>
        </div>
    @endif
</div>

@include('loading')

<div id="content" class="container mt-7">
    @yield('content')
    <div id="contentCard" class="card shadow border-0 p-3">
        @yield('contentCard')
    </div>
</div>

@include('footer-scripts')
@yield('additional-footer-scripts')

</body>
</html>