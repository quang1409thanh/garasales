<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Customers') }}
            </h3>
        </div>

        <div class="card-actions">
            <x-action.create route="{{ route('customers.create') }}" />
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                <p class=" bg-blue-lt" style="padding-left: 8px">Show</p>
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                    </select>
                </div>
            </div>
            <div class="ms-auto text-secondary">
                <p class=" bg-blue-lt" style="padding-left: 8px">Search: </p>
                <div class=" bg-blue-lt" class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                           aria-label="Search Customer">
                </div>
            </div>
        </div>

    </div>

    <x-spinner.loading-spinner/>

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
            <tr>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('id')" href="#" role="button">
                        {{ __('Id') }}
                        @include('inclues._sort-icon', ['field' => 'id'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button">
                        {{ __('Name') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <!-- Tổng -->
                <th scope="col" class="align-middle text-center">
                    <a href="#" role="button">
                        {{ __('Tổng') }}
                        @include('inclues._sort-icon', ['field' => 'totalAmount'])
                    </a>
                </th>

                <!-- Tiền mặt -->
                <th scope="col" class="align-middle text-center">
                    <a href="#" role="button">
                        {{ __('Tiền mặt') }}
                        @include('inclues._sort-icon', ['field' => 'cashAmount'])
                    </a>
                </th>

                <!-- Chuyển khoản -->
                <th scope="col" class="align-middle text-center">
                    <a  href="#" role="button">
                        {{ __('Chuyển khoản') }}
                        @include('inclues._sort-icon', ['field' => 'bankAmount'])
                    </a>
                </th>

                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('created_at')" href="#" role="button">
                        {{ __('Created at') }}
                        @include('inclues._sort-icon', ['field' => 'Created_at'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Action') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td class="align-middle text-center">
                        {{ $loop->index }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->name }}
                    </td>
                    <td>💰: {{ number_format($customer->totalAmount) }} đ</td>
                    <td>💵:  {{ number_format($customer->cashAmount) }} đ</td>
                    <td>🏦: {{ number_format($customer->bankAmount) }} đ</td>

                    <td class="align-middle text-center">
                        {{ $customer->created_at->diffForHumans() }}
                    </td>
                    <td class="align-middle text-center">
                        <x-button.show class="btn-icon" route="{{ route('customers.show', $customer->uuid) }}"/>
                        <x-button.edit class="btn-icon" route="{{ route('customers.edit', $customer->uuid) }}"/>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="8">
                        No results found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex flex-column align-items-start">
        <p class="m-0 text-secondary">
            Showing <span>{{ $customers->firstItem() }}</span> to <span>{{ $customers->lastItem() }}</span> of <span>{{ $customers->total() }}</span> entries
        </p>

        <div class="d-flex justify-content-between w-100">
            <div>
                <strong>Tổng tiền:</strong> 💰 {{ number_format($totalAmount) }} đ
            </div>
            <div>
                <strong>Tiền mặt:</strong> 💵 {{ number_format($cashAmount) }} đ
            </div>
            <div>
                <strong>Chuyển khoản:</strong> 🏦 {{ number_format($bankAmount) }} đ
            </div>
        </div>

        <ul class="pagination m-0 ms-auto">
            {{ $customers->links() }}
        </ul>
    </div>

</div>
