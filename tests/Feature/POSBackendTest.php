<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an owner to sign in and access protected pages', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active','password'=>'password']);

    $this->post(route('login.store'),['email'=>$owner->email,'password'=>'password'])
        ->assertRedirect(route('dashboard'));

    $this->actingAs($owner)->get(route('product'))->assertOk();
});

it('creates a sale and decrements stock atomically', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active']);
    $category=Category::create(['name'=>'Pain Relief','is_active'=>true]);
    $product=Product::create(['category_id'=>$category->id,'name'=>'Paracetamol','unit'=>'Tablet','price'=>5000,'stock'=>10,'min_stock'=>2,'is_active'=>true]);

    $response=$this->actingAs($owner)->postJson(route('sales.store'),[
        'payment_method'=>'Cash',
        'items'=>[['product_id'=>$product->id,'quantity'=>3]],
    ]);

    $response->assertCreated()->assertJsonPath('status','Success');
    expect($product->fresh()->stock)->toBe(7);
    $this->assertDatabaseHas('sale_items',['product_id'=>$product->id,'quantity'=>3]);
    $this->assertDatabaseHas('stock_movements',['product_id'=>$product->id,'type'=>'OUT','quantity'=>-3]);
});

it('rejects a sale when stock is insufficient', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active']);
    $category=Category::create(['name'=>'Vitamin','is_active'=>true]);
    $product=Product::create(['category_id'=>$category->id,'name'=>'Vitamin C','unit'=>'Tablet','price'=>10000,'stock'=>2,'min_stock'=>1,'is_active'=>true]);

    $this->actingAs($owner)->postJson(route('sales.store'),[
        'payment_method'=>'Cash',
        'items'=>[['product_id'=>$product->id,'quantity'=>3]],
    ])->assertStatus(422);

    expect($product->fresh()->stock)->toBe(2);
    $this->assertDatabaseCount('sales',0);
});
