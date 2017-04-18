<?php

namespace MarketPlex\Http\Controllers;

use Illuminate\Http\Request;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\Category;

class StoreFrontController extends Controller
{
    //
    public function showStoreFront()
    {
        if(env('STORE_CLOSE', true) === true)
            return view('store-comingsoon');
        else if(Product::count() == 0)
            return view('store-comingsoon');

        $user = User::whereEmail(config('mail.admin.address'))->first();
        if(!$user || $user->products->count() == 0)
            return view('store-comingsoon');

        $paginatedProducts = $user->products()->paginate(4);
        // $paginatedProducts->setPath('showcase');
        return view('store-front-1',  
                [
                    'categories' => Category::all(),
                    'carousels' => [ 'Slogan0', 'Slogan1', 'Slogan2' ]

                ])->withPaginatedProducts($paginatedProducts);
    }
}
