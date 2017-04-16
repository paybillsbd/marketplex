<h4 class="block-title">Product Spec</h4>
                                <div class="block-of-block">
                                    <div id="product-create-spec" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="spec_title" class="col-sm-3 control-label">Spec Title:</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="spec_title" name="spec_title" placeholder="">
                                                <input type="button" hidden>
                                                <!--<span class="help-block">
                                                    <strong></strong>
                                                </span>-->
                                            </div>

                                            <!--<div class="col-sm-7 padT5"><b></b></div>-->
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-3 control-label">Control Type:</label>
                                            <div class="col-sm-3">
                                                <select id="control_type" name="control_type" class="form-control"  data-placeholder="Control Type" style="width: 100%;">
                                                    @foreach(MarketPlex\Product::VIEW_TYPES['group'] as $id => $control)
                                                        <option value="{{ $id }}">{{ $control }}</option>
                                                    @endforeach
                                                    @foreach(MarketPlex\Product::VIEW_TYPES['single'] as $id => $control)
                                                        <option value="{{ $id }}">{{ $control }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="button" hidden>
                                            </div>
                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label">Value:</label>

                                            <div id="input" class="col-sm-3 spec-controls" hidden="">
                                                <input type="text" class="form-control" id="single_spec" name="single_spec" placeholder="">
                                                <input type="button" hidden>
                                            </div>

                                            <div id="options" class="col-sm-3 spec-controls" hidden="">
                                                <div class="radio">
                                                    <label for="optradio_1"><input type="radio" id="optradio" name="optradio">---</label>
                                                </div>
                                            </div>

                                            <div id="dropdown" class="col-sm-3 spec-controls" hidden="">
                                                <select id="optdropdown" name="optdropdown" class="form-control"  data-placeholder="" style="width: 100%;">
                                                    <option>---</option>
                                                </select>
                                            </div>

                                            <div id="spinner" class="spec-controls" hidden="">
                                                <div class="col-sm-1">
                                                    <div class="input-group spinner">
                                                        <input type="text" class="form-control" id="optspinner_min" name="optspinner_min" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label  class="col-sm-1 control-label">To</label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="optspinner_max" name="optspinner_max" placeholder="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2 add-option" hidden="">
                                                <button id="add-option-btn" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> </button>
                                            </div>

                                        </div>
                                        <div class="form-group">

                                            <label class="col-sm-3 control-label"></label>

                                            <div class="col-sm-3 add-option">
                                                <input type="text" class="form-control" id="option_input" name="option_input" placeholder="Type new value to add above">
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-3 control-label"></label>
                                            <div class="col-sm-3">
                                                <button id="apply_spec" name="apply_spec" class="btn btn-default btn-flat">Apply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel">
                                        <div class="panel-body">
                                            <input name="spec_count" id="spec_count" type="text" value="{{ isset($product) ? count($product->specialSpecs()) : 0 }}" hidden>
                                            <table id="spac_table" class="table table-hover table-condensed table-bordered text-center spec-table">
                                                <thead>
                                                <tr>
                                                    <th>Spec Title</th>
                                                    <th>Option Type</th>
                                                    <th>Values</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody><!-- $product->specialSpecs() -->
                                                @if(isset($product))

                                                    @each('includes.product-special-specs', $product->specialSpecs(), 'properties', 'includes.product-specs-empty')

                                                @else

                                                    @include('includes.product-specs-empty')

                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                              

                                <h4 class="block-title">Availability</h4>
                                <div class="block-of-block">
                                    <div id="product-create-privacy" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label"></label>
                                            <div class="col-sm-3" >
                                                <div class="checkbox">
                                                    <label><input id="is_public" name="is_public" type="checkbox" {{ isset($product) ? ($product->is_public ? 'checked="checked"' : old('is_public')) : '' }}>Make this product public.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>