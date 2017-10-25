<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Test */
Route::get('/phpinfo', function() {
    return phpinfo();
});

Route::get('/event', function() {
    event(new \MarketPlex\Events\ClientAction([ 'id' => 4, 'name' => 'MyEvent' ]));
});
Route::get('/app', function() {
    return 'Its me';
});


// cart test

// add products to the cart
Route::get('/addcart/{id}', [
    'uses' => 'StoreFrontController@addCart',
    'as'=> 'cart.add'
    ]);


// show all cart products
Route::get('/cart/show', [
    'uses' => 'StoreFrontController@showCart',
    'as'=> 'cart.show'
    ]);
    
// add quantity of one product in cart page
Route::get('/cart/addqt/{id}', [
    'uses' => 'StoreFrontController@addQtCart',
    'as'=> 'cart.addqt'
    ]);
    
// decrease quantity of one product in cart page
Route::get('/cart/remove/{id}', [
    'uses' => 'StoreFrontController@removeCart',
    'as'=> 'cart.remove'
    ]);
    
// remove a product from cart page
Route::get('/cart/removethis/{id}', [
    'uses' => 'StoreFrontController@removethisCart',
    'as'=> 'cart.removethis'
    ]);
    
// remove all items from cart
Route::get('/cart/removeall/', [
    'uses' => 'StoreFrontController@removeallCart',
    'as'=> 'cart.removeall'
    ]);
        
 

// end cart test



/* Development */

Route::get('/', [ 'uses' => 'StoreFrontController@showStoreFront', 'as' => 'store-front' ]);

Route::get('/categories/{category}', [ 'uses' => 'StoreFrontController@filterCategory', 'as' => 'store-front.categories.filter' ]);

Route::get('/about', function() {
    return view('store-about');
});

Auth::routes();

Route::group([ 'as' => 'user::' ], function() {

	Route::get('/home', [ 'uses' => 'HomeController@index', 'as' => 'home' ]);

    Route::get('/who-am-i', [ 'uses' => 'HomeController@user', 'as' => 'info' ]);

	// Product controller
	Route::group([ 'prefix' => 'products' ], function () {

	    Route::get('/', [ 'uses' => 'ProductController@index', 'as' => 'products' ]);
	    Route::post('/create', [ 'uses' => 'ProductController@create', 'as' => 'products.create' ]);
	    Route::get('/search', [ 'uses' => 'ProductController@search', 'as' => 'products.search' ]);
	    Route::post('/{product}/sell-yours', [ 'uses' => 'ProductController@copy', 'as' => 'products.sell-yours' ]);
	    Route::post('/{product}/edit', [ 'uses' => 'ProductController@edit', 'as' => 'products.edit' ]);
	    Route::post('/{product}/update', [ 'uses' => 'ProductController@update', 'as' => 'products.update' ]);
	    Route::post('/{product}/delete', [ 'uses' => 'ProductController@delete', 'as' => 'products.delete' ]);
	    Route::post('/{product_bulk_id}/product-bulk-delete', [ 'uses' => 'ProductController@bulkDelete', 'as' => 'products.bulk.delete' ]);
	    Route::get('/approvals', [ 'uses' => 'ProductController@approvals', 'as' => 'products.approvals' ]);
	    Route::post('/approvals/confirm/{id}', [ 'uses' => 'ProductController@confirmApproval', 'as' => 'products.approvals.confirm' ]);
	    Route::post('/import/csv', [ 'uses' => 'ProductController@uploadCSV', 'as' => 'products.upload.csv' ]);
	    Route::get('/{image_title}/image-delete', [ 'uses' => 'ProductController@imageDelete', 'as' => 'products.image.delete' ]);
	    Route::get('/{search_item}/search-product', [ 'uses' => 'ProductController@productSearch', 'as' => 'products.search.table' ]);
	    Route::get('/{search_item}/search-single-product', [ 'uses' => 'ProductController@productSearchSingle', 'as' => 'products.search.single.table' ]);
	    Route::get('/{search_item}/search-all-product', [ 'uses' => 'ProductController@productSearchAll', 'as' => 'products.search.all.table' ]);
	});

    // Store controller
    Route::group(['prefix' => 'stores'], function () {

        Route::get('/', [ 'uses' => 'StoreController@index', 'as' => 'stores' ]);           
        Route::get('/redirect/site/{site}', [ 'uses' => 'StoreController@redirectUrl', 'as' => 'stores.redirect' ]);           
        Route::get('/create/name/{name}/site/{site}/business/{business}', [ 'uses' => 'StoreController@createOnSignUp', 'as' => 'stores.create-on-signup' ]);           
        Route::post('/create', [ 'uses' => 'StoreController@create', 'as' => 'stores.create' ]);           
        Route::post('/{store}', [ 'uses' => 'StoreController@postUpdate', 'as' => 'stores.update' ]);           
        Route::get('/{store}/edit/', [ 'uses' => 'StoreController@update', 'as' => 'stores.edit' ]);           
        Route::post('/{store}/delete/', [ 'uses' => 'StoreController@delete', 'as' => 'stores.delete' ]); 
        Route::get('/approvals', [ 'uses' => 'StoreController@approvals', 'as' => 'stores.approvals' ]);
        Route::post('/approvals/confirm/{id}', [ 'uses' => 'StoreController@confirmApproval', 'as' => 'stores.approvals.confirm' ]);          
        Route::get('/suggest/input/{input}', [ 'uses' => 'StoreController@suggest', 'as' => 'stores.suggest' ]);          
    });

    // Category controller
    Route::get('/categories', [ 'uses' => 'CategoryController@index', 'as' => 'categories' ]);
    Route::post('/categories/create', [ 'uses' => 'CategoryController@create', 'as' => 'categories.create' ]);
    Route::get('/categories/{category_id}/edit', [ 'uses' => 'CategoryController@edit', 'as' => 'categories.edit' ]);
    Route::post('/categories/{category_id}', [ 'uses' => 'CategoryController@postEdit', 'as' => 'categories.update' ]);
    Route::post('/categories/delete/{category_id}', [ 'uses' => 'CategoryController@delete', 'as' => 'categories.delete' ]);
    Route::get('/categories/approvals', [ 'uses' => 'CategoryController@approvals', 'as' => 'categories.approvals' ]);
    Route::post('/categories/approvals/confirm/{id}', [ 'uses' => 'CategoryController@confirmApproval', 'as' => 'categories.approvals.confirm' ]);
});
