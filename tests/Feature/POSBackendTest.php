<?php

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an owner to sign in and access protected pages', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active','password'=>'password']);

    $this->post(route('login.store'),['username'=>$owner->username,'password'=>'password'])
        ->assertRedirect(route('dashboard'));

    $this->actingAs($owner)->get(route('product'))->assertOk();
});

it('creates a sale and decrements stock atomically', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active']);
    $category=Category::create(['name'=>'Pain Relief','is_active'=>true]);
    $product=Product::create(['category_id'=>$category->id,'name'=>'Paracetamol','unit'=>'Tablet','price'=>5000,'stock'=>10,'min_stock'=>2,'is_active'=>true]);

    $response=$this->actingAs($owner)->postJson(route('sales.store'),[
        'payment_method'=>'Cash',
        'amount_received'=>100000,
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
        'amount_received'=>100000,
        'items'=>[['product_id'=>$product->id,'quantity'=>3]],
    ])->assertStatus(422);

    expect($product->fresh()->stock)->toBe(2);
    $this->assertDatabaseCount('sales',0);
});

it('rejects unsupported payment methods', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active']);
    $category=Category::create(['name'=>'Vitamin','is_active'=>true]);
    $product=Product::create(['category_id'=>$category->id,'name'=>'Vitamin C','unit'=>'Tablet','price'=>10000,'stock'=>5,'min_stock'=>1,'is_active'=>true]);

    $this->actingAs($owner)->postJson(route('sales.store'),[
        'payment_method'=>'Crypto',
        'items'=>[['product_id'=>$product->id,'quantity'=>1]],
    ])->assertStatus(422);
});

it('does not allow products to use inactive categories', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active']);
    $category=Category::create(['name'=>'Inactive','is_active'=>false]);

    $this->actingAs($owner)->postJson(route('product.store'),[
        'name'=>'Test Product',
        'category_id'=>$category->id,
        'unit'=>'Tablet',
        'price'=>1000,
        'stock'=>1,
        'min_stock'=>1,
        'is_active'=>true,
    ])->assertStatus(422);
});

it('serves reporting and receipt pages for an owner', function () {
    $owner=User::factory()->create(['role'=>'Owner','status'=>'Active']);
    $this->actingAs($owner)->get(route('reporting'))->assertOk();
    $category=Category::create(['name'=>'Pain Relief','is_active'=>true]);
    $product=Product::create(['category_id'=>$category->id,'name'=>'Paracetamol','unit'=>'Tablet','price'=>5000,'stock'=>5,'min_stock'=>1,'is_active'=>true]);
    $response=$this->actingAs($owner)->postJson(route('sales.store'),['payment_method'=>'Cash','amount_received'=>100000,'items'=>[['product_id'=>$product->id,'quantity'=>1]]])->assertCreated();
    $saleId=$response->json('id');
    $this->actingAs($owner)->get(route('transaction.receipt',$saleId))->assertOk();
});

it('aggregates duplicate product lines before checking stock', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $category = Category::create(['name' => 'Digestive', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Antacid',
        'unit' => 'Tablet',
        'price' => 3000,
        'stock' => 3,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($owner)->postJson(route('sales.store'), [
        'payment_method' => 'Cash',
        'amount_received' => 100000,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2],
            ['product_id' => $product->id, 'quantity' => 2],
        ],
    ])->assertStatus(422);

    expect($product->fresh()->stock)->toBe(3);
    $this->assertDatabaseCount('sales', 0);
});

it('allows owner and cashier to access history', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);

    $this->actingAs($owner)->get(route('history'))->assertOk();
    $this->actingAs($cashier)->get(route('history'))->assertOk();
});

it('records sales in activity history', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $category = Category::create(['name' => 'Antiviral', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Test Medicine',
        'unit' => 'Tablet',
        'price' => 7000,
        'stock' => 5,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($owner)->postJson(route('sales.store'), [
        'payment_method' => 'QRIS',
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $owner->id,
        'module' => 'Sale',
        'action' => 'sale',
    ]);
});

it('renders the owner dashboard with all expected inventory and sales data', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);

    $this->actingAs($owner)->get(route('dashboard'))->assertOk();
});

it('renders inventory and user management pages with their expected data', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);

    $this->actingAs($owner)->get(route('category'))->assertOk();
    $this->actingAs($owner)->get(route('product'))->assertOk();
    $this->actingAs($owner)->get(route('user-management'))->assertOk();
});

it('blocks cashier access to owner-only management and reporting pages', function () {
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);

    $this->actingAs($cashier)->get(route('dashboard'))->assertForbidden();
    $this->actingAs($cashier)->get(route('user-management'))->assertForbidden();
    $this->actingAs($cashier)->get(route('reporting'))->assertForbidden();
    $this->actingAs($cashier)->get(route('transaction'))->assertForbidden();
});

it('rejects inactive accounts during login', function () {
    $cashier = User::factory()->create([
        'role' => 'Cashier',
        'status' => 'Inactive',
        'password' => 'password',
    ]);

    $this->from(route('login'))->post(route('login.store'), [
        'email' => $cashier->email,
        'password' => 'password',
    ])->assertRedirect(route('login'))
        ->assertSessionHasErrors('username');

    expect(auth()->check())->toBeFalse();
});

it('filters activity history by the stored backend module values', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);

    ActivityLog::record($owner, 'Product', 'create', 'Membuat produk "Paracetamol".');
    ActivityLog::record($owner, 'User', 'create', 'Membuat akun Kasir.');

    $this->actingAs($owner)
        ->get(route('history', ['module' => 'Product']))
        ->assertOk()
        ->assertSee('Membuat produk "Paracetamol".')
        ->assertDontSee('Membuat akun Kasir.');
});

it('does not record a login in activity history', function () {
    $owner = User::factory()->create([
        'role' => 'Owner',
        'status' => 'Active',
        'password' => 'password',
    ]);

    $this->post(route('login.store'), [
        'email' => $owner->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertDatabaseMissing('activity_logs', [
        'user_id' => $owner->id,
        'module' => 'Authentication',
        'action' => 'login',
    ]);
});

it('limits cashier history to the cashier own activity and stock movements', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);
    $category = Category::create(['name' => 'Test Category', 'is_active' => true]);
    $ownerProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Owner Product',
        'unit' => 'Tablet',
        'price' => 5000,
        'stock' => 10,
        'min_stock' => 1,
        'is_active' => true,
    ]);
    $cashierProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Cashier Product',
        'unit' => 'Tablet',
        'price' => 5000,
        'stock' => 10,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    ActivityLog::record($owner, 'Product', 'create', 'Aktivitas milik Owner.');
    ActivityLog::record($cashier, 'Product', 'update', 'Aktivitas milik Cashier.');

    StockMovement::create([
        'product_id' => $ownerProduct->id,
        'user_id' => $owner->id,
        'type' => 'IN',
        'quantity' => 5,
        'stock_before' => 5,
        'stock_after' => 10,
        'note' => 'Stok Owner',
    ]);
    StockMovement::create([
        'product_id' => $cashierProduct->id,
        'user_id' => $cashier->id,
        'type' => 'OUT',
        'quantity' => -1,
        'stock_before' => 10,
        'stock_after' => 9,
        'note' => 'Stok Cashier',
    ]);

    $this->actingAs($cashier)
        ->get(route('history'))
        ->assertOk()
        ->assertSee('Aktivitas milik Cashier.')
        ->assertDontSee('Aktivitas milik Owner.')
        ->assertDontSee('Owner Product')
        ->assertSee('Cashier Product');
});


it('allows cashier to view inventory and adjust stock but not manage master data', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);
    $category = Category::create(['name' => 'Stock Test', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Stock Product',
        'unit' => 'Tablet',
        'price' => 5000,
        'stock' => 10,
        'min_stock' => 2,
        'is_active' => true,
    ]);

    $this->actingAs($cashier)->get(route('product'))->assertOk();
    $this->actingAs($cashier)->get(route('category'))->assertOk();

    $this->actingAs($cashier)->postJson(route('product.store'), [
        'name' => 'Unauthorized Product',
        'category_id' => $category->id,
        'unit' => 'Tablet',
        'price' => 5000,
        'stock' => 1,
        'min_stock' => 1,
        'is_active' => true,
    ])->assertForbidden();

    $this->actingAs($cashier)->postJson(route('category.store'), [
        'name' => 'Unauthorized Category',
        'is_active' => true,
    ])->assertForbidden();

    $this->actingAs($cashier)->putJson(route('product.update', $product), [
        'stock' => 7,
    ])->assertOk();

    expect($product->fresh()->stock)->toBe(7);
    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $product->id,
        'user_id' => $cashier->id,
        'type' => 'OUT',
        'quantity' => -3,
    ]);

    $this->actingAs($cashier)->putJson(route('category.update', $category), [
        'name' => 'Changed Category',
        'is_active' => true,
    ])->assertForbidden();
});

it('blocks deleting a user who already has sales and recommends deactivation', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);
    $category = Category::create(['name' => 'Delete Test', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Delete Product',
        'unit' => 'Tablet',
        'price' => 5000,
        'stock' => 5,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($cashier)->postJson(route('sales.store'), [
        'payment_method' => 'Cash',
        'amount_received' => 10000,
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated();

    $this->actingAs($owner)->deleteJson(route('user.destroy', $cashier))
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'Akun ini sudah memiliki transaksi. Nonaktifkan akun agar riwayat transaksi tetap tersimpan.']);

    expect($cashier->fresh())->not->toBeNull();
});

it('persists cash received and change amounts on a sale', function () {
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);
    $category = Category::create(['name' => 'Payment Test', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Payment Product',
        'unit' => 'Tablet',
        'price' => 7000,
        'stock' => 5,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    $response = $this->actingAs($cashier)->postJson(route('sales.store'), [
        'payment_method' => 'Cash',
        'amount_received' => 10000,
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated();

    $saleId = $response->json('id');
    $this->assertDatabaseHas('sales', [
        'id' => $saleId,
        'amount_received' => 10000,
        'change_amount' => 3000,
    ]);

    $response->assertJsonPath('change_amount', '3000.00');
});

it('limits cashier receipt access to their own sale', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $cashier = User::factory()->create(['role' => 'Cashier', 'status' => 'Active']);
    $category = Category::create(['name' => 'Receipt Test', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Receipt Product',
        'unit' => 'Tablet',
        'price' => 6000,
        'stock' => 5,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    $ownerSale = $this->actingAs($owner)->postJson(route('sales.store'), [
        'payment_method' => 'Cash',
        'amount_received' => 10000,
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated()->json('id');

    $cashierSale = $this->actingAs($cashier)->postJson(route('sales.store'), [
        'payment_method' => 'Cash',
        'amount_received' => 10000,
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated()->json('id');

    $this->actingAs($cashier)->get(route('transaction.receipt', $cashierSale))->assertOk();
    $this->actingAs($cashier)->get(route('transaction.receipt', $ownerSale))->assertForbidden();
});

it('filters transaction history on the server before pagination', function () {
    $owner = User::factory()->create(['role' => 'Owner', 'status' => 'Active']);
    $category = Category::create(['name' => 'Filter Test', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Filter Product',
        'unit' => 'Tablet',
        'price' => 5000,
        'stock' => 20,
        'min_stock' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($owner)->postJson(route('sales.store'), [
        'payment_method' => 'Cash',
        'amount_received' => 10000,
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated();
    $cashInvoice = Sale::where('payment_method', 'Cash')->latest('id')->value('invoice_number');

    $this->actingAs($owner)->postJson(route('sales.store'), [
        'payment_method' => 'QRIS',
        'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ])->assertCreated();
    $qrisInvoice = Sale::where('payment_method', 'QRIS')->latest('id')->value('invoice_number');

    $this->actingAs($owner)
        ->get(route('transaction', ['payment_method' => 'QRIS']))
        ->assertOk()
        ->assertSee($qrisInvoice)
        ->assertDontSee($cashInvoice);
});
