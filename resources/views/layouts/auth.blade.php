<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Garasale by thanyk14') }}</title>
        <!-- <script defer data-api="/stats/api/event" data-domain="preview.garasale.com" src="{{ asset('stats/js/script.js') }}"></script> -->
        <meta name="msapplication-TileColor" content="#ff6600"/>
        <meta name="theme-color" content="#ff6600"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="mobile-web-app-capable" content="yes"/>
        <meta name="HandheldFriendly" content="True"/>
        <meta name="MobileOptimized" content="320"/>
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

        <!-- Meta description for search engines -->
        <meta name="description" content="Garasale - A platform to buy and sell items easily and securely. Developed by thanyk14, Garasale provides an intuitive and reliable marketplace experience."/>

        <!-- Canonical URL for SEO -->
        <meta name="canonical" content="https://garasale.com">

        <!-- Twitter Cards Metadata -->
        <meta name="twitter:image:src" content="https://garasales-1027992830683.asia-east2.run.app/iu.jpg">
        <meta name="twitter:site" content="@garasale_official">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Garasale - Buy & Sell with Ease | Developed by thanyk14">
        <meta name="twitter:description" content="Garasale is a secure marketplace platform where you can buy and sell items quickly and safely. Join us today!">

        <!-- Open Graph Metadata for Facebook and other social media -->
        <meta property="og:image" content="https://garasales-1027992830683.asia-east2.run.app/iu.jpg">
        <meta property="og:image:width" content="1280">
        <meta property="og:image:height" content="640">
        <meta property="og:site_name" content="Garasale">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Garasale - Buy & Sell with Ease | Developed by thanyk14">
        <meta property="og:url" content="https://garasales-1027992830683.asia-east2.run.app/">
        <meta property="og:description" content="Garasale, developed by thanyk14, offers a smooth and secure platform for users to buy and sell products. Enjoy a reliable marketplace experience today!">
        <!-- CSS files -->
        <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet"/>
        <style>
            @import url('https://rsms.me/inter/inter.css');
            :root {
                --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            }
            body {
                font-feature-settings: "cv03", "cv04", "cv11";
            }
        </style>
        @stack('page-styles')
    </head>
    <body class="d-flex flex-column">
        <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>

        <div class="page page-center">
            <div class="container container-tight py-4">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
                        <img src="{{ asset('static/logo.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
                    </a>
                </div>

                @include('components.alert')

                @if (session('status'))
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <h3 class="mb-1">Success</h3>
                        <p>{{ session('status') }}</p>

                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
        <script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
        @stack('page-scripts')
    </body>
</html>
