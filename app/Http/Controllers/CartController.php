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
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Test Cart Functions
    public function addCart($id)
    {
        $products = MarketProduct::find($id);

        $product_quantity = $products->product->available_quantity;
        // get cart mathced with product id 
        $totalcart = Cart::content()->where('id', $id);
        
        if(count($totalcart) > 0){

            foreach($totalcart as $cartitem) {

              $cart_quantity = $cartitem->qty;
                // compare available item quantity before add to cart  
              if($product_quantity > $cart_quantity){
                  
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
                    
                Session::flash('alert-success', 'Product added to the cart!');
                return redirect()->back();
                  
              }
              else {
                  
                    Session::flash('alert-danger', 'Product quantity is not available!');
                    return redirect()->back();
                  
                }

            }
        }
        
        else{
            
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
                    Session::flash('alert-success', 'Product added to the cart!');
                    return redirect()->back();
            
        }

    }
    public function showCart()
    {
        
       $totalcart = Cart::content();
        
       $totalprice = Cart::subtotal();
       
       $products = Product::all();
       
       if(Cart::count() == 0){
            return redirect()->route('store-front');
        }
        
       return view('cart-view',  compact('totalcart', 'totalprice', 'products'));
    }
    
    public function addQtCart($id)
    {
        $item = Cart::get($id);
        
        $products = MarketProduct::find($item->id);
        
        $available_quantity = $products->product->available_quantity;
        
        // Checking cart quantity with available item quantity in DB
        if($item->qty < $available_quantity){
          Cart::update($id, $item->qty + 1);
          return redirect()->back();
        }
        else{
            Session::flash('alert-danger', 'Product quantity is not available!');
            return redirect()->back();
        }

    }

    public function decreaseCart($id)
    {
        $item = Cart::get($id);
        
        Cart::update($id, $item->qty - 1);
        
        if(Cart::count() > 0){
            return redirect()->back();
        }
        Session::flash('alert-warning', 'All Cart removed!');
        return redirect()->route('store-front');
        
    }
    
    
    public function removeItem($id)
    {
        Cart::remove($id);
        
        if(Cart::count() > 0){
            Session::flash('alert-warning', 'Cart removed!');
            return redirect()->back();
        }
        Session::flash('alert-warning', 'All Cart removed!');
        return redirect()->route('store-front');
        
    }

    public function removeCart()
    {
        Cart::destroy();
        Session::flash('alert-danger', 'All Cart removed!');
        return redirect()->route('store-front');
    }
    // End test cart
    
    // Check out    
    public function checkoutCart()
    {
        $allcheckout = Cart::content();
        
        $totalcheckout = Cart::subtotal();
        
        return view('cart-checkout', compact('allcheckout', 'totalcheckout'));
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
        
        Session::flash('alert-info', 'Order Confirm! Please check your email!');
        
        return redirect()->route('store-front');
    }
}
