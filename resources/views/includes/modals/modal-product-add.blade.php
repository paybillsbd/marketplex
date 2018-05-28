add product modal-->
<div id="addProduct" class="modal fade laravel-bootstrap-modal-form" role="dialog">

    <div id="has_error" class="hidden{{ count($errors) > 0 ? ' has-error' : '' }}"></div>
    <div id="is_edit" class="hidden{{ isset($product) ? ' is-edit' : '' }}"></div>

    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
                <div class="custom_tab">
                    <ul class="nav nav-tabs">
                        <li class="{{ ($tab == 'single_product_entry_tab') ? 'active' : '' }}"><a href="#tab-edit" data-toggle="tab">Add Product Details</a></li>
                        <li class="{{ ($tab == 'bulk_product_entry_tab') ? 'active' : '' }}"><!-- <a href="#tab-messages" data-toggle="tab">Upload Products</a></li> -->
                    </ul>
                </div>
            </div>

            <!--Custom tab content start from here-->
            <div id="generalTabContent" class="tab-content">

                @include('errors')
                <div id="tab-edit" class="tab-pane fade in{{ ($tab == 'single_product_entry_tab') ? ' active' : '' }}"> <!-- hidden is intensional feature here -->

                    <!-- form start -->
                    <!-- route('user::products.create') -->
                    <!-- enctype="multipart/form-data" -->
                    <form id="product-create-form" class="form-horizontal"
                          action="{{ isset($product) ? route('user::products.update', [$product]) : route('user::products.create') }}"
                          enctype="multipart/form-data"
                          method="POST">

                        {!! csrf_field() !!}

                        <h4 class="block-title">Product Summary</h4>
                        <div class="block-of-block">

                            <div class="modal-body">

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select store</label>
                                    <div class="col-sm-7">

                                        <select name="store_name" id="store_name" class="form-control select2" data-placeholder="Select Store" style="width: 100%;">
                                            @foreach($stores as $name_as_url => $name)
                                                <option value="{{ $name_as_url }}"{{ (isset($product) && $product->store) && ($product->store->name_as_url == $name_as_url || old('store_name') == $name_as_url) ? ' selected' : '' }}> {{ $name }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('store_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('store_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                                    <label  class="col-sm-3 control-label">Product Category:</label>
                                    <div class="col-sm-6">
                                        <select name="category" id="category" class="form-control select2" data-placeholder="Select a Category" style="width: 100%;">

                                            @if(isset($categories))
                                                @foreach( $categories as $category )
                                                    <option value="{{ $category->id }}"{{ (isset($product) && $product->category) && ($product->category->id == $category->id || old('category') == $category->id) ? ' selected' : '' }}>{{ $category->name or 'Uncategorized' }}</option>
                                                @endforeach
                                            @else
                                                <option>{{ 'Uncategorized' }}</option>
                                            @endif
                                        </select>
                                        @if ($errors->has('category'))
                                            <span class="help-block">
                                              <strong>{{ $errors->first('category') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <button formmethod="GET" formaction="{{ route('user::categories') }}" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> </button>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                    <label  class="col-sm-3 control-label">Product Sub Category:</label>
                    <div class="col-sm-7">
                        <select name="subcategory" class="form-control select2" multiple="multiple" data-placeholder="Select a sub Category" style="width: 100%;">

                            {{--@if(isset($categories))--}}
                                {{--@foreach( $categories as $category )--}}
                                {{--<option>{{ $category->category_name or 'Uncategorized' }}</option>--}}
                                {{--@endforeach--}}
                                {{--@endif--}}
                                        </select>
                                    </div>
                                    {{--<div class="col-sm-2">--}}
                                {{--<button formmethod="GET" formaction="{{ route('user::categories') }}" class="btn btn-info btn-flat"><i class="fa fa-plus"></i> </button>--}}
                                {{--</div>--}}
                                        </div>-->
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label for="title" class="col-sm-3 control-label">Product Title:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="ex: kitkat 5RS" value="{{ isset($product) ? $product->title : old('title') }}">
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                              <strong>{{ $errors->first('title') }}</strong>
                                          </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('manufacturer_name') ? ' has-error' : '' }}">
                                    <label for="Manufacturer" class="col-sm-3 control-label">Manufacturer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="manufacturer_name" name="manufacturer_name" placeholder="ex: dairy milk" value="{{ isset($product) ? $product->marketManufacturer() : old('manufacturer_name') }}">
                                        @if ($errors->has('manufacturer_name'))
                                            <span class="help-block">
                                              <strong>{{ $errors->first('manufacturer_name') }}</strong>
                                          </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" style="display:none;">
                                    <label  class="col-sm-3 control-label">Product Type:</label>
                                    <div class="col-sm-3">
                                        <select name="product_type" class="form-control select2" multiple="multiple" data-placeholder="Select a Category" style="width: 100%;">
                                            @foreach(MarketPlex\Product::EXISTANCE_TYPE as $key => $type)
                                                <option value="{{ $key }}" {{ (isset($product) && $key == $product->type) ? ' selected' : '' }}> {{ $type }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                    <label for="price" class="col-sm-3 control-label">Price:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="price" name="price" placeholder="ex: 3{!! MarketPlex\Store::currencyIcon() !!}" value="{{ isset($product) ? $product->marketPrice() : old('price') }}">
                                        @if ($errors->has('price'))
                                            <span class="help-block">
                                              <strong>{{ $errors->first('price') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(false)
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">Status:</label>
                                    <div class="col-sm-2">
                                        <select name="status" id="status" class="form-control select2" style="width: 100%;"{{ isset($product) ? '' : ' hidden' }}>
                                            @if(isset($product))
                                                @foreach(MarketPlex\Product::STATUS_FLOWS as $status)
                                                    <option {{ $status == $product->status ? ' selected' : '' }}> {{ $status }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group{{ $errors->has('available_quantity') ? ' has-error' : '' }}">
                                    <label for="available_quantity" class="col-sm-3 control-label">Available Quantity:</label>
                                    <div class="col-sm-2">
                                        <div class="input-group spinner">
                                            <input type="text" name="available_quantity" class="form-control"
                                                   value="{{ isset($product) ? $product->getAvailableQuantity() : MarketPlex\Product::MIN_AVAILABLE_QUANTITY }}"
                                                   min="{{ MarketPlex\Product::MIN_AVAILABLE_QUANTITY }}">

                                           <!--  <div class="input-group-btn-vertical">
                                                <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                            </div> -->
                                        </div>
                                        @if ($errors->has('available_quantity'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('available_quantity') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h4 class="block-title">Upload Media</h4>
                        <div class="block-of-block">
                            <div id="product-create-upload-image" class="form-horizontal">

                                <div class="from-group{{ $errors->has('upload_image_1') || $errors->has('upload_image_2') || $errors->has('upload_image_3') || $errors->has('upload_image_4') ? ' has-error' : '' }}">
                                    <div class="row">
                                        <label for="" class="col-sm-3 control-label">Upload Images: (Max: {{ MarketPlex\ProductMedia::getMaxUploadFileSizeInMBytes() . ' MB' }})</label>
                                        <div class="col-md-2">
                                            <div class="thumbnail">
                                                <img id="blah-1" src="{{ isset($product) ? $product->previewImage(0) : MarketPlex\Product::defaultImage() }}">
                                                <span class="btn btn-default btn-file">

                                                    Browse ({{ MarketPlex\Product::imageDisplayOrientation(0) }}) <input id="imgInp-1" name="upload_image_1" data-image_id="1" type="file" style="margin-top: 7px" >
                                                </span>

                                            </div>
                                            <div class="col-md-6 col-md-offset-3">
                                                <span><a href="#" data-image_src="blah-1" data-file_input="imgInp-1" {{ (isset($product) && $product->getImageURL(0)) ? 'data-image_url='.$product->getImageURL(0)['url'].'' : "" }} {{ (isset($product) && $product->getImageURL(0)) ? 'data-image_title='.$product->getImageURL(0)['title'].'' : "" }} {{ (isset($product) && $product->getImageURL(0)) ? "onClick=removeServerImage(this)" : 'onClick=removeLocalImage(this)' }}><i class="fa fa-times"></i></a></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="thumbnail">
                                                <img id="blah-2" src="{{ isset($product) ? $product->previewImage(1) : MarketPlex\Product::defaultImage() }}">
                                                <span class="btn btn-default btn-file">

                                                    Browse ({{ MarketPlex\Product::imageDisplayOrientation(1) }}) <input id="imgInp-2" name="upload_image_2" data-image_id="2" type="file" style="margin-top: 7px" >
                                                </span>

                                            </div>
                                            <div class="col-md-6 col-md-offset-3">
                                                {{--<span><a href="#" id="remove_image_2"><i class="fa fa-times"></i></a></span>--}}
                                                <span><a href="#" data-image_src="blah-2" data-file_input="imgInp-2"  {{ (isset($product) && $product->getImageURL(1)) ? 'data-image_url='.$product->getImageURL(1)['url'].'' : "" }} {{ (isset($product) && $product->getImageURL(1)) ? 'data-image_title='.$product->getImageURL(1)['title'].'' : "" }} {{ (isset($product) && $product->getImageURL(1)) ? "onClick=removeServerImage(this)" : 'onClick=removeLocalImage(this)' }}><i class="fa fa-times"></i></a></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="thumbnail">
                                                <img id="blah-3" src="{{ isset($product) ? $product->previewImage(2) : MarketPlex\Product::defaultImage() }}">
                                                <span class="btn btn-default btn-file">

                                                    Browse ({{ MarketPlex\Product::imageDisplayOrientation(2) }}) <input id="imgInp-3" name="upload_image_3" data-image_id="3" type="file" style="margin-top: 7px" >
                                                </span>

                                            </div>
                                            <div class="col-md-6 col-md-offset-3">
                                                {{--<span><a href="#" id="remove_image_3"><i class="fa fa-times"></i></a></span>--}}
                                                <span><a href="#" data-image_src="blah-3" data-file_input="imgInp-3"  {{ (isset($product) && $product->getImageURL(2)) ? 'data-image_url='.$product->getImageURL(2)['url'].'' : "" }} {{ (isset($product) && $product->getImageURL(2)) ? 'data-image_title='.$product->getImageURL(2)['title'].'' : "" }} {{ (isset($product) && $product->getImageURL(2)) ? "onClick=removeServerImage(this)" : 'onClick=removeLocalImage(this)' }}><i class="fa fa-times"></i></a></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="thumbnail">
                                                <img id="blah-4" src="{{ isset($product) ? $product->previewImage(3) : MarketPlex\Product::defaultImage() }}">
                                                <span class="btn btn-default btn-file">

                                                    Browse ({{ MarketPlex\Product::imageDisplayOrientation(3) }}) <input id="imgInp-4" name="upload_image_4" data-image_id="4" type="file" style="margin-top: 7px" >
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-md-offset-3">
                                                {{--<span><a href="#" id="remove_image_4"><i class="fa fa-times"></i></a></span>--}}
                                                <span><a href="#" data-image_src="blah-4" data-file_input="imgInp-4" {{--{{ (isset($product)) ? 'data-product_id='.$product->id.' : "" }}--}}  {{ (isset($product) && $product->getImageURL(3)) ? 'data-image_url='.$product->getImageURL(3)['url'].'' : "" }} {{ (isset($product) && $product->getImageURL(3)) ? 'data-image_title='.$product->getImageURL(3)['title'].'' : "" }} {{ (isset($product) && $product->getImageURL(3)) ? "onClick=removeServerImage(this)" : 'onClick=removeLocalImage(this)' }}><i class="fa fa-times"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                    @if ($errors->has('upload_image_1') || $errors->has('upload_image_2') || $errors->has('upload_image_3') || $errors->has('upload_image_4'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('upload_image_1') }}</strong>
                                      </span>
                                    @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('upload_video') ? ' has-error' : '' }}">
                                    <label for="upload_video" class="col-sm-3 control-label">Upload Video:</label>
                                    <div class="col-sm-3">
                                        <input id="upload_video" name="upload_video" type="file" style="margin-top: 7px" placeholder="Include some file">

                                        @if ($errors->has('upload_video'))
                                            <span class="help-block">
                                              <strong>{{ $errors->first('upload_video') }}</strong>
                                          </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"></label>
                                    <div class="checkbox col-sm-8">
                                        <label><input id="has_embed_video" name="has_embed_video" type="checkbox" value="{{ isset($product) && !$product->hasEmbedVideo() ? 'checked' : '' }}"{{ isset($product) && !$product->hasEmbedVideo() ? ' checked' : '' }}>Or Embed a Video.</label>
                                    </div>
                                </div>

                                <div class="form-group{{ (isset($product) && $product->hasEmbedVideo()) ? '' : ' hidden' }} embed_video_form_group">
                                    <label for="" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-8">
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <div id="embed_iframe">

                                                @if(isset($product) && $product->hasEmbedVideo())

                                                    {!! $product->videoEmbedUrl()['url'] !!}

                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group embed_video{{ $errors->has('embed_video_url') ? ' has-error' : '' }}">
                                    <label for="embed_video" class="col-sm-3 control-label">Embed Video:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"
                                               id="embed_video_url" name="embed_video_url" placeholder="<iframe> url </iframe>"
                                               value="{{ isset($product) ? $product->videoEmbedUrl()['url'] : '' }}">

                        <span class="help-block{{ $errors->has('embed_video_url') ? '' : ' hidden' }}">
                            <strong>{{ $errors->first('embed_video_url') }}</strong>
                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="block-title">Description</h4>
                        <div class="block-of-block">
                            <div class="box-body">
                                <div>
                                    <textarea name="description" class="textarea"
                                    placeholder="Product Description"
                                    style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                    >{{ isset($product) ? $product->description : old('description') }}
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        @if ( MarketPlex\Product::isFeatureSpecAllowed() )
                            @include('includes.product-modal-spec')
                        @endif

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-flat">Save</button>
                            <input id="btn-reset-product-form" type="reset" value="Reset" hidden>
                        </div>

                    </form>
                    <!-- form ends -->

                </div>


                @include('includes.product-modal-upload')
                
                <div id="tab-logistic" class="tab-pane fade in hidden">
                    <div class=" form-horizontal">

                        <form action="" method="POST" id="js-upload-form">
                            
                        <h4 class="block-title">Tax & Price Calculation</h4>
                        <div class="block-of-block">
                            <div id="product-create-privacy" class="form-horizontal">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">Tax Type:</label>
                                    <div class="col-sm-3">
                                        <select id="control_type" name="control_type" class="form-control"  data-placeholder="Control Type" style="width: 100%;">
                                                <option value="Exempt">Exempt</option>
                                                <option value="Standard">Standard</option>
                                        </select>
                                    </div>
                                 </div>
                            </div>
                            
                            <div class="form-group">
                                    <label for="buy_price" class="col-sm-3 control-label">Buy Price:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="buy_price" name="buy_price" placeholder="ex: 6₹" value="">
                                       
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="sell_price" class="col-sm-3 control-label">Sell Price:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="sell_price" name="sell_price" placeholder="ex: 9₹" value="">
                                       
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="profit" class="col-sm-3 control-label">Profit Margin:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="profit" name="profit" placeholder="ex: 3₹" value="">
                                       
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="final_price" class="col-sm-3 control-label">Final Price:</label>
                                    <div class="col-sm-2">
                                        <label for="final_price" class="col-sm-3 control-label"><h4 style="padding: 0 !important; margin: 0 !important;">00₹</h4></label>
                                       
                                    </div>
                            </div>
                        </div>
                            
                          
                        <h4 class="block-title">Product Deliver Type</h4>
                        <div class="block-of-block">
                            <div id="product-create-privacy" class="form-horizontal">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">Delivery Type:</label>
                                    <div class="col-sm-3">
                                        <select id="control_type" name="control_type" class="form-control"  data-placeholder="Control Type" style="width: 100%;">
                                                <option value="Exempt">Cash On Deliver</option>
                                                <option value="Standard">Full Paid</option>
                                        </select>
                                    </div>
                                 </div>
                            </div>
                        </div>
                        
                        <h4 class="block-title">Shipment</h4>
                        <div class="block-of-block">
                            <div id="product-create-privacy" class="form-horizontal">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">Select Vendor:</label>
                                    <div class="col-sm-3">
                                        <select id="control_type" name="control_type" class="form-control"  data-placeholder="Control Type" style="width: 100%;">
                                                <option value="Exempt">Vendor Name</option>
                                                <option value="Standard">Vendor Name</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button formmethod="GET" formaction="" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> </button>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label class="col-xs-3 control-label">Expected Date:</label>
                                    <div class="col-sm-3 date">
                                        <div class="input-group input-append date" id="dateRangePicker">
                                            <input type="text" class="form-control" name="date" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipping_cost" class="col-sm-3 control-label">Cost:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="shipping_cost" name="shipping_cost" placeholder="ex: 3₹" value="">
                                       
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Expected Delivery Timeline:</label>
                                    <div class="col-sm-2 date">
                                        <div class="input-group input-append date" id="dateRangePicker">
                                            <input type="text" class="form-control" name="date" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                            <label  class="col-sm-1 control-label">To</label>
                                        </div>
                                    <div class="col-sm-2 date">
                                        <div class="input-group input-append date" id="dateRangePicker">
                                            <input type="text" class="form-control" name="date" />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="block-title">Return</h4>
                        <div class="block-of-block">
                            <div id="product-create-privacy" class="form-horizontal">
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Is Returnable:</label>
                                    <div class="col-sm-3" >
                                        <div class="checkbox">
                                            <label><input id="is_public" name="is_public" type="checkbox" ></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label  class="col-sm-3 control-label">Return days:</label>
                                    <div class="col-sm-3">
                                        <select id="control_type" name="control_type" class="form-control"  data-placeholder="Control Type" style="width: 100%;">
                                                <option value="Exempt">7 Days</option>
                                                <option value="Standard">15 Days</option>
                                        </select>
                                    </div>
                            </div>
                            
                             <div class="form-group">
                                    <label  class="col-sm-3 control-label">Return Conditional Text:</label>
                                    <div class="col-sm-9">
                                       <div class="box-body">
                                        <div>
                                            <textarea name="description" class="textarea" placeholder="Product Description"
                                                      style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                            ></textarea>
                                        </div>
                            </div>
                                    </div>
                            </div>
                        
                        </div>

                            <br>

                            

                            <div class="modal-footer">

                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat">Save</button>
                            </div>

                        </form>


                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<!--end add product modal