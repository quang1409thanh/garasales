@php use App\Models\Product; @endphp
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Products') }}
            </h3>
        </div>

    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                <p class=" bg-blue-lt" style="padding-left: 8px">Show</p>
                <div class=" bg-blue-lt" class="mx-2 d-inline-block" style=" border: solid 1px">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                    </select>
                </div>
            </div>
            <div class="ms-auto text-secondary">
                <p class="bg-blue-lt" style="padding-left: 8px">Search: </p>
                <div class="bg-blue-lt d-inline-block" style="border: solid 1px">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search product">
                </div>
            </div>

            <div class="ms-auto text-secondary">
                <p class="bg-blue-lt" style="padding-left: 8px">In Stock</p>
                <div class="bg-blue-lt d-inline-block" style="border: solid 1px">
                    <input type="checkbox" wire:model.live="inStock" class="form-check-input">
                </div>
            </div>

        </div>
    </div>

    <x-spinner.loading-spinner/>

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
            <tr>
                <th class="align-middle text-center w-1">
                    {{ __('No.') }}
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __(' Product Image') }}
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button">
                        {{ __('Name') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
{{--                <th scope="col" class="align-middle text-center">--}}
{{--                    <a wire:click.prevent="sortBy('created_at')" href="#" role="button">--}}
{{--                        {{ __('consignment date') }}--}}
{{--                        @include('inclues._sort-icon', ['field' => 'created_at'])--}}
{{--                    </a>--}}
{{--                </th>--}}
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('category_id')" href="#" role="button">
                        {{ __('Category') }}
                        @include('inclues._sort-icon', ['field' => 'category_id'])
                    </a>
                </th>
{{--                <th scope="col" class="align-middle text-center">--}}
{{--                    <a wire:click.prevent="sortBy('quantity')" href="#" role="button">--}}
{{--                        {{ __('Quantity') }}--}}
{{--                        @include('inclues._sort-icon', ['field' => 'quantity'])--}}
{{--                    </a>--}}
{{--                </th>--}}
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('selling_price')" href="#" role="button">
                        {{ __('Price') }}
                        {{--                            số lượng đã bán--}}
                        @include('inclues._sort-icon', ['field' => 'quantity'])
                    </a>
                </th>

                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('supplier_id')" href="#" role="button">
                        {{ __('Owner Information') }}
                        {{-- Owner Information --}}
                        @include('inclues._sort-icon', ['field' => 'owner'])
                    </a>
                </th>

                <th scope="col" class="align-middle text-center">
                    {{ __('Action') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="align-middle text-center">
                        {{ $product ->code}}
                    </td>
                    <td class="align-middle text-center">
                        <img class="responsive-image"
                             src="{{ $product->thumbnail_url ? $product->thumbnail_url : 'https://storage.googleapis.com/garasales/thumbnails/default.png' }}"
                             alt="{{ $product->name }}" loading="lazy">
                    </td>

                    <style>
                        .responsive-image {
                            width: 90px; /* Đặt chiều rộng */
                            height: 90px; /* Đặt chiều cao */
                            object-fit: cover; /* Giữ nguyên tỷ lệ và cắt bớt hình ảnh nếu cần */
                            border-radius: 5px; /* Thêm góc tròn nếu bạn muốn */
                        }

                        @media (max-width: 768px) {
                            .responsive-image {
                                width: 90px; /* Chiều rộng cụ thể khi ở chế độ mobile */
                                height: 90px; /* Chiều cao cụ thể khi ở chế độ mobile */
                                object-fit: cover; /* Cắt bớt hình ảnh nếu cần */
                            }
                        }
                    </style>
                    <td class="align-middle text-center">
                        {{ $product->name }}
                    </td>
{{--                    <td class="align-middle text-center">--}}
{{--                        {{ $product->created_at }}--}}
{{--                    </td>--}}
                    <td class="align-middle text-center">
                        <a href="{{ $product->category ? route('category_client.products.show', $product->category->slug) : '#' }}"
                           class="badge bg-blue-lt">
                            {{ $product->category ? $product->category->name : '--' }}
                        </a>
                    </td>
{{--                    <td class="align-middle text-center">--}}
{{--                        {{ $product->quantity }}--}}
{{--                    </td>--}}
                    <td class="align-middle text-center">
                        {{ $product->selling_price }}
                    </td>
                    <td>
                        <a class="badge bg-green-lt"
                           href="{{ optional($product->supplier)->uuid ? route('supplier_client.show', optional($product->supplier)->uuid) . '' : '#' }}">
                            {{ optional($product->supplier)->name ?? '--' }}
                        </a>

                    </td>
                    <!-- Các cột khác -->


                    <td class="align-middle text-center" style="width: 10%">
                        <x-button.show class="btn-icon" route="{{ route('product_client.show', $product->uuid) }}"/>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="7">
                        No results found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $products->firstItem() }}</span>
            to <span>{{ $products->lastItem() }}</span> of <span>{{ $products->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $products->links() }}
        </ul>
    </div>
    <style>
        .pagination {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</div>
