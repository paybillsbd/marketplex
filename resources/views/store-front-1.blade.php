@extends('layouts.app-store-front')
@section('title', 'Store')
@section('title-module-name', 'Store')

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
@include('includes.message.message') 
<div class="row">
    <!--Sidebar-->
    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.2s">

        <div class="widget-wrapper">
            @include('includes.frontend.categories')
        </div>

        <div class="widget-wrapper wow fadeIn" data-wow-delay="0.4s">
            @include('includes.frontend.subscription')
        </div>

    </div>
    <!--/.Sidebar-->

    <!--Main column-->
    <div class="col-lg-8">

        <!--First row-->
        <div class="row wow fadeIn" data-wow-delay="0.4s">
            <div class="col-lg-12">
                @if($cart > 0)
                <p class="text-right"><a href="{{ route('cart.show') }}" class="btn btn-success" role="button">Total Item  <span class="badge">
                    {{$cart}}
                </span></a></p>
                @endif               
                <div class="divider-new">
                    <h2 class="h2-responsive">What's new?</h2>
                </div>        
                @include('includes.frontend.carousel')
        
            </div>
        </div>
        <!--/.First row-->
        <br>
        <hr class="extra-margins">

        <!--Second row-->
        <div class="row">
            @include('includes.frontend.product', [ 'market_products' => $paginated_products ])         
        </div>
        <!--/.Second row-->
        
        <!--test for sales action-->
        <p><a href="{{ route('get.sale') }}" class="btn btn-info" role="button">Sales</a></p>
        <p><a href="{{ route('edit.sale') }}" class="btn btn-info" role="button">Edit Sales</a></p>
        <p><a href="{{ route('get.sale.search') }}" class="btn btn-info" role="button">Search Sales</a></p>
        <p><a href="{{ route('sale.income') }}" class="btn btn-info" role="button">Sales Income</a></p>

    </div>
    <!--/.Main column-->

</div>
@endsection