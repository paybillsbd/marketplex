@extends('layouts.app-store-front')
@section('title', 'Cart Checkout')
@section('title-module-name', 'Store')

@section('header-styles')
    <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" />-->
    <style type="text/css">
        
/*Cards images*/
.credit_cards ol, .credit_cards ul {
  list-style: none;
}
.hand {
  cursor: pointer;
  cursor: pointer;
}
.cards{
    padding-left:0;
}
.cards li {
  -webkit-transition: all .2s;
  -moz-transition: all .2s;
  -ms-transition: all .2s;
  -o-transition: all .2s;
  transition: all .2s;
  background-image: url('//c2.staticflickr.com/4/3713/20116660060_f1e51a5248_m.jpg');
  background-position: 0 0;
  float: left;
  height: 32px;
  margin-right: 8px;
  text-indent: -9999px;
  width: 51px;
}
.cards .mastercard {
  background-position: -51px 0;
}
.cards li {
  -webkit-transition: all .2s;
  -moz-transition: all .2s;
  -ms-transition: all .2s;
  -o-transition: all .2s;
  transition: all .2s;
  background-image: url('//c2.staticflickr.com/4/3713/20116660060_f1e51a5248_m.jpg');
  background-position: 0 0;
  float: left;
  height: 32px;
  margin-right: 8px;
  text-indent: -9999px;
  width: 51px;
}
.cards .amex {
  background-position: -102px 0;
}
.cards li {
  -webkit-transition: all .2s;
  -moz-transition: all .2s;
  -ms-transition: all .2s;
  -o-transition: all .2s;
  transition: all .2s;
  background-image: url('//c2.staticflickr.com/4/3713/20116660060_f1e51a5248_m.jpg');
  background-position: 0 0;
  float: left;
  height: 32px;
  margin-right: 8px;
  text-indent: -9999px;
  width: 51px;
}
.cards li:last-child {
  margin-right: 0;
}

.card .card-header{
	background-color: #414A5C;
	color: #fff;
}

.top_card{
  margin-bottom: 30px;
}

    </style>
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
@endsection

@section('content')
<div class="wow fadeIn" data-wow-delay="0.2s">
  <div class="row">
      <!--cart form start from here-->
      <div class="col-12">
          <!--REVIEW ORDER-->
          <div class="card top_card">
              <div class="card-header">
                  Review Order
              </div>
              <div class="card-block">
                  @foreach($allcheckout as $product)
                    <div class="form-group">
                      <div class="row">
                        <div class="col-2">
                            <img class="img-responsive" src="{{ $product->options->image }}" />
                        </div>
                        <div class="col-6 text-center">
                            <div class="col-xs-12">{{ $product->name }}</div>
                            <div class="col-xs-12"><small>Quantity:<span>{{ $product->qty }}</span></small></div>
                        </div>
                        <div class="col-4 text-right">
                            <h6><span>{{ MarketPlex\Store::currencyIcon() }}</span>{{ $product->price }}</h6>
                        </div>
                      </div>
                    </div>
                  @endforeach
                  <div class="form-group"><hr /></div>
                  @if($totalcheckout > 0)
                  <div class="form-group">
                      <div class="col-xs-12">
                          <strong>Order Total</strong>
                          <div class="pull-right"><span>{{ MarketPlex\Store::currencyIcon() }}</span><span>{{$totalcheckout}}</span></div>
                      </div>
                  </div>

                  @endif
              </div>
          </div>
          <!--REVIEW ORDER END-->
      </div>
      <!--CREDIT CART PAYMENT-->
        {{--@include('layouts.secure-payment-checkout')--}}
      <!--CREDIT CART PAYMENT END-->
      <div class="col-12">
       @include('includes.message.message')
          <!--SHIPPING METHOD-->
          <div class="card ">
            <form class="form-horizontal" method="post" action="{{ route('cart.confirm') }}">
              {{ csrf_field() }}
              <div class="card-header">Address</div>
              <div class="card-block">
                  <div class="form-group">
                      <div class="col-md-12">
                          <h4>Shipping Address</h4>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>Country:</strong></div>
                      <div class="col-md-12">
                          <input type="text" name="country" value="" />
                      </div>
                  </div>
                    <div class="form-group">
                        <div class="col-12">
                            <strong>First Name:</strong>
                            <input type="text" name="first_name" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <strong>Last Name:</strong>
                            <input type="text" name="last_name" value="" />
                        </div>
                    </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>Address:</strong></div>
                      <div class="col-md-12">
                          <input type="text" name="address" value="" />
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>City:</strong></div>
                      <div class="col-md-12">
                          <input type="text" name="city" value="" />
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>State:</strong></div>
                      <div class="col-md-12">
                          <input type="text" name="state" value="" />
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>Zip / Postal Code:</strong></div>
                      <div class="col-md-12">
                          <input type="text" name="zip_code" value="" />
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>Phone Number:</strong></div>
                      <div class="col-md-12"><input type="text" name="phone_number" value="" /></div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12"><strong>Email Address:</strong></div>
                      <div class="col-md-12"><input type="text" name="email_address" value="" /></div>
                  </div>
                  <br>
                  <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <button type="submit" class="btn btn-primary btn-submit-fix">Place Order</button>
                      </div>
                  </div>
              </div>
            </form>
          </div>
          <!--SHIPPING METHOD END-->
      </div>
<!--form end here-->
  </div>
</div>
@endsection