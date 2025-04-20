@php use App\Models\Product; @endphp
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Products') }}
            </h3>
        </div>

        <div class="card-actions btn-group">
            <div class="dropdown">
                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    <x-icon.vertical-dots/>
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="">
                    <a href="{{ route('products.create') }}" class="dropdown-item">
                        <x-icon.plus/>
                        {{ __('Create Product') }}
                    </a>
                    <a href="{{ route('products.import.view') }}" class="dropdown-item">
                        <x-icon.plus/>
                        {{ __('Import Products') }}
                    </a>
                    <a href="{{ route('products.export.store') }}" class="dropdown-item">
                        <x-icon.plus/>
                        {{ __('Export Products') }}
                    </a>
                </div>
            </div>
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
                    <a wire:click.prevent="sortBy('code')" href="#" role="button">
                        {{ __('No.') }}
                        @include('inclues._sort-icon', ['field' => 'code'])
                    </a>

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
                    <a wire:click.prevent="sortBy('selling_price')" href="#" role="button">
                        {{ __('Price') }}
                        {{--                            số lượng đã bán--}}
                        @include('inclues._sort-icon', ['field' => 'quantity'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a href="#" role="button">
                        {{ __('Return Price') }}
                        {{--                            số lượng đã bán--}}
                        @include('inclues._sort-icon', ['field' => 'quantity'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a href="#" role="button">
                        {{ __('Profit') }}
                        {{--                            số lượng đã bán--}}
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a href="#" role="button">
                        {{ __('Order ID') }}
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
                        {{ $product->code }}
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
                        {{ $product->selling_price }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $product->buying_price }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $product->selling_price - $product->buying_price }}
                    </td>
                    <td>
                        @foreach($product->orders as $order)
                            <a href="{{ route('orders.show', $order->uuid) }}">
                                #{{ $order->invoice_no }}
                            </a>
                            @if (!$loop->last), @endif
                        @endforeach
                    </td>

                    <td>
                        <a class="badge bg-green-lt"
                           href="{{ optional($product->supplier)->uuid ? route('suppliers.show', optional($product->supplier)->uuid) . '' : '#' }}">
                            {{ optional($product->supplier)->name ?? '--' }}
                        </a>

                    </td>
                    <!-- Các cột khác -->


                    <td class="align-middle text-center" style="width: 10%">
                        @if($product->is_show_visible)
                            <x-button.per_show class="btn-icon"
                                               route="{{ route('products.toggleVisibility', $product->uuid) }}"/>
                        @else
                            <x-button.per_hide class="btn-icon"
                                               route="{{ route('products.toggleVisibility', $product->uuid) }}"/>
                        @endif
                        @if($product->is_show_visible)
                            <x-button.show class="btn-icon" route="{{ route('products.show', $product->uuid) }}"/>
                        @else
                        @endif
                        <x-button.edit class="btn-icon {{ $product->product_sold > 0 ? 'btn-disabled' : '' }}"
                                       route="{{ route('products.edit', $product->uuid) }}"
                                       onclick="return confirm('Are you sure to update product {{ $product->name }} ?')"/>

                        <x-button.delete class="btn-icon {{ $product->product_sold > 0 ? 'btn-disabled' : '' }}"
                                         route="{{ route('products.destroy', $product->uuid) }}"
                                         onclick="return confirm('Are you sure to delete product {{ $product->name }} ?')"/>
                    </td>


                    <script>
                        function toggleVisibility(uuid, button) {
                            fetch(`/products/toggle-visibility/${uuid}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        // Thay đổi giao diện dựa trên trạng thái mới
                                        const iconElement = button.querySelector('.btn-icon');

                                        // Thay đổi icon dựa trên trạng thái mới
                                        if (data.is_show_visible) {
                                            iconElement.innerHTML = '<x-button.per_show class="btn-icon" route="{{ route('products.toggleVisibility', $product->uuid) }}"/>';
                                        } else {
                                            iconElement.innerHTML = '<x-button.per_hide class="btn-icon" route="{{ route('products.toggleVisibility', $product->uuid) }}"/>';
                                        }
                                    } else {
                                        alert('Có lỗi xảy ra!');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    </script>

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
