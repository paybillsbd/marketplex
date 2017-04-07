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

Route::get('/', function () {
    return view('store-comingsoon');
});

Auth::routes();

Route::group([ 'as' => 'user::' ], function() {

	Route::get('/home', [ 'uses' => 'HomeController@index', 'as' => 'home' ]);

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
	    Route::get('/medias/image/{file_name}', [ 'uses' => 'ProductController@image', 'as' => 'products.medias.image' ]);

	    // Sk script
	    Route::get('/{product}/quick-view', [ 'uses' => 'ProductController@quickView', 'as' => 'products.quick.view' ]);
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
    Route::get('/categories/edit/{category_id}', [ 'uses' => 'CategoryController@edit', 'as' => 'categories.edit' ]);
    Route::post('/categories/edit/{category_id}', [ 'uses' => 'CategoryController@postEdit', 'as' => 'categories.update' ]);
    Route::post('/categories/delete/{category_id}', [ 'uses' => 'CategoryController@delete', 'as' => 'categories.delete' ]);
    Route::get('/categories/approvals', [ 'uses' => 'CategoryController@approvals', 'as' => 'categories.approvals' ]);
    Route::post('/categories/approvals/confirm/{id}', [ 'uses' => 'CategoryController@confirmApproval', 'as' => 'categories.approvals.confirm' ]);
});
