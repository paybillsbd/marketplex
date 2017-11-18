<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Cart;
use MarketPlex\MarketProduct;

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
        $products = factory(App\MarketProduct::class)->create([
        'name' => $faker->unique()->safeEmail,
        'title' => $faker->name,
        'price' => $faker->randomNumber(2),
        'manufacturer_name' => str_random(10),
    ]);
                
        $product = [
                    'id' => 1,
                    'name' => "Product 1",
                    'qty' => 5,
                    'price' => 100,
                    'options' => [
                          'available_quantity' => 5
                        ]
                    ];
        
        $result = Cart::add($product);
                    
        $rowid = $result->rowId;
        
        $item = Cart::get($rowid);
        
        $response = $this->get('/addqt/{id}');
                         
        $this->assertTrue($item->qty <= $product['options']['available_quantity']);
                         
        
    }
}
