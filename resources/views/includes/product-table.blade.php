    <table id="parent" class="table table-hover table-bordered product_table">
        <tr>
            <!-- <th>ID</th> -->
            <th style="vertical-align: middle"><input type="checkbox" name="select_all" id="select_all"> </th>
            <th data-sort="product_name"
                class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['name'] ? 'hidden' : '' }}"
                data-order="ASC" id="sort_by_click">
                <a href="#">Product Name</a>
            </th>
            <th data-sort="category" data-order="ASC" id="sort_by_click" class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['category'] ? 'hidden' : '' }}">
                <a href="#">Category</a>
            </th>
            <th class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['mrp'] ? 'hidden' : '' }}">MRP</th>
            <th class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['discount'] ? 'hidden' : '' }}">Discount</th>
            <th data-sort="price" data-order="ASC" id="sort_by_click"
                class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['price'] ? 'hidden' : '' }}">
                <a href="#">Price</a>
            </th>
            <th class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['store'] ? 'hidden' : '' }}">Store</th>
            <th class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['image'] ? 'hidden' : '' }}">Image</th>
            <th class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['available_quantity'] ? 'hidden' : '' }}">Available Quantity</th>
            <th class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['action'] ? 'hidden' : '' }}">Action</th>
        </tr>

        @isset($products)
            @foreach( $products as $product )
                @if ($product->marketProduct())
                <tr id="product_{{ $product->id }}" class="{{ $product->trashed() ? 'deleted-product' : '' }}">
                    <!-- <td id="child"><a href="">001</a> </td> -->
                    <td style="vertical-align: middle">
                        <input type="checkbox" name="check_box" value="{{ $product->id }}" class="{{ $product->trashed() ? 'hidden' : '' }}">
                    </td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['name'] ? 'hidden' : '' }}">
                        <a href="">{{ $product->title }}</a></td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['category'] ? 'hidden' : '' }}">
                        <a href="">{{ $product->categoryName() }}</a></td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['mrp'] ? 'hidden' : '' }}">
                        <a href="">{{ $product->mrp }}</a></td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['discount'] ? 'hidden' : '' }}">
                        <a href="">{{ $product->discount }} %</a></td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['price'] ? 'hidden' : '' }}">
                        <a href="">&#2547 {{ $product->marketProduct()->price }}</a></td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['store'] ? 'hidden' : '' }}">
                        <a href="">{{ $product->store ? $product->store->name : 'Store unknown' }}</a>
                    </td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['image'] ? 'hidden' : '' }}">
                        <a class="view_detail" data-product_url="{{ route('user::products.quick.view', [ 'product' => $product, 'api_token' => MarketPlex\Helpers\ImageManager::PUBLIC_TOKEN ]) }}">
                            <img src="{{ $product->thumbnail() }}" height="60px" width="90px"/>
                        </a>
                    </td>
                    <td id="child"
                        class="{{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['available_quantity'] ? 'hidden' : '' }}">
                        <a href="">{{ $product->available_quantity }}</a></td> <!-- Available quantity-->
                    <td class="text-center {{ isset($is_hidden) && is_array($is_hidden) && $is_hidden['action'] ? 'hidden' : '' }}" id="child">
                        <div class="form-horizontal{{ $product->trashed() ? ' hidden' : '' }}" >
                            @include('includes.single-product-actions', compact('product'))
                        </div>
                    </td>
                </tr>
                @endif
        @endforeach
    @endisset
    </table>
    <div class="col-sm-12 noPadMar text-center parentPagDiv">
    {{ $products->links() }}
    </div>