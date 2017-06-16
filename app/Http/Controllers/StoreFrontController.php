<?php

namespace MarketPlex\Http\Controllers;

use Illuminate\Http\Request;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\MarketProduct;
use MarketPlex\Category;
use Auth;
use MarketPlex\Mailers\ActionMailer;

class StoreFrontController extends Controller
{
    //
    public function showStoreFront(Request $request, ActionMailer $mailer)
    {
        if(env('STORE_CLOSE', true) === true)
            return view('store-comingsoon');
        else if(Product::count() == 0)
            return view('store-comingsoon');

        $user = User::whereEmail(config('mail.admin.address'))->first();
        
        // Developer debug access
        if(!Auth::guest() && (Auth::user()->isDeveloper() || Auth::user()->isGuest()))
            $user = Auth::user();

        if(!$user || $user->hasNoProduct())
            return view('store-comingsoon');

        $mailer->report($request);

        $marketProducts = MarketProduct::UserProducts($user);
        $category = null;
        if(session()->has('category'))
        {
            $category = session('category');
            $marketProducts = MarketProduct::UserProducts($user)->Categorized($category);
        }
        $viewData = [
            'active_category' => $category ? $category->id : Category::first()->id,
        ];
        return view('store-front-1', $viewData)->withPaginatedProducts($marketProducts->paginate(6))
                                               ->withCategories(Category::all()->pluck('name', 'id'));
    }

    public function filterCategory(Category $category)
    {
        return redirect()->route('store-front')->withCategory($category);
    }
}
