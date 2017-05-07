<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api', 'as' => 'user::'], function () {

	// Product controller
	Route::group([ 'prefix' => 'products' ], function () {
		
		Route::get('/medias/image/{file_name}', [ 'uses' => 'ProductController@image', 'as' => 'products.medias.image' ]);
	    Route::get('/{product}/quick-view', [ 'uses' => 'ProductController@quickView', 'as' => 'products.quick.view' ]);
	});
});
