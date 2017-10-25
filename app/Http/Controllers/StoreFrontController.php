<?php

namespace MarketPlex\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\MarketProduct;
use MarketPlex\Category;
use Cart;

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
        return view('store-front-1', $viewData)->withPaginatedProducts($marketProducts->paginate(6))
                                                ->withCategories(Category::all()->pluck('name', 'id'))
                                                ->with('cart',$cart);
                                                
                                               
    }

    public function filterCategory(Category $category)
    {
        return redirect()->route('store-front')->withCategory($category);
    }
    
    // Test Cart Functions
    public function addCart($id)
    {
        $products = MarketProduct::find($id);
        
        $result = Cart::add([
            'id' => $id,
            'name' => $products->title,
            'qty' => 1,
            'price' => $products->mrp(),
            'options' => ['image' => $products->thumbnail()]
            ]);

        
        return redirect()->back();
        
        
    }
    public function showCart()
    {
        
       $totalcart = Cart::content();
        
       $totalprice = Cart::subtotal();
        
       return view('testcartview',  compact('totalcart', 'totalprice'));
    }
    
    public function addQtCart($id)
    {
        $item = Cart::get($id);
        
        Cart::update($id, $item->qty + 1);
        
        return redirect()->back();
    }


    public function removeCart($id)
    {
        $item = Cart::get($id);
        
        Cart::update($id, $item->qty - 1);
        
        return redirect()->back();
        
    }
    
    
    public function removethisCart($id)
    {
         Cart::remove($id);
        
        return redirect()->back();
        
    }

    public function removeallCart()
    {
        Cart::destroy();
        
        return redirect()->route('store-front');
    }
    // End test cart
}
