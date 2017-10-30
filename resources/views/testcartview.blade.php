@extends('layouts.app-store-front')
@section('title', 'Cart Item')
@section('title-module-name', 'Store')

@section('header-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" />-->
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
@endsection

@section('content')
<a href="{{ route('store-front') }}"><button  class="btn btn-info">Shop Again</button></a>
<a href="{{ route('cart.removeall') }}"><button  class="btn btn-danger float-right">Remove All Items</button></a>
<br>
<br>
<div class="row">
    <!--Main column-->
    <div class="col-lg-12">
        <!--First row-->
        <div class="row wow fadeIn" data-wow-delay="0.4s">
            @foreach($totalcart as $product)
            <div class="col-lg-4">
                <div class="thumbnail cartimg">
                  <img src="{{ $product->options->image }}" alt="..." class="img-reponsive">
                  <div class="caption text-center">
                    <h3>{{ $product->name }}</h3>
                    <h5>Cart Quantity: {{ $product->qty }}</h5>
                    <p>Available Quantity: {{ $product->options->available_quantity }}</p>
                    <p>Price: <span>{{ MarketPlex\Store::currencyIcon() }}</span>{{ $product->price }}</p>
                    <a href="{{ route('cart.remove', ['id' => $product->rowId] ) }}" ><button  class="btn btn-warning btn-sm fa fa-minus fa-2x"></button></a>
                    <a href="{{ route('cart.addqt', ['id' => $product->rowId] ) }}"><button  class="btn btn-success btn-sm fa fa-plus fa-2x"></button></a>
                    <br>
                    <br>
                    <a href="{{ route('cart.removethis', ['id' => $product->rowId]) }}"><button  class="btn btn-danger">Remove Item</button></a>
                  </div>
                </div>
            </div>
           @endforeach
           @if($totalprice > 0)
            <div class="text-center col-md-12">
              <h3 class="alert alert-info">Total Price: <span>{{ MarketPlex\Store::currencyIcon() }}</span>{{$totalprice}}</h3>
              <a href="{{route('cart.checkout')}}"><button  class="btn btn-primary">Checkout</button></a>
            </div>
           @endif
        </div>
    </div>
    <!--/.Main column-->
</div>
@endsection