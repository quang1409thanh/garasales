@extends('layouts.tabler')

@section('content')

    <div class="page-header d-print-none">
        <div class="container-xl">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <h3 class="mb-1">Oops...</h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Edit Product') }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs', ['model' => $product])
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">

                <form action="{{ route('products.update', $product->uuid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Product Image') }}
                                    </h3>

                                    <img class="img-account-profile mb-2"
                                        src="{{ $product->product_image ? $product->product_image : "https://storage.googleapis.com/garasales/thumbnails/default.png" }}"
                                        alt="" id="image-preview">

                                    <div class="small font-italic text-muted mb-2">
                                        JPG or PNG no larger than 6 MB
                                    </div>

                                    <input type="file" accept="image/*" id="image" name="product_image"
                                        class="form-control @error('product_image') is-invalid @enderror"
                                        onchange="previewImage();">

                                    @error('product_image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">

                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Product Details') }}
                                    </h3>

                                    <div class="row row-cards">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">
                                                    {{ __('Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="name" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Product name" value="{{ old('name', $product->name) }}">

                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="supplier_id">Supplier</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control" disabled>
                                                <option value="">-- Select Supplier --</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">This field is not editable.</small> <!-- Ghi chú cho người dùng -->
                                        </div>


                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">
                                                    Product category
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="category_id" id="category_id"
                                                    class="form-select @error('category_id') is-invalid @enderror">
                                                    <option selected="" disabled="">Select a category:</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            @if (old('category_id', $product->category_id) == $category->id) selected="selected" @endif>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>

                                                @error('category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="unit_id">
                                                    {{ __('Unit') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="unit_id" id="unit_id"
                                                    class="form-select @error('unit_id') is-invalid @enderror">
                                                    <option selected="" disabled="">
                                                        Select a unit:
                                                    </option>

                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            @if (old('unit_id', $product->unit_id) == $unit->id) selected="selected" @endif>
                                                            {{ $unit->name }}</option>
                                                    @endforeach
                                                </select>

                                                @error('unit_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number"
                                                     label="Selling Price"
                                                     name="selling_price"
                                                     id="selling_price"
                                                     placeholder="0"
                                                     value="{{ old('selling_price', $product->selling_price) }}"/>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="fee">
                                                    {{ __('Phí (%)') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="fee" id="fee" class="form-select @error('fee') is-invalid @enderror">
                                                    <option value="0" {{ old('fee', $product->fee) == 0 ? 'selected' : '' }}>0%</option>
                                                    <option value="5" {{ old('fee', $product->fee) == 5 ? 'selected' : '' }}>5%</option>
                                                    <option value="10" {{ old('fee', $product->fee) == 10 ? 'selected' : '' }}>10%</option>
                                                    <option value="15" {{ old('fee', $product->fee) == 15 ? 'selected' : '' }}>15%</option>
                                                    <option value="20" {{ old('fee', $product->fee) == 20 ? 'selected' : '' }}>20%</option>
                                                </select>

                                                @error('fee')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number"
                                                     label="Giá Trả Lại Sản Phẩm"
                                                     name="buying_price"
                                                     id="buying_price"
                                                     placeholder="0"
                                                     value="{{ old('buying_price', $product->buying_price) }}"
                                            readonly
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number"
                                                     label="Quantity"
                                                     name="quantity"
                                                     id="quantity"
                                                     placeholder="0"
                                                     value="{{ old('quantity', $product->quantity) }}" />
                                        </div>

                                        <div class="col-sm-6 col-md-6" style="display: none;">
                                            <x-input type="number"
                                                     label="Quantity Alert"
                                                     name="quantity_alert"
                                                     id="quantity_alert"
                                                     placeholder="0"
                                                     value="{{ old('quantity_alert', $product->quantity_alert) }}" <!-- Lấy giá trị từ cơ sở dữ liệu -->
                                            readonly
                                            />
                                        </div>


                                        <div class="col-md-12">
                                            <div class="mb-3 mb-0">
                                                <label for="notes" class="form-label">
                                                    {{ __('Notes') }}
                                                </label>

                                                <textarea name="notes" id="notes" rows="5" class="form-control @error('notes') is-invalid @enderror"
                                                    placeholder="Product notes">{{ old('notes', $product->notes) }}</textarea>

                                                @error('notes')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>`
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button class="btn btn-primary" type="submit">
                                        {{ __('Update') }}
                                    </button>

                                    <a class="btn btn-danger" href="{{ url()->previous() }}">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log('Script is running');

            function previewImage() {
                const fileInput = document.getElementById('image');
                const file = fileInput.files[0];

                if (!file) {
                    console.log('No file selected');
                    return;
                }

                const fileType = file.type;

                console.log('File type selected:', fileType);

                if (fileType === 'image/webp') {
                    alert('WebP images are not allowed. Please upload a JPG or PNG file.');
                    fileInput.value = '';
                    document.getElementById('image-preview').src = '{{ asset('assets/img/products/default.webp') }}';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            document.getElementById('image').addEventListener('change', previewImage);
        });
        document.addEventListener('DOMContentLoaded', function () {
            const sellingPriceInput = document.getElementById('selling_price');
            const feeSelect = document.getElementById('fee');
            const buyingPriceInput = document.getElementById('buying_price');

            function calculateBuyingPrice() {
                const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
                const fee = parseFloat(feeSelect.value) || 0;
                const remainingPercentage = (100 - fee) / 100;

                const buyingPrice = sellingPrice * remainingPercentage;
                buyingPriceInput.value = buyingPrice.toFixed(2); // Hiển thị 2 chữ số sau dấu thập phân
            }

            // Lắng nghe sự kiện thay đổi giá trị
            sellingPriceInput.addEventListener('input', calculateBuyingPrice);
            feeSelect.addEventListener('change', calculateBuyingPrice);
        });

    </script>

@endpushonce
