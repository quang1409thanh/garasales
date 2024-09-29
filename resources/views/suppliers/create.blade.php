@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Create Supplier') }}
                </h2>
            </div>
        </div>

        @include('partials._breadcrumbs')
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">

            <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Profile Image') }}
                                </h3>

                                <img class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/img/demo/user-placeholder.svg') }}" alt="" id="image-preview" />

                                <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 1 MB</div>

                                <input class="form-control @error('photo') is-invalid @enderror" type="file"  id="image" name="photo" accept="image/*" onchange="previewImage();">

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
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Supplier Details') }}
                                </h3>

                                <div class="row row-cards">
                                    <div class="col-md-12">
                                        <x-input name="name" :required="true" />

                                        <x-input name="email" label="Email address" :required="true" />

                                        <x-input name="shopname" label="Shop name" :required="true" />

                                        <x-input name="phone" label="Phone number" :required="true" />
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <label for="type" class="form-label required">
                                            Type of supplier
                                        </label>

                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                            <option selected="" disabled="">Select a type:</option>

                                            @foreach(\App\Enums\SupplierType::cases() as $supplierType)
                                                <option value="{{ $supplierType->value }}" @selected(old('type') == $supplierType->value)>
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
                                            <option value="Agribank" @if(old('bank_name') == 'Agribank') selected="selected" @endif>Agribank</option>
                                            <option value="BIDV" @if(old('bank_name') == 'BIDV') selected="selected" @endif>BIDV</option>
                                            <option value="Vietcombank" @if(old('bank_name') == 'Vietcombank') selected="selected" @endif>Vietcombank</option>
                                            <option value="VietinBank" @if(old('bank_name') == 'VietinBank') selected="selected" @endif>VietinBank</option>
                                            <option value="Techcombank" @if(old('bank_name') == 'Techcombank') selected="selected" @endif>Techcombank</option>
                                            <option value="Sacombank" @if(old('bank_name') == 'Sacombank') selected="selected" @endif>Sacombank</option>
                                            <option value="Eximbank" @if(old('bank_name') == 'Eximbank') selected="selected" @endif>Eximbank</option>
                                            <option value="MB Bank" @if(old('bank_name') == 'MB Bank') selected="selected" @endif>MB Bank</option>
                                            <option value="ACB" @if(old('bank_name') == 'ACB') selected="selected" @endif>ACB</option>
                                            <option value="VPBank" @if(old('bank_name') == 'VPBank') selected="selected" @endif>VPBank</option>
                                            <option value="SHB" @if(old('bank_name') == 'SHB') selected="selected" @endif>SHB</option>
                                            <option value="OceanBank" @if(old('bank_name') == 'OceanBank') selected="selected" @endif>OceanBank</option>
                                            <option value="HDBank" @if(old('bank_name') == 'HDBank') selected="selected" @endif>HDBank</option>
                                            <option value="Kiên Long Bank" @if(old('bank_name') == 'Kiên Long Bank') selected="selected" @endif>Kiên Long Bank</option>
                                            <option value="NAB Bank" @if(old('bank_name') == 'NAB Bank') selected="selected" @endif>NAB Bank</option>
                                            <option value="TPBank" @if(old('bank_name') == 'TPBank') selected="selected" @endif>TPBank</option>
                                        </select>
                                        @error('bank_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input name="account_holder" label="Account holder"/>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input name="account_number" label="Account number"/>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label required">
                                                {{ __('Address') }}
                                            </label>

                                            <textarea id="address"
                                                      name="address"
                                                      rows="3"
                                                      class="form-control @error('address') is-invalid @enderror"
                                            >{{ old('address') }}</textarea>

                                            @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">
                                    {{ __('Save') }}
                                </button>

                                <a class="btn btn-outline-warning" href="{{ route('suppliers.index') }}">
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
@endpushonce
