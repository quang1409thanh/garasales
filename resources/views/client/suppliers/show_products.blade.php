@extends('layouts.client_tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @livewire('client.product-by-supplier-table', ['supplier' => $supplier])
        </div>
    </div>
@endsection
