@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">
                                    {{ __('New Order') }}
                                </h3>
                            </div>
                            <div class="card-actions btn-actions">
                                <x-action.close route="{{ route('orders.index') }}"/>
                            </div>
                        </div>
                        <form action="{{ route('invoice.create') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row gx-3 mb-3">
                                    @include('partials.session')
                                    <div class="col-md-4">
                                        <label for="purchase_date" class="small my-1">
                                            {{ __('Date') }}
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input name="purchase_date" id="purchase_date" type="date"
                                               class="form-control example-date-input @error('purchase_date') is-invalid @enderror"
                                               value="{{ old('purchase_date') ?? now()->format('Y-m-d') }}"
                                               required
                                        >

                                        @error('purchase_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small mb-1" for="customer_id">
                                            {{ __('Customer') }}
                                            <span class="text-danger">*</span>
                                        </label>

                                        @if ($customer)
                                            <!-- Kiểm tra xem có khách hàng không -->
                                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                            <!-- Gán giá trị vào input ẩn -->
                                            <div class="form-control"
                                                 style="background-color: #f5f5f5; color: #6c757d; cursor: not-allowed;"
                                                 disabled>
                                                {{ $customer->name }}
                                            </div>
                                        @else
                                            <div class="form-control"
                                                 style="background-color: #f5f5f5; color: #6c757d; cursor: not-allowed;"
                                                 disabled>
                                                No customer found.
                                            </div>
                                        @endif

                                        @error('customer_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small mb-1" for="reference">
                                            {{ __('Reference') }}
                                        </label>

                                        <input type="text" class="form-control"
                                               id="reference"
                                               name="reference"
                                               value="ORD"
                                               readonly
                                        >

                                        @error('reference')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered align-middle">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('Product') }}</th>
                                            <th scope="col" class="text-center">{{ __('Quantity') }}</th>
                                            <th scope="col" class="text-center">{{ __('Price') }}</th>
                                            <th scope="col" class="text-center">{{ __('SubTotal') }}</th>
                                            <th scope="col" class="text-center">
                                                {{ __('Action') }}
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($carts as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td style="min-width: 170px;">
                                                    <form></form>
                                                    <form action="{{ route('pos.updateCartItem', $item->rowId) }}"
                                                          method="POST">
                                                        @csrf
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="qty"
                                                                   required value="{{ old('qty', $item->qty) }}">
                                                            <input type="hidden" class="form-control" name="product_id"
                                                                   value="{{ $item->id }}">

                                                            <div class="input-group-append text-center">
                                                                <button type="submit"
                                                                        class="btn btn-icon btn-success border-none"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="" data-original-title="Sumbit">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         class="icon icon-tabler icon-tabler-check"
                                                                         width="24" height="24" viewBox="0 0 24 24"
                                                                         stroke-width="2" stroke="currentColor"
                                                                         fill="none" stroke-linecap="round"
                                                                         stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                              fill="none"/>
                                                                        <path d="M5 12l5 5l10 -10"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->price }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->subtotal }}
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('pos.deleteCartItem', $item->rowId) }}"
                                                          method="POST">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-outline-danger "
                                                                onclick="return confirm('Are you sure you want to delete this record?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="icon icon-tabler icon-tabler-trash" width="24"
                                                                 height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                 stroke="currentColor" fill="none"
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M4 7l16 0"/>
                                                                <path d="M10 11l0 6"/>
                                                                <path d="M14 11l0 6"/>
                                                                <path
                                                                    d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="5" class="text-center">
                                                {{ __('Add Products') }}
                                            </td>
                                        @endforelse

                                        <tr>
                                            <td colspan="4" class="text-end">
                                                Total Product
                                            </td>
                                            <td class="text-center">
                                                {{ Cart::count() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal</td>
                                            <td class="text-center">
                                                {{ Cart::subtotal() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Tax</td>
                                            <td class="text-center">
                                                {{ Cart::tax() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Total</td>
                                            <td class="text-center">
                                                {{ Cart::total() }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer text-end">
                                <button type="submit"
                                        class="btn btn-success add-list mx-1 {{ Cart::count() > 0 ? '' : 'disabled' }}">
                                    {{ __('Create Invoice') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-lg-5">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">
                            List Product
                        </div>
                        <div class="card-body">
                            <!-- Form tìm kiếm -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchCode"
                                           placeholder="Search by code">
                                    <button class="btn btn-primary" id="searchButton">Search</button>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered align-middle" id="productTable">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td class="text-center">{{ $product->name }}</td>
                                                <td class="text-center">{{ $product->quantity }}</td>
                                                <td class="text-center">{{ $product->code }}</td>
                                                <td class="text-center">{{ number_format($product->selling_price, 2) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <form action="{{ route('pos.addCartItem', $product) }}"
                                                              method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                                            <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                                                            <input type="hidden" name="name"
                                                                   value="{{ $product->name }}">
                                                            <input type="hidden" name="selling_price"
                                                                   value="{{ $product->selling_price }}">
                                                            <button type="submit"
                                                                    class="btn btn-icon btn-outline-primary">
                                                                <x-icon.cart/>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="5" class="text-center">
                                                    Data not found!
                                                </th>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khi nhấn nút tìm kiếm
            $('#searchButton').click(function() {
                filterProducts();
            });

            // Khi nhấn phím Enter trong ô tìm kiếm
            $('#searchCode').keypress(function(e) {
                if (e.which == 13) { // Kiểm tra phím Enter
                    e.preventDefault(); // Ngăn chặn hành vi mặc định
                    filterProducts();
                }
            });

            function filterProducts() {
                var code = $('#searchCode').val().toLowerCase(); // Lấy giá trị tìm kiếm và chuyển sang chữ thường

                // Duyệt qua từng hàng trong bảng
                $('#productTable tbody tr').filter(function() {
                    // Lấy mã sản phẩm từ ô tương ứng và kiểm tra xem nó có chứa giá trị tìm kiếm không
                    $(this).toggle($(this).find('td:eq(2)').text().toLowerCase().indexOf(code) > -1);
                });
            }
        });
    </script>

@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
