@extends('layouts.client_tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <x-alert />
            @livewire('client.products', ['products' => $products])
        </div>
    </div>
@endsection
