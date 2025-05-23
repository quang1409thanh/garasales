@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <script>
            console.log("supplier_id" + @json($supplier_id));
        </script>

        <div class="container-xl">
            <x-alert/>

            <div class="row row-cards">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Product Image') }}
                                    </h3>

                                    <img class="img-account-profile mb-2"
                                         src="{{ asset('assets/img/products/default.webp') }}" alt=""
                                         id="image-preview"/>

                                    <div class="small font-italic text-muted mb-2">
                                        JPG or PNG no larger than 10 MB
                                    </div>

                                    <input
                                        type="file"
                                        accept="image/*"
                                        id="image"
                                        name="product_image"
                                        class="form-control @error('product_image') is-invalid @enderror"
                                        onchange="previewImage();"
                                    >

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
                                <div class="card-header">
                                    <div>
                                        <h3 class="card-title">
                                            {{ __('Product Create') }}
                                        </h3>
                                    </div>

                                    <div class="card-actions">
                                        <a href="{{ route('products.index') }}" class="btn-action">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-md-12">

                                            <x-input name="name"
                                                     id="name"
                                                     placeholder="Product name"
                                                     value="{{ old('name') }}"
                                            />
                                        </div>
                                        <div class="col-md-12">
                                            <label for="supplier_id">Select Supplier</label>
                                            <select name="supplier_id" id="supplier_id"
                                                    class="form-control" {{ $supplier_id ? 'disabled' : '' }}>
                                                <option value="">-- Select Supplier --</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ (old('supplier_id', $supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if($supplier_id)
                                                <!-- Chỉ hiển thị trường ẩn nếu đã có supplier_id -->
                                                <input type="hidden" name="supplier_id" value="{{ $supplier_id }}">
                                            @endif
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">
                                                    Product category
                                                    <span class="text-danger">*</span>
                                                </label>

                                                @if ($categories->count() === 1)
                                                    <select name="category_id" id="category_id"
                                                            class="form-select @error('category_id') is-invalid @enderror"
                                                            readonly
                                                    >
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" selected>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="category_id" id="category_id"
                                                            class="form-select @error('category_id') is-invalid @enderror"
                                                    >
                                                        <option selected="" disabled="">
                                                            Select a category:
                                                        </option>

                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                    @if(old('category_id') == $category->id) selected="selected" @endif>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif

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

                                                @if ($units->count() === 1)
                                                    <select name="category_id" id="category_id"
                                                            class="form-select @error('category_id') is-invalid @enderror"
                                                            readonly
                                                    >
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}" selected>
                                                                {{ $unit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="unit_id" id="unit_id"
                                                            class="form-select @error('unit_id') is-invalid @enderror">
                                                        <option selected="" disabled="">
                                                            Select a unit:
                                                        </option>

                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}"
                                                                    @if(old('unit_id') == $unit->id || (old('unit_id') === null && $unit->name == 'Piece')) selected @endif>
                                                                {{ $unit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif

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
                                                     value="{{ old('selling_price') }}"
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="fee">
                                                    {{ __('Phí (%)') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="fee" id="fee"
                                                        class="form-select @error('fee') is-invalid @enderror">
                                                    <option value="0" selected>0%</option>
                                                    <option value="5">5%</option>
                                                    <option value="10">10%</option>
                                                    <option value="15">15%</option>
                                                    <option value="20">20%</option>
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
                                                     value="0"
                                                     readonly
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number"
                                                     label="Quantity"
                                                     name="quantity"
                                                     value=1
                                                     id="quantity"
                                                     :value="old('quantity', 1)"
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6" style="display: none;">
                                            <x-input type="number"
                                                     label="Quantity Alert"
                                                     name="quantity_alert"
                                                     id="quantity_alert"
                                                     placeholder="0"
                                                     value="0"
                                                     readonly
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            {{--                                        <x-input type="number"--}}
                                            {{--                                                 label="Tax"--}}
                                            {{--                                                 name="tax"--}}
                                            {{--                                                 id="tax"--}}
                                            {{--                                                 placeholder="0"--}}
                                            {{--                                                 value="{{ old('tax') }}"--}}
                                            {{--                                        />--}}
                                            {{--                                    </div>--}}

                                            {{--                                    <div class="col-sm-6 col-md-6">--}}
                                            {{--                                        <div class="mb-3">--}}
                                            {{--                                            <label class="form-label" for="tax_type">--}}
                                            {{--                                                {{ __('Tax Type') }}--}}
                                            {{--                                            </label>--}}

                                            {{--                                            <select name="tax_type" id="tax_type"--}}
                                            {{--                                                    class="form-select @error('tax_type') is-invalid @enderror"--}}
                                            {{--                                            >--}}
                                            {{--                                                @foreach(\App\Enums\TaxType::cases() as $taxType)--}}
                                            {{--                                                <option value="{{ $taxType->value }}" @selected(old('tax_type') == $taxType->value)>--}}
                                            {{--                                                    {{ $taxType->label() }}--}}
                                            {{--                                                </option>--}}
                                            {{--                                                @endforeach--}}
                                            {{--                                            </select>--}}

                                            {{--                                            @error('tax_type')--}}
                                            {{--                                            <div class="invalid-feedback">--}}
                                            {{--                                                {{ $message }}--}}
                                            {{--                                            </div>--}}
                                            {{--                                            @enderror--}}
                                            {{--                                        </div>--}}
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">
                                                    {{ __('Notes') }}
                                                </label>

                                                <textarea name="notes"
                                                          id="notes"
                                                          rows="5"
                                                          class="form-control @error('notes') is-invalid @enderror"
                                                          placeholder="Product notes"
                                                ></textarea>

                                                @error('notes')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <x-button.save type="submit" id="save-button" class="button-loading">
                                        {{ __('Save') }}
                                    </x-button.save>

                                    <a class="btn btn-warning" href="{{ url()->previous() }}">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>

                                <script>
                                    document.querySelector('form').addEventListener('submit', function(event) {
                                        // Vô hiệu hóa nút khi form được submit
                                        document.getElementById('save-button').disabled = true;
                                    });
                                </script>

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
        document.addEventListener("DOMContentLoaded", function () {
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
                reader.onload = function (e) {
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
            const categorySelect = document.getElementById('category_id');

            // Định nghĩa bảng ánh xạ giữa category và fee
            // VD: 1 => 10% (cho sách vở), 2 => 20% (cho quần áo)
            const feeMap = {
                '1': 20,
                '2': 10,
                '3': 10,
                '4': 10,
                '5': 10,
                '6': 10,
                // Thêm các mapping nếu cần
            };

            // Hàm tính giá mua dựa vào giá bán và phí
            function calculateBuyingPrice() {
                const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
                const fee = parseFloat(feeSelect.value) || 0;
                const remainingPercentage = (100 - fee) / 100;

                const buyingPrice = sellingPrice * remainingPercentage;
                buyingPriceInput.value = buyingPrice.toFixed(2); // Hiển thị 2 chữ số sau dấu thập phân
            }

            // Hàm cập nhật giá trị phí dựa vào category đã chọn
            function updateFeeByCategory() {
                const selectedCategory = categorySelect.value;
                // Lấy fee tương ứng từ feeMap, nếu không có, giữ nguyên giá trị hiện tại hoặc mặc định (0%)
                feeSelect.value = feeMap[selectedCategory] !== undefined ? feeMap[selectedCategory] : feeSelect.value;
                // Sau khi cập nhật fee, tính lại giá mua
                calculateBuyingPrice();
            }

            // Sự kiện thay đổi giá bán
            sellingPriceInput.addEventListener('input', calculateBuyingPrice);
            // Sự kiện thay đổi fee
            feeSelect.addEventListener('change', calculateBuyingPrice);
            // Sự kiện thay đổi category
            categorySelect.addEventListener('change', updateFeeByCategory);

            // Nếu muốn tự động cập nhật fee khi trang vừa load (trường hợp đã có category được chọn trước đó)
            if(categorySelect.value) {
                updateFeeByCategory();
            }
        });

    </script>

@endpushonce
