<?php

namespace MarketPlex\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\MarketProduct;
use MarketPlex\Category;

class StoreFrontController extends Controller
{
    //
    public function showStoreFront(Request $request)
    {
        if(env('STORE_CLOSE', true) === true)
            return view('store-comingsoon');
        else if(Product::count() == 0)
            return view('store-comingsoon');

        $user = User::whereEmail(config('mail.admin.address'))->first();

        $guestUser = User::guestUser();
        if ($guestUser)
        {
            $user = $guestUser;
        }
        
        // Developer debug access
        if(!Auth::guest() && (Auth::user()->isDeveloper() || Auth::user()->isGuest()))
            $user = Auth::user();

        if(!$user || $user->hasNoProduct())
            return view('store-comingsoon');

        $marketProducts = MarketProduct::UserProducts($user);
        $category = null;
        if(session()->has('category'))
        {
            $category = session('category');
            $marketProducts = MarketProduct::UserProducts($user)->Categorized($category);
        }
        $viewData = [
            'active_category' => $category ? $category->id : -1,
        ];
        return view('store-front-1', $viewData)->withPaginatedProducts($marketProducts->paginate(6))
                                               ->withCategories(Category::all()->pluck('name', 'id'));
    }

    public function filterCategory(Category $category)
    {
        return redirect()->route('store-front')->withCategory($category);
    }
}
