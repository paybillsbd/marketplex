<?php

namespace MarketPlex\Http\Controllers\Auth;

use MarketPlex\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use MarketPlex\User;
use MarketPlex\Product;
use MarketPlex\Category;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showStoreFront()
    {
        if(env('STORE_CLOSE', true) === true)
            return view('store-comingsoon');
        else if(Product::count() == 0)
            return view('store-comingsoon');

        $user = User::whereEmail(config('mail.admin.address'))->first();
        if(!$user)
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
