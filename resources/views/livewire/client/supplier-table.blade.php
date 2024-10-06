<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Suppliers') }}
            </h3>
        </div>

    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                <p class=" bg-blue-lt" style="padding-left: 8px">Show</p>
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
            </div>
            <div class="ms-auto text-secondary">
                <p class=" bg-blue-lt" style="padding-left: 8px">Search: </p>
                <div class=" bg-blue-lt" class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                           aria-label="Search Supplier">
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
                    <a wire:click.prevent="sortBy('payment_status')" href="#" role="button">
                        {{ __('Payment Status') }}
                        @include('inclues._sort-icon', ['field' => 'payment_status'])
                    </a>
                </th>

                <!-- Cột trạng thái thanh toán -->
                <th scope="col" class="align-middle text-center">{{ __('Sold Products') }}</th>
                <th scope="col" class="align-middle text-center">{{ __('Quantity') }}</th>
                <!-- Cột số sản phẩm đã bán -->
                <th scope="col" class="align-middle text-center">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($suppliers as $supplier)
                <tr>
                    <td class="align-middle text-center">{{ $loop->index + 1 }}</td>
                    <td class="align-middle text-center">{{ $supplier->name }}</td>
                    <td class="align-middle text-center">
                        <span class="badge
                            {{ $supplier->payment_status === 'complete' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($supplier->payment_status) }}
                        </span> <!-- Trạng thái thanh toán -->
                    </td>
                    <td class="align-middle text-center">{{ number_format($supplier->total_product_sold, 0) }}</td>
                    <td class="align-middle text-center">{{ number_format($supplier->total_quantity, 0) }}</td>
                    <!-- Hiển thị tổng giá bán -->
                    <td class="align-middle text-center">
                        <x-button.show class="btn-icon" route="{{ $supplier->uuid ? route('supplier_client.show', $supplier->uuid) . '' : '#' }}"/>
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


</div>
