@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">

            <form action="{{ route('suppliers.update', $supplier->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Profile Image') }}
                                </h3>

                                <img class="img-account-profile mb-2" src="{{ $supplier->photo ? $supplier->photo : asset('assets/img/demo/user-placeholder.svg') }}" alt="" id="image-preview" />
                                <!-- Profile picture help block -->
                                <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 1 MB</div>
                                <!-- Profile picture input -->
                                <input class="form-control form-control-solid mb-2 @error('photo') is-invalid @enderror" type="file"  id="image" name="photo" accept="image/*" onchange="previewImage();">
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
                                    <x-action.close route="{{ route('suppliers.index') }}" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-md-12">
                                        <x-input name="name" :value="old('name', $supplier->name)" :required="true"/>
                                        <x-input name="shopname" label="Dorm room" :value="old('shopname', $supplier->shopname)" :required="true"/>
                                        <x-input name="phone" label="Phone number" :value="old('phone', $supplier->phone)" :required="true"/>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <label for="type" class="form-label required">
                                            Type of supplier
                                        </label>

                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                            @foreach(\App\Enums\SupplierType::cases() as $supplierType)
                                            <option value="{{ $supplierType->value }}" @selected(old('type', $supplier->type) == $supplierType->value)>
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

                                    <div class="col-sm-6 col-md-6">
                                        <label for="bank_name" class="form-label required">
                                            Bank Name
                                        </label>

                                        <select class="form-select @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name">
                                            <option selected="" disabled="">Chọn ngân hàng:</option>
                                            <option value="Agribank" @if(old('bank_name', $supplier->bank_name) == 'Agribank') selected="selected" @endif>Agribank</option>
                                            <option value="BIDV" @if(old('bank_name', $supplier->bank_name) == 'BIDV') selected="selected" @endif>BIDV</option>
                                            <option value="Vietcombank" @if(old('bank_name', $supplier->bank_name) == 'Vietcombank') selected="selected" @endif>Vietcombank</option>
                                            <option value="VietinBank" @if(old('bank_name', $supplier->bank_name) == 'VietinBank') selected="selected" @endif>VietinBank</option>
                                            <option value="Techcombank" @if(old('bank_name', $supplier->bank_name) == 'Techcombank') selected="selected" @endif>Techcombank</option>
                                            <option value="Sacombank" @if(old('bank_name', $supplier->bank_name) == 'Sacombank') selected="selected" @endif>Sacombank</option>
                                            <option value="Eximbank" @if(old('bank_name', $supplier->bank_name) == 'Eximbank') selected="selected" @endif>Eximbank</option>
                                            <option value="MB Bank" @if(old('bank_name', $supplier->bank_name) == 'MB Bank') selected="selected" @endif>MB Bank</option>
                                            <option value="ACB" @if(old('bank_name', $supplier->bank_name) == 'ACB') selected="selected" @endif>ACB</option>
                                            <option value="VPBank" @if(old('bank_name', $supplier->bank_name) == 'VPBank') selected="selected" @endif>VPBank</option>
                                            <option value="SHB" @if(old('bank_name', $supplier->bank_name) == 'SHB') selected="selected" @endif>SHB</option>
                                            <option value="OceanBank" @if(old('bank_name', $supplier->bank_name) == 'OceanBank') selected="selected" @endif>OceanBank</option>
                                            <option value="HDBank" @if(old('bank_name', $supplier->bank_name) == 'HDBank') selected="selected" @endif>HDBank</option>
                                            <option value="Kiên Long Bank" @if(old('bank_name', $supplier->bank_name) == 'Kiên Long Bank') selected="selected" @endif>Kiên Long Bank</option>
                                            <option value="NAB Bank" @if(old('bank_name', $supplier->bank_name) == 'NAB Bank') selected="selected" @endif>NAB Bank</option>
                                            <option value="TPBank" @if(old('bank_name', $supplier->bank_name) == 'TPBank') selected="selected" @endif>TPBank</option>
                                        </select>

                                        @error('bank_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

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
