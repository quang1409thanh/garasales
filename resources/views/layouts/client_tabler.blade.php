<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

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

        .form-control:focus {
            box-shadow: none;
        }
    </style>

    {{-- - Page Styles - --}}
    @stack('page-styles')
    @livewireStyles
</head>

<body>
<script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>

<div class="page">
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('static/logo.svg') }}" width="110" height="32" alt="Tabler"
                         class="navbar-brand-image">
                </a>
            </h1>
            <div class="navbar-nav flex-row order-md-last">
                <div class="d-none d-md-flex">

                    <div class="nav-item dropdown d-none d-md-flex me-3">
                        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                           aria-label="Show notifications">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path
                                    d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
                                <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
                            </svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">

                            <span class="dropdown-header">Dropdown header</span>
                            <a class="dropdown-item" href="#">
                                Action
                            </a>
                            <a class="dropdown-item" href="#">
                                Another action
                            </a>
                        </div>
                    </div>

                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                       aria-label="Open user menu">
                            <span class="avatar avatar-sm shadow-none"
                                  style="background-image: url({{ asset('avata.png') }})">
                            </span>

                        <div class="d-none d-xl-block ps-2">
                            <div>{{ 'Guest' }}</div>
                        </div>
                    </a>
                    <div class="dropdown-menu">
                        <a href="/login" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="icon dropdown-item-icon icon-tabler icon-tabler-login" width="24" height="24"
                                 viewBox="5 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 12h12"></path>
                                <path d="M16 8l4 4l-4 4"></path>
                                <path d="M13 12h-10"></path>
                                <path d="M9 16v4h12v-16h-12v4"></path>
                            </svg>
                            Login
                        </a>
                    </div>
                </div>


            </div>
        </div>
    </header>

    <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar">
                <div class="container-xl">
                    <ul class="navbar-nav">

                        <li class="nav-item {{ request()->is('client/products*') ? 'active' : null }}">
                            <a class="nav-link" href="{{ route('product_client.index') }}">
                                        <span
                                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="icon icon-tabler icon-tabler-packages" width="24"
                                                 height="24" viewBox="0 0 24 24" stroke-width="2"
                                                 stroke="currentColor" fill="none" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                <path d="M2 13.5v5.5l5 3"/>
                                                <path d="M7 16.545l5 -3.03"/>
                                                <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"/>
                                                <path d="M12 19l5 3"/>
                                                <path d="M17 16.5l5 -3"/>
                                                <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"/>
                                                <path d="M7 5.03v5.455"/>
                                                <path d="M12 8l5 -3"/>
                                            </svg>
                                        </span>
                                <span class="nav-link-title">
                                            {{ __('Products') }}
                                        </span>
                            </a>
                        </li>


                        <li class="nav-item {{ request()->is('client/suppliers*') ? 'active' : null }}">
                            <a class="nav-link" href="{{ route('supplier_client.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="icon icon-tabler icon-tabler-layers-subtract" width="24"
                                         height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M8 4m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/>
                                        <path d="M16 16v2a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-8a2 2 0 0 1 2 -2h2"/>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Suppliers') }}
                                </span>

                            </a>
                        </li>


                        <li class="nav-item {{ request()->is('client/category*') ? 'active' : null }}">
                            <a class="nav-link" href="{{ route('category_client.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="icon icon-tabler icon-tabler-settings" width="24"
                                         height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/>
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
            {{ __('Categories') }}
        </span>
                            </a>
                        </li>

                    </ul>

                    <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                        <form action="./" method="get" autocomplete="off" novalidate>
                            <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                 height="24" viewBox="0 0 24 24" stroke-width="2"
                                                 stroke="currentColor" fill="none" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/>
                                                <path d="M21 21l-6 -6"/>
                                            </svg>
                                        </span>
                                <input type="text" name="search" id="search" value=""
                                       class="form-control" placeholder="Search…" aria-label="Search in website">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="page-wrapper">
        <div>
            @yield('content')
        </div>

        <footer class="footer footer-transparent d-print-none custom-footer">
            <div class="container-xl">
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                            Copyright &copy; {{ now()->year }}
                            <a href="." class="link-secondary">thanhyk14</a>.
                            All rights reserved.
                        </li>
                        <li class="list-inline-item">
                            <a href="mailto:thanhaxt342@gmail.com" class="link-secondary">thanhaxt342@gmail.com</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.instagram.com/thanyk14/" class="link-secondary" target="_blank">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>

    </div>
</div>

<!-- Libs JS -->
@stack('page-libraries')
<!-- Tabler Core -->
<script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
<script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
{{-- - Page Scripts - --}}
@stack('page-scripts')

@livewireScripts
</body>

</html>
