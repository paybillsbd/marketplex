<?php

namespace MarketPlex\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\MarketProduct;
use MarketPlex\Category;
use Cart;
use Validator;
use Mail;
use MarketPlex\Mail\Order;
use MarketPlex\Mail\OrderPlacement;

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
        // return $user;
        // Developer debug access
        if(!Auth::guest() && (Auth::user()->isDeveloper() || Auth::user()->isGuest()))
            $user = Auth::user();
// return $user->hasNoProduct() ? 'Yes' : 'No';
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
        
        $cart = Cart::count();
        
        $totalcart = Cart::content();
        
        // $products = MarketProduct::all();
        
        return view('store-front-1', $viewData)->withPaginatedProducts($marketProducts->paginate(6))
                                                ->withCategories(Category::all()->pluck('name', 'id'))
                                                ->with(compact('cart', 'totalcart'));
                                                
                                               
    }

    public function filterCategory(Category $category)
    {
        return redirect()->route('store-front')->withCategory($category);
    }
    
    
}
