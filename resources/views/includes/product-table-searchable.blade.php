<!--recently added product-->
<div class="row">
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ $title or 'Śhowing store products' }}</h3>
            <div class="box-tools">
                <div class="input-group" style="width: 150px;">
                    <input type="text" name="table_search" id="search_box" class="form-control input-sm pull-right" placeholder="Search">
                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-default" id="search_by_button_click"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <div id="delete_all"></div>
            <div id="load_table_dom">
                @include('includes.product-table', [ 'is_hidden' => MarketPlex\Product::isColumnHidden(null) ])
            </div><!-- /.box-body -->
        </div><!-- /.box -->
</div>
</div>