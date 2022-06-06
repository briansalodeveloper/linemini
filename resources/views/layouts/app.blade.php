<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ _vers('css/compiled.css') }}" rel="stylesheet">
    @stack('assetCss')
    <link href="{{ _vers('css/app.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed @yield('bodyClass')">
    <main>
        <div class="main-container">
            <div class="wrapper">
                @include('layouts.auth.header')
                @include('layouts.auth.aside')
                <div class="content-wrapper">
                    @include('layouts.auth.content-header')
                    <section class="content px-4">
                        @yield('content')
                    </section>
                </div>
                @include('layouts.auth.footer')
                <div id="spinner-overlay" class="text-center align-items-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">the page is loading...</span>
                    </div>
                </div>
                @include('modals.confirm')
            </div>
        </div>
    </main>
    @stack('modals')
    <script src="{{ _vers('js/compiled.js') }}"></script>
    @stack('assetJs')
    <script src="{{ _vers('js/app.js') }}"></script>
    @stack('js')
    <script>
        @if(session()->has('message'))
            toastr.success('{{ session()->get('message') }}')
        @elseif(session()->has('success'))
            toastr.success('{{ session()->get('success') }}')
        @elseif(session()->has('info'))
            toastr.info('{{ session()->get('info') }}')
        @elseif(session()->has('error'))
            toastr.error('{{ session()->get('error') }}')
        @endif
        @stack('js_script')
    </script>
</body>
</html>
