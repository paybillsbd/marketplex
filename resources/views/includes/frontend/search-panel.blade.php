<!--form-->
<form action="{{ route('user::products.search') }}" method="GET">
    <div class="col-lg-6 col-lg-offset-3">
        <div class="box box-widget">
            <div class="box-header with-border">
                <h4 class="boxed-header">Find it on {{ config('app.vendor') }}</h4>
            </div>
            <div class="box-body">
                <div class="input-group">
                    <input id="search-box" name="search_box" type="text" class="form-control search_box">
                    <span class="input-group-btn">
                      <button id="product-search-btn" class="btn btn-info btn-flat" type="submit"><i class="fa fa-lg fa-search"><!-- Search --></i></button>
                    </span>
                </div>
            </div>
            <div class="box-footer box-comments{{ $productsCount == 0 ? '' : ' hidden' }}">
                <div class="box-comment">
                    <div class="col-lg-6">
                        <h4 class="C-header">If it is not in {{ config('app.vendor') }}'s catalog:</h4>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button id="product-form-open-button" class="btn btn-info btn-flat laravel-bootstrap-modal-form-open" data-toggle="modal" data-target="#addProduct" type="button"><i class="fa fa-lg fa-plus-square"></i>&ensp; Add Product</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end of form-->

<div class="col-lg-6 col-lg-offset-3 boxPadTop">
    <div id="sell_yours_search" class="box box-down box-info{{--{{ $productsCount == 0 ? ' hidden' : '' }}--}}">
        {{--@include('includes.product-search-table')--}}
    </div>
</div>