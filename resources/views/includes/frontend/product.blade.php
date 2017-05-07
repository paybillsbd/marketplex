<!--columnn-->
@foreach($market_products as $market_product)

<div class="col-lg-4">
    <!--Card-->
    <div class="card  wow fadeIn"  data-wow-delay="0.2s" >
     
        <!--Card image-->
        <div class="view overlay hm-white-slight" >
            <img src="{{ $market_product->thumbnail() }}" class="img-responsive card-img" alt="">
            <a class="view_detail" data-product_url="{{ route('user::products.quick.view', [$market_product->product]) }}">
                <div class="mask"></div>
            </a>
        </div>
        <!--/.Card image-->

        <!--Card content-->
        <div class="card-block">
            <!--Title-->

            <h4 class="card-title"> {{ $market_product->title }}</h4>
            <!--Text--> 
            <p class="card-text"> {!! $market_product->manufacturer_name !!} </p>
            <a href="#" style="width:100%;margin:0;" class="btn btn-default btn-fluid">Buy now for <br/><strong>{{ $market_product->product->mrp }} &#2547</strong></a>
        </div>
        <!--/.Card content-->

    </div>
    <br/>
    <!--/.Card-->
</div>
@endforeach

<div class="container text-center">
  {{ $market_products->links('includes.frontend.pagination') }}
</div>

<!--end column -->