@extends('layouts.client_tabler')

@section('content')
    <div class="page-body p-0 m-0" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; background: #000;">
        @livewire('client.products-swipe')
    </div>

    {{-- Nút quay lại --}}
    <div style="position: fixed; top: 20px; left: 20px; z-index: 1001;">
        <a href="{{ url()->previous() }}" class="btn btn-icon btn-pill btn-dark">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <line x1="5" y1="12" x2="11" y2="18"></line>
                <line x1="5" y1="12" x2="11" y2="6"></line>
            </svg>
        </a>
    </div>

    <style>
        .navbar, .footer, .page-header, .page-pretitle, .page-title {
            display: none !important;
        }

        /* Reset layout */
        .page, .page-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            min-height: 100vh !important;
        }

        body {
            overflow: hidden;
        }

        .container-xl {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>
@endsection

