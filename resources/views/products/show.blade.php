@extends('layouts.tabler')

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
                                    {{ __('Hình ảnh') }}
                                </h3>

                                <div>
                                    <picture>
                                        <source
                                            srcset="{{ $product->product_image ? $product->product_image : 'https://storage.googleapis.com/garasales/products/default.webp' }}"
                                            type="image/webp">
                                        <img id="image-preview"
                                             src="{{ $product->product_image ? $product->product_image : 'https://storage.googleapis.com/garasales/products/default.webp' }}"
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
                                    {{ __('Chi tiết sản phẩm') }}
                                </h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                                    <tbody>
                                    <tr>
                                        <td>Tên</td>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Chủ sở hữu</td>
                                        <td>
                                            <a class="badge bg-green-lt"
                                               href="{{ optional($product->supplier)->uuid ? route('suppliers.show', optional($product->supplier)->uuid) . '/products' : '#' }}">
                                                {{ optional($product->supplier)->name ?? '--' }}
                                            </a>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td><span class="text-secondary">Mã</span></td>
                                        <td>{{ $product->code }}</td>
                                    </tr>
{{--                                    <tr>--}}
{{--                                        <td>Barcode</td>--}}
{{--                                        <td>{!! $barcode !!}</td>--}}
{{--                                    </tr>--}}
                                    <tr>
                                        <td>Danh mục</td>
                                        <td>
                                            <a href="{{ route('categories.show', $product->category) }}"
                                               class="badge bg-blue-lt">
                                                {{ $product->category->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Phí</td>
                                        <td>
                                            <a
                                                class="badge bg-blue-lt">
                                                {{ $product->fee .'%'}}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Số lượng</td>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td>Đã bán</td>
                                        <td>
                                                <span class="badge bg-red-lt">
                                                    {{ $product->product_sold }}
                                                </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Giá nhập</td>
                                        <td>{{ $product->buying_price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Giá bán</td>
                                        <td>{{ $product->selling_price }}</td>
                                    </tr>
                                    {{--                                        <tr>--}}
                                    {{--                                            <td>Tax</td>--}}
                                    {{--                                            <td>--}}
                                    {{--                                                <span class="badge bg-red-lt">--}}
                                    {{--                                                    {{ $product->tax }} %--}}
                                    {{--                                                </span>--}}
                                    {{--                                            </td>--}}
                                    {{--                                        </tr>--}}
                                    {{--                                        <tr>--}}
                                    {{--                                            <td>Tax Type</td>--}}
                                    {{--                                            <td>{{ $product->tax_type->label() }}</td>--}}
                                    {{--                                        </tr>--}}
                                    {{--                                        <tr>--}}
                                    {{--                                            <td>{{ __('Notes') }}</td>--}}
                                    {{--                                            <td>{{ $product->notes }}</td>--}}
                                    {{--                                        </tr>--}}
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
                                <a class="btn btn-warning {{ $product->product_sold > 0 ? 'btn-disabled' : '' }}"
                                   href="{{ $product->product_sold > 0 ? '#' : route('products.edit', $product->uuid) }}"
                                   onclick="{{ $product->product_sold > 0 ? 'return false;' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"/>
                                        <path d="M13.5 6.5l4 4"/>
                                    </svg>
                                    {{ __('Edit') }}
                                </a>

                                <style>
                                    .btn-disabled {
                                        pointer-events: none; /* Vô hiệu hóa việc click */
                                        opacity: 0.5; /* Làm mờ nút */
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
