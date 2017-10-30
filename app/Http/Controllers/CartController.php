<?php

namespace MarketPlex\Http\Controllers;

use Illuminate\Http\Request;
use MarketPlex\MarketProduct;
use Cart;
use Validator;
use Mail;
use MarketPlex\Product;
use MarketPlex\Mail\Order;
use MarketPlex\Mail\OrderPlacement;

class CartController extends Controller
{
    // Test Cart Functions
    public function addCart($id)
    {
        $products = MarketProduct::find($id);
        
        $result = Cart::add([
            'id' => $id,
            'name' => $products->title,
            'qty' => 1,
            'price' => $products->mrp(),
            'options' => [
                'image' => $products->thumbnail(),
                'available_quantity' => $products->product->available_quantity
                ]
            ]);

        
        return redirect()->back();
        
        
    }
    public function showCart()
    {
        
       $totalcart = Cart::content();
        
       $totalprice = Cart::subtotal();
    
       if(Cart::count() == 0){
            return redirect()->route('store-front');
        }
        
       return view('testcartview',  compact('totalcart', 'totalprice'));
    }
    
    public function addQtCart($id)
    {
        $item = Cart::get($id);
        
        $products = MarketProduct::find($item->id);
        
        $available_quantity = $products->product->available_quantity;
        
        // Checking cart quantity with available item quantity
        if($item->qty < $available_quantity){
          Cart::update($id, $item->qty + 1);  
        }
        return redirect()->back();
    }


    public function decreaseCart($id)
    {
        $item = Cart::get($id);
        
        Cart::update($id, $item->qty - 1);
        
        if(Cart::count() > 0){
            return redirect()->back();
        }
        
        return redirect()->route('store-front');
        
    }
    
    
    public function removeItem($id)
    {
        Cart::remove($id);
        
        if(Cart::count() > 0){
            return redirect()->back();
        }
        
        return redirect()->route('store-front');
        
    }

    public function removeCart()
    {
        Cart::destroy();
        
        return redirect()->route('store-front');
    }
    // End test cart
    
    // Check out    
    public function checkoutCart()
    {
        $allcheckout = Cart::content();
        
        $totalcheckout = Cart::subtotal();
        
        return view('testcheckout', compact('allcheckout', 'totalcheckout'));
    }    
    // Confirm Order    
    public function confirmCart(Request $request)
    {
        $this->validate($request, [
            'country' => 'min:3|max:255',
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip_code' => 'nullable',
            'phone_number' => 'required',
            'email_address' => 'required|email',
        ]);
        
        // $input = array($request->all());
        
        // return $allcheckout = Cart::content();
        
        // $totalcheckout = Cart::subtotal();
        
        $data = [
            'user' => $request->all(),
            'order' => [
                'order_items' => Cart::content(),
                'total_price' => Cart::subtotal(),
                ],
            ];
        
        Mail::to($request->input('email_address'))->send(new Order($data));
        
        Mail::to(env('MAIL_ADMIN'))->send(new OrderPlacement($data));
        
        Cart::destroy();
        
        return redirect()->route('store-front');
    }
}
