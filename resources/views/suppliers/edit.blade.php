@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">

                <form action="{{ route('suppliers.update', $supplier->uuid) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ __('Profile Image') }}
                                    </h3>

                                    <img class="img-account-profile mb-2"
                                         src="{{ $supplier->photo ? $supplier->photo : asset('assets/img/demo/user-placeholder.svg') }}"
                                         alt="" id="image-preview"/>
                                    <!-- Profile picture help block -->
                                    <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 10 MB</div>
                                    <!-- Profile picture input -->
                                    <input
                                        class="form-control form-control-solid mb-2 @error('photo') is-invalid @enderror"
                                        type="file" id="image" name="photo" accept="image/*" onchange="previewImage();">
                                    @error('photo')
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
                                            {{ __('Edit Supplier') }}
                                        </h3>
                                    </div>

                                    <div class="card-actions">
                                        <x-action.close route="{{ route('suppliers.index') }}"/>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-md-12">
                                            <x-input name="name" :value="old('name', $supplier->name)"
                                                     :required="true"/>
                                            <x-input name="shopname" label="Dorm room"
                                                     :value="old('shopname', $supplier->shopname)" :required="true"/>
                                            <x-input name="phone" label="Phone number"
                                                     :value="old('phone', $supplier->phone)" :required="true"/>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <label for="type" class="form-label required">
                                                Type of supplier
                                            </label>

                                            <select class="form-select @error('type') is-invalid @enderror" id="type"
                                                    name="type">
                                                @foreach(\App\Enums\SupplierType::cases() as $supplierType)
                                                    <option
                                                        value="{{ $supplierType->value }}" @selected(old('type', $supplier->type) == $supplierType->value)>
                                                        {{ $supplierType->label() }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 col-md-6" style="position: relative;">
                                            <label for="bank_name" class="form-label required">Chọn ngân hàng:</label>

                                            <input
                                                type="text"
                                                id="bank_name"
                                                name="bank_name"
                                                placeholder="Nhập tên ngân hàng"
                                                class="form-control @error('bank_name') is-invalid @enderror"
                                                autocomplete="off"
                                                oninput="handleBankSearch(this.value)"
                                                value="{{ old('bank_name', $supplier->bank_name) }}"
                                            />

                                            <!-- Hiển thị danh sách ngân hàng nếu có kết quả -->
                                            <div id="bank-list-wrapper" style="position: relative;">
                                                <ul id="bank-list" class="list-group mt-2"
                                                    style="position: absolute; z-index: 1000; width: 100%; display: none; background: rgba(255, 255, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 5px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 0; overflow: hidden;">
                                                </ul>
                                            </div>

                                            @error('bank_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- JavaScript -->
                                        <script>
                                            let bankData = [];

                                            function handleBankSearch(value) {
                                                // Kiểm tra nếu dữ liệu chưa tải xong
                                                if (!bankData || bankData.length === 0) {
                                                    console.error('Dữ liệu ngân hàng chưa tải xong hoặc không có dữ liệu.');
                                                    return;
                                                }

                                                const searchValue = value.toLowerCase();
                                                const bankList = document.getElementById('bank-list');

                                                bankList.innerHTML = '';  // Xóa danh sách cũ

                                                if (searchValue === '') {
                                                    bankList.style.display = 'none';
                                                    return;
                                                }

                                                const filteredBanks = bankData.filter(bank =>
                                                    bank.name.toLowerCase().includes(searchValue) || bank.shortName.toLowerCase().includes(searchValue)
                                                );

                                                if (filteredBanks.length > 0) {
                                                    bankList.style.display = 'block'; // Hiển thị danh sách
                                                    filteredBanks.forEach(bank => {
                                                        const li = document.createElement('li');
                                                        li.className = 'list-group-item list-group-item-action';
                                                        li.style.cursor = 'pointer';

                                                        // Hiển thị logo và short name
                                                        li.innerHTML = `<img src="${bank.logo}" alt="${bank.shortName} Logo"
                             style="height: 30px; margin-right: 10px; border-radius: 50%;" />
                             ${bank.shortName}`; // Chỉ hiển thị short name

                                                        // Sự kiện khi nhấp chuột vào một ngân hàng
                                                        li.onclick = function () {
                                                            document.getElementById('bank_name').value = bank.shortName; // Chọn short name để gán vào input
                                                            bankList.style.display = 'none'; // Ẩn danh sách sau khi chọn
                                                        };
                                                        bankList.appendChild(li);
                                                    });
                                                } else {
                                                    bankList.style.display = 'none'; // Ẩn danh sách nếu không tìm thấy kết quả
                                                }
                                            }

                                            // Fetch file JSON
                                            fetch('/banks.json') // Đổi đường dẫn thành đường dẫn file JSON của bạn
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data) {
                                                        bankData = data; // Lưu dữ liệu ngân hàng từ data
                                                    } else {
                                                        console.error('JSON không có dữ liệu mong đợi.');
                                                    }
                                                })
                                                .catch(error => console.error('Error fetching JSON:', error));
                                        </script>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input name="account_holder"
                                                     label="Account holder"
                                                     :value="old('account_holder', $supplier->account_holder)"
                                            />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input name="account_number"
                                                     label="Account number"
                                                     :value="old('account_number', $supplier->account_number)"
                                            />
                                        </div>


                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <x-button type="submit">
                                        {{ __('Save') }}
                                    </x-button>
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
@endpushonce
