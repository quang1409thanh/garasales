@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <h2 class="page-title">
                        Thông tin thanh toán cho nhà cung cấp: {{ $supplier->name }}
                    </h2>
                </div>
            </div>

            @include('partials._breadcrumbs', ['model' => $supplier])
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">
                                {{ __('Hình ảnh hóa đơn') }}
                            </h3>
                            <img id="image-preview"
                                 class="img-account-profile mb-2"
                                 src="{{ $supplier->bill_image ? asset($supplier->bill_image) : "https://storage.googleapis.com/garasales/peding%20_payment.webp" }}"
                                 alt="Bill Image"
                                 style="width: 100%; height: auto;"
                            >
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ __('Trạng thái thanh toán') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>Trạng thái thanh toán</td>
                                    <td>
                                        <span class="badge {{ $supplier->payment_status === 'complete' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($supplier->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-end">
                            @if($supplier->payment_status === 'pending')
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                    Xác nhận thanh toán
                                </button>
                            @endif
                            <a class="btn btn-info" href="{{ route('suppliers.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l14 0" />
                                    <path d="M5 12l6 6" />
                                    <path d="M5 12l6 -6" />
                                </svg>
                                {{ __('Quay lại') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận thanh toán -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Xác nhận thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('suppliers.payments.store', $supplier->uuid) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="bill_image" class="form-label">Chọn hình ảnh hóa đơn</label>
                            <input type="file" class="form-control" id="bill_image" name="bill_image" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending">Chưa hoàn thành</option>
                                <option value="complete">Hoàn thành</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Lưu thanh toán</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
