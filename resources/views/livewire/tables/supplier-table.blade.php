<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Suppliers') }}
            </h3>
        </div>

        <div class="card-actions">
            <x-action.create route="{{ route('suppliers.create') }}"/>
            <a href="{{ route('export_supplier') }}" class="btn btn-primary">
                Xuất Excel
            </a>
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                entries
            </div>
            <div class="ms-auto text-secondary">
                Search:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                           aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner/>

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
            <tr>
                <th class="align-middle text-center w-1">{{ __('ID.') }}</th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button">
                        {{ __('Name') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('phone')" href="#" role="button">
                        {{ __('Phone') }}
                        @include('inclues._sort-icon', ['field' => 'phone'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('shopname')" href="#" role="button">
                        {{ __('Dorm room') }}
                        @include('inclues._sort-icon', ['field' => 'shopname'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">{{ __('Selling Price') }}</th>
                <!-- Cột tổng giá bán -->
                <th scope="col" class="align-middle text-center">{{ __('Return Price') }}</th>
                <!-- Cột tổng giá trả lại -->
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('payment_status')" href="#" role="button">
                        {{ __('Payment Status') }}
                        @include('inclues._sort-icon', ['field' => 'payment_status'])
                    </a>
                </th>

                <!-- Cột trạng thái thanh toán -->
                <th scope="col" class="align-middle text-center">{{ __('Sold Products') }}</th>
                <!-- Cột số sản phẩm đã bán -->
                <th scope="col" class="align-middle text-center">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($suppliers as $supplier)
                <tr>
                    <td class="align-middle text-center">{{ $loop->index + 1 }}</td>
                    <td class="align-middle text-center">{{ $supplier->name }}</td>
                    <td class="align-middle text-center">{{ $supplier->phone }}</td>
                    <td class="align-middle text-center">{{ $supplier->shopname }}</td>
{{--                    <td class="align-middle text-center">--}}
{{--                        <span class="badge bg-primary text-white text-uppercase">{{ $supplier->type }}</span>--}}
{{--                    </td>--}}
                    <td class="align-middle text-center">{{ number_format($supplier->total_selling_price, 2) }}</td>
                    <!-- Hiển thị tổng giá bán -->
                    <td class="align-middle text-center">{{ number_format($supplier->total_return_price, 2) }}</td>
                    <!-- Hiển thị tổng giá trả lại -->
                    <td class="align-middle text-center">
                        <span class="badge
                            {{ $supplier->payment_status === 'complete' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($supplier->payment_status) }}
                        </span> <!-- Trạng thái thanh toán -->
                    </td>
                    <td class="align-middle text-center">{{ number_format($supplier->total_selling_price, 0) }}</td>
                    <!-- Hiển thị tổng giá bán -->
                    <td class="align-middle text-center">
                        <x-button.show class="btn-icon" route="{{ route('suppliers.show', $supplier->uuid) }}"/>
                        <x-button.edit class="btn-icon" route="{{ route('suppliers.edit', $supplier->uuid) }}"/>
                        <x-button.delete
                            class="btn-icon"
                            route="{{ route('suppliers.destroy', $supplier->uuid) }}"
                            onclick="return confirm('Are you sure to remove supplier {{ $supplier->name }} ?!')"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="9">No results found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $suppliers->firstItem() }}</span> to <span>{{ $suppliers->lastItem() }}</span> of
            <span>{{ $suppliers->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $suppliers->links() }}
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
