<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use MarketPlex\MarketProduct;
use Cart;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }  
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicCart()
    {
        $products = MarketProduct::find(1);
        
        $result = Cart::add([
                    'id' => 1,
                    'name' => $products->title,
                    'qty' => 1,
                    'price' => $products->mrp(),
                    'options' => [
                        'image' => $products->thumbnail(),
                        'available_quantity' => $products->product->available_quantity
                        ]
                    ]);
                    
        $rowid = $result->rowId;
        
        $item = Cart::get($rowid);
        
        $product = MarketProduct::find($item->id);
        
        $available_quantity = $products->product->available_quantity;
        
        
        
        $response = $this->get('/addqt/{id}');
                         
        $this->assertTrue($item->qty < $available_quantity);
                         
        
    }
}
