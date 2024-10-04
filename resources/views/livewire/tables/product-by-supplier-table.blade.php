<div>
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">
                    Supplier: {{ $supplier->name }}
                </h3>
            </div>

            <div class="card-actions btn-actions">
                <div class="dropdown">
                    <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <!-- Download SVG icon from http://tabler-icons.io/i/dots-vertical -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        </svg>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a href="{{ route('products.create', ['supplier' => $supplier]) }}" class="dropdown-item">
                            <x-icon.plus/>
                            {{ __('Add Product') }}
                        </a>
                    </div>
                </div>

                <x-action.close route="{{ url()->previous() }}"/>
            </div>
        </div>

        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-secondary">
                    <p class=" bg-blue-lt" style="padding-left: 8px">Show</p>
                    <div class=" bg-blue-lt" class="mx-2 d-inline-block" style=" border: solid 1px">
                        <select wire:model.live="perPage" class="form-select form-select-sm"
                                aria-label="result per page">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                        </select>
                    </div>
                </div>
                <div class="ms-auto text-secondary">
                    <p class=" bg-blue-lt" style="padding-left: 8px">Search: </p>
                    <div class=" bg-blue-lt" class="ms-2 d-inline-block" style=" border: solid 1px">
                        <input type="text" wire:model.live="search" class="form-control form-control-sm"
                               aria-label="Search product">
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
                        {{ __('Product Image') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('name')" href="#" role="button">
                            {{ __('Name') }}
                            @include('inclues._sort-icon', ['field' => 'name'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('created_at')" href="#" role="button">
                            {{ __('consignment date') }}
                            @include('inclues._sort-icon', ['field' => 'created_at'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('category_id')" href="#" role="button">
                            {{ __('Category') }}
                            @include('inclues._sort-icon', ['field' => 'category_id'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('quantity')" href="#" role="button">
                            {{ __('Qty') }}
                            @include('inclues._sort-icon', ['field' => 'quantity'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('product_sold')" href="#" role="button">
                            {{ __(' sold') }}
                            @include('inclues._sort-icon', ['field' => 'product_sold'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('selling_price')" href="#" role="button">
                            {{ __('Price') }}
                            {{--                            số lượng đã bán--}}
                            @include('inclues._sort-icon', ['field' => 'quantity'])
                        </a>
                    </th>

                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('buying_price')" href="#" role="button">
                            {{ __('Return price') }}
                            {{-- Owner Information --}}
                            @include('inclues._sort-icon', ['field' => 'buying_price'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('fee')" href="#" role="button">
                            {{ __('Fee') }}
                            {{-- Owner Information --}}
                            @include('inclues._sort-icon', ['field' => 'fee'])
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
                            {{ $product->code }}
                        </td>
                        <td class="align-middle text-center">
                            <img style="width: 90px; height: 90px; object-fit: cover;"
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
                        <td class="align-middle text-center">
                            {{ $product->created_at }}
                        </td>
                        <td class="align-middle text-center">
                            <a href="{{ $product->category ? route('categories.show', $product->category) : '#' }}"
                               class="badge bg-blue-lt">
                                {{ $product->category ? $product->category->name : '--' }}
                            </a>
                        </td>
                        <td class="align-middle text-center">
                            {{ $product->quantity}}
                        </td>
                        <td class="align-middle text-center">
                            {{ $product->product_sold}}
                        </td>
                        <td class="align-middle text-center">
                            {{ $product->selling_price }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $product->buying_price }}
                        </td>
                        <td class="align-middle text-center">

                            <p class="badge bg-green-lt">{{ $product->fee }}</p>
                        </td>

                        <!-- Các cột khác -->


                        <td class="align-middle text-center" style="width: 10%">
                            <x-button.show class="btn-icon" route="{{ route('products.show', $product->uuid) }}"/>

                            <x-button.edit class="btn-icon {{ $product->product_sold > 0 ? 'btn-disabled' : '' }}"
                                           route="{{ route('products.edit', $product->uuid) }}"
                                           onclick="return confirm('Are you sure to update product {{ $product->name }} ?')"/>

                            <x-button.delete class="btn-icon {{ $product->product_sold > 0 ? 'btn-disabled' : '' }}"
                                             route="{{ route('products.destroy', $product->uuid) }}"
                                             onclick="return confirm('Are you sure to delete product {{ $product->name }} ?')"/>
                        </td>
                        <style>
                            .btn-disabled {
                                pointer-events: none; /* Vô hiệu hóa việc click */
                                opacity: 0.5; /* Làm mờ nút */
                            }
                        </style>
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
                Showing <span>{{ $products->firstItem() }}</span> to <span>{{ $products->lastItem() }}</span> of
                <span>{{ $products->total() }}</span> entries
            </p>

            <ul class="pagination m-0 ms-auto">
                {{ $products->links() }}
            </ul>
        </div>
        <div class="mt-3 bg-light p-3 rounded shadow-sm">
            <h4 class="text-primary">Total Selling Price:
                <span class="fw-bold">{{ number_format($totalSellingPrice, 2) }}</span>
            </h4>
            <h4 class="text-success">Total Return Price:
                <span class="fw-bold">{{ number_format($totalBuyingPrice, 2) }}</span>
            </h4>
            <h4 class="text-danger">Profit:
                <span class="fw-bold">{{ number_format($totalSellingPrice - $totalBuyingPrice, 2) }}</span>
            </h4>
        </div>

    </div>
</div>
