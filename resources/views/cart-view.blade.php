@extends('layouts.app-store-front')
@section('title', 'Cart Items')
@section('title-module-name', 'Cart Items')

@section('header-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>
@endsection

@section('content')
<a href="{{ route('store-front') }}"><button  class="btn btn-info">Shop Again</button></a>
<a href="{{ route('cart.removeall') }}"><button  class="btn btn-danger float-right">Remove All Items</button></a>
<br>
<br>
<div class="wow fadeIn" data-wow-delay="0.2s">
 @include('includes.message.message') 
</div>
<div class="row">
    <!--Main column-->
    <div class="col-lg-12">
        <!--First row-->
        <div class="row wow fadeIn" data-wow-delay="0.4s">
        <!--From Cart Array-->
        @foreach($totalcart as $cartproduct)
        <div class="col-lg-4">
          <div class="card  wow fadeIn"  data-wow-delay="0.2s" >
            <!--Card image-->
            <div class="view overlay hm-white-slight" >
                <img src="{{ $cartproduct->options->image }}" class="img-responsive card-img" alt="" />
                <a class="view_detail" data-product_url="{{ route('user::products.quick.view', [ 'product' => MarketPlex\Product::find($cartproduct->id), 'api_token' => \MarketPlex\Helpers\ImageManager::PUBLIC_TOKEN ]) }}">
                    <div class="mask"></div>
                </a>
            </div>
            <!--/.Card image-->
            <!--Card content-->
            <div class="card-block">
                <div class="caption text-center">
                <h4 class="card-title">{{ $cartproduct->name }}</h4>
                <!--Text--> 
                <p class="card-text">Cart Quantity: {{ $cartproduct->qty }}</p>
                <p>Available Quantity: {{ $cartproduct->options->available_quantity }}</p>
                <p>Price: <span>{{ MarketPlex\Store::currencyIcon() }}</span>{{ $cartproduct->price }}</p>
                <a href="{{ route('cart.remove', ['id' => $cartproduct->rowId] ) }}" ><button  class="btn btn-warning btn-sm fa fa-minus fa-2x"></button></a>
                <a href="{{ route('cart.addqt', ['id' => $cartproduct->rowId] ) }}"><button  class="btn btn-success btn-sm fa fa-plus fa-2x"></button></a>
                <br>
                <br>
                <a href="{{ route('cart.removethis', ['id' => $cartproduct->rowId]) }}"><button  class="btn btn-danger">Remove Item</button></a>
                </div>
            </div>
            <!--/.Card content-->
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