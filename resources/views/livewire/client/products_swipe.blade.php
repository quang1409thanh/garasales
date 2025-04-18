@extends('layouts.client_tabler')

@section('content')
    <div class="page-body p-0 m-0" style="height: 100vh; overflow: hidden;">
        @livewire('client.products-swipe')
    </div>
@endsection
