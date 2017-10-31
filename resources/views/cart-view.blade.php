@extends('layouts.app-store-front')
@section('title', 'Cart Item')
@section('title-module-name', 'Store')

@section('header-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
        <!--From Product Model-->
        @foreach($products as $product)
        @if($product->id == $cartproduct->id)
        <!-- Modal -->
        <div class="modal fade" id="ModalLong{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLong" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Product Details - Quick View</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-3  padT10">
                      <img class="img-responsive imgborder" data-toggle="magnify" src="{{ $product->banner() }}" />
                  </div>
                  <div class="col-md-9" style="height:500px;overflow-y:auto">
                    <h3 class="padmar0 headtext1">{{ $product->title }}</h3>
                    <p>Category: {{ $product->categoryName() }}</p>
                    <h4>{!! MarketPlex\Store::currencyIcon() !!}{{ $product->mrp }}</h4>
                    <p class="sku">{{ $product->discount }}% discount!</p>
                      
                      @if(false)
                        @include('includes.approval-label', [ 'status' => $product->status, 'labelText' => $product->getStatus() ])
                      @endif
                      
                    <h5><strong>Product Info</strong></h5>
                    <p class="slidePara1">{!! $product->description or '<i>No description is found to this product</i>' !!}</p>
                    @include('includes.product-spec-viewer')
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal -->
        <div class="col-lg-4">
            <div class="thumbnail cartimg">
                <a href="#" data-toggle="modal" data-target="#ModalLong{{ $cartproduct->id }}">
                <img src="{{ $cartproduct->options->image }}" alt="..." class="img-reponsive">
                </a>
              <div class="caption text-center">
                <h3>{{ $cartproduct->name }}</h3>
                <h5>Cart Quantity: {{ $cartproduct->qty }}</h5>
                <p>Available Quantity: {{ $cartproduct->options->available_quantity }}</p>
                <p>Price: <span>{{ MarketPlex\Store::currencyIcon() }}</span>{{ $cartproduct->price }}</p>
                <a href="{{ route('cart.remove', ['id' => $cartproduct->rowId] ) }}" ><button  class="btn btn-warning btn-sm fa fa-minus fa-2x"></button></a>
                <a href="{{ route('cart.addqt', ['id' => $cartproduct->rowId] ) }}"><button  class="btn btn-success btn-sm fa fa-plus fa-2x"></button></a>
                <br>
                <br>
                <a href="{{ route('cart.removethis', ['id' => $cartproduct->rowId]) }}"><button  class="btn btn-danger">Remove Item</button></a>
              </div>
            </div>
        </div>
        @endif
        @endforeach
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