@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @livewire('tables.product-by-supplier-table', [
            'supplier' => $supplier,
            'totalSellingPrice' => $totalSellingPrice,
            'totalBuyingPrice' => $totalBuyingPrice
            ])
        </div>
    </div>
@endsection
