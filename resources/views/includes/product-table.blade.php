    <table id="parent" class="table table-hover table-bordered product_table">
        <tr>
            <!-- <th>ID</th> -->
            <th style="vertical-align: middle"><input type="checkbox" name="select_all" id="select_all"> </th>
            <th data-sort="product_name" data-order="ASC" id="sort_by_click">
                <a href="#">Product Name</a>
            </th>
            <th data-sort="category" data-order="ASC" id="sort_by_click">
                <a href="#">Category</a>
            </th>
            <th>MRP</th>
            <th>Discount</th>
            <th data-sort="price" data-order="ASC" id="sort_by_click">
                <a href="#">Price</a>
            </th>
            <th>Image</th>
            <th>Available Quantity</th>
            <th>Action</th>
        </tr>

        @if(isset($products))

            @foreach( $products as $product )

                @if($product->marketProduct())
                    <tr id="product_{{ $product->id }}">
                        <!-- <td id="child"><a href="">001</a> </td> -->
                        <td style="vertical-align: middle"><input type="checkbox" name="check_box" value="{{ $product->id }}" id=""></td>
                        <td id="child"><a href="">{{ $product->title }}</a></td>
                        <td id="child"><a href="">{{ $product->categoryName() }}</a></td>
                        <td id="child"><a href="">{{ $product->mrp }}</a></td>
                        <td id="child"><a href="">{{ $product->discount }} %</a></td>
                        <td id="child"><a href="">&#2547 {{ $product->marketProduct()->price }}</a></td>
                        <td id="child">
                            <a class="view_detail" data-product_url="{{ route('user::products.quick.view', [ 'product' => $product, 'api_token' => MarketPlex\Helpers\ImageManager::PUBLIC_TOKEN ]) }}">
                                <img src="{{ $product->thumbnail() }}" height="60px" width="90px"/>
                            </a>
                        </td>
                        <td id="child"><a href="">{{ $product->available_quantity }}</a></td> <!-- Available quantity-->
                        <td class="text-center" id="child">
                            <form id="product-modification-form" class="form-horizontal" method="POST" >
                                {!! csrf_field() !!}

                                @include('includes.single-product-actions', compact('product'))
                        </form>
                    </td>
                </tr>

            @endif

        @endforeach
    @endif
    </table>
    <div class="col-sm-12 noPadMar text-center parentPagDiv">
    {{ $products->links() }}
    </div>