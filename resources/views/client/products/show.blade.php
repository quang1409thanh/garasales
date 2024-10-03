@extends('layouts.client_tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ $product->name }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs')
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Product Image') }}
                                </h3>

                                <div>
                                    <picture>
                                        <source srcset="{{ $product->product_image ? $product->product_image : asset('assets/img/products/default.webp') }}" type="image/webp">
                                        <img id="image-preview"
                                             src="{{ $product->product_image ? $product->product_image : asset('assets/img/products/default.png') }}"
                                             alt="{{ $product->name }}"
                                             class="img-account-profile mb-2"
                                             loading="lazy">
                                    </picture>
                                </div>
                            </div>
                        </div>
                    </div> <!---
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    Product Code
                                </div>
                                <div class="row row-cards">
                                    <div class="col-md-6">
                                        <label class="small mb-1">
                                            Product code
                                        </label>

                                        <div class="form-control">
                                            {{ $product->code }}
                    </div>
                </div>

                <div class="col-md-6 align-middle">
                    <label class="small mb-1">
                        Barcode
                    </label>

                    <div class="mt-1">
{!! $barcode !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
--->

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ __('Product Details') }}
                                </h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $product->name }}</td>
                                    </tr>

                                    <tr>
                                        <td>Owner Information</td>
                                        <td>
                                            <a class="badge bg-green-lt" href="{{ optional($product->supplier)->uuid ? route('supplier_client.show', optional($product->supplier)->uuid) . '/products' : '#' }}">
                                                {{ optional($product->supplier)->name ?? '--' }}
                                            </a>
                                        </td>

                                    </tr>                                    <tr>
                                        <td><span class="text-secondary">Code</span></td>
                                        <td>{{ $product->code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Barcode</td>
                                        <td>{!! $barcode !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Category</td>
                                        <td><a href="{{ route('categories.show', $product->category) }}"
                                               class="badge bg-blue-lt">
                                                {{ $product->category->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit</td>
                                        <td>
                                            <a href="{{ route('units.show', $product->unit) }}"
                                               class="badge bg-blue-lt">
                                                {{ $product->unit->short_code }}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Quantity</td>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>

                                    <tr>
                                        <td>Buying Price</td>
                                        <td>{{ $product->buying_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Selling Price</td>
                                        <td>{{ $product->selling_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td>
                                                <span class="badge bg-red-lt">
                                                    {{ $product->tax }} %
                                                </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Notes') }}</td>
                                        <td>{{ $product->notes }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-end">
                                <a class="btn btn-info" href="{{ url()->previous() }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="icon icon-tabler icon-tabler-arrow-left"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l14 0"/>
                                        <path d="M5 12l6 6"/>
                                        <path d="M5 12l6 -6"/>
                                    </svg>
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
