<?php

namespace MarketPlex\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function showStoreFront()
    {
        if(env('STORE_CLOSE', true) === true)
            return view('store-comingsoon');
        else if(Product::count() == 0)
            return view('store-comingsoon');

        $user = User::whereEmail(config('mail.admin.address'))->first();
        $paginatedProducts = $user->products()->paginate(4);
        // $paginatedProducts->setPath('showcase');
        return view('store-front-1',  
                [
                    'categories' => Category::all(),
                    'carousels' => [ 'Slogan0', 'Slogan1', 'Slogan2' ]

                ])->withPaginatedProducts($paginatedProducts);
    }

    // Find who is authenticated currently
    public function user(Request $request)
    {
        if( $request->ajax() )
        {
            $success = true;
            $user = Auth::user();
            if(Auth::guest())
            {
                $success = false;
                $message = 'User not authenticated. Redirecting to home ...';
                return response()->json(compact('success', 'message', 'user'));    
            }
            return response()->json(compact('success', 'message', 'user'));
        }
        return redirect()->route('guest::home');
    }
}
