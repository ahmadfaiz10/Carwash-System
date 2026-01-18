@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Products</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4">
            <div class="card mb-3">
                @if($product->Image)
                    <img src="{{ asset('storage/'.$product->Image) }}" class="card-img-top">
                @endif
                <div class="card-body">
                    <h5>{{ $product->ProductName }}</h5>
                    <p>RM {{ number_format($product->Price,2) }}</p>
                    <p>Stock: {{ $product->StockQuantity }}</p>
                    <a href="{{ route('products.buy',$product->ProductID) }}"
                       class="btn btn-primary">
                        Buy Now
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
