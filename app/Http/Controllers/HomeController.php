<?php

namespace MarketPlex\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use MarketPlex\User;

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
