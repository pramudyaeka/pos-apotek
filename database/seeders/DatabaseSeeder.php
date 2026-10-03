<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner=User::updateOrCreate(['email'=>'owner@apotek.com'],['name'=>'Apotek Owner','password'=>Hash::make('password'),'role'=>'Owner','status'=>'Active']);
        $categories=['Vitamin','Antibiotic','Allergy','Cold & Flu','Digestive','Antiviral','Pain Relief'];
        foreach($categories as $name) Category::firstOrCreate(['name'=>$name],['is_active'=>true]);
        $categoryIds=Category::pluck('id','name');
        $products=[
            ['name'=>'Paracetamol','category'=>'Pain Relief','price'=>5000,'stock'=>30,'min_stock'=>10,'unit'=>'Tablet'],
            ['name'=>'Vitamin C','category'=>'Vitamin','price'=>12000,'stock'=>15,'min_stock'=>5,'unit'=>'Tablet'],
            ['name'=>'Obat Flu','category'=>'Cold & Flu','price'=>24000,'stock'=>8,'min_stock'=>5,'unit'=>'Strip'],
            ['name'=>'Omeprazole','category'=>'Digestive','price'=>17000,'stock'=>20,'min_stock'=>5,'unit'=>'Capsule'],
            ['name'=>'Acyclovir','category'=>'Antiviral','price'=>15000,'stock'=>10,'min_stock'=>5,'unit'=>'Tablet'],
        ];
        foreach($products as $p) Product::updateOrCreate(['name'=>$p['name']],['category_id'=>$categoryIds[$p['category']],'unit'=>$p['unit'],'price'=>$p['price'],'stock'=>$p['stock'],'min_stock'=>$p['min_stock'],'is_active'=>true]);
        $this->command?->info('Owner: owner@apotek.com / password');
    }
}
