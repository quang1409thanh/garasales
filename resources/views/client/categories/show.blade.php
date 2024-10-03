@extends('layouts.client_tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @livewire('client.product-by-category-table', ['category' => $category])
        </div>
    </div>
@endsection
