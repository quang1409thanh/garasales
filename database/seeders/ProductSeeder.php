<?php

namespace Database\Seeders;

use App\Models\Product;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = collect([
            [
                'name' => 'Áo thun nam',
                'slug' => 'ao-thun-nam',
                'code' => 'PC0000001',
                'quantity' => 1,
                'buying_price' => 100000,
                'selling_price' => 150000,
                'quantity_alert' => 0,
                'tax' => 0,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 1, // Quần áo
                'unit_id' => 3,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'https://storage.googleapis.com/garasales/default.jpg',
                'supplier_id' => 1,
                'thumbnail_url' => 'https://storage.googleapis.com/garasales/thumbnail.jpg'

            ],
            [
                'name' => 'Sách giáo khoa toán',
                'slug' => 'sach-giao-khoa-toan',
                'code' => 'PC0000002',
                'quantity' => 3,
                'buying_price' => 50000,
                'selling_price' => 80000,
                'quantity_alert' => 0,
                'tax' => 0,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 2, // Sách vở
                'unit_id' => 3,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'https://storage.googleapis.com/garasales/default.jpg',
                'supplier_id' => 2,
                'thumbnail_url' => 'https://storage.googleapis.com/garasales/thumbnail.jpg'

            ],
            [
                'name' => 'Ba lô học sinh',
                'slug' => 'ba-lo-hoc-sinh',
                'code' => 'PC0000003',
                'quantity' => 4,
                'buying_price' => 200000,
                'selling_price' => 300000,
                'quantity_alert' => 0,
                'tax' => 0,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 3, // Đồ dùng phụ kiện
                'unit_id' => 3,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'https://storage.googleapis.com/garasales/default.jpg',
                'supplier_id' => 3,
                'thumbnail_url' => 'https://storage.googleapis.com/garasales/thumbnail.jpg'
            ],
        ]);

        $products->each(function ($product){
            Product::create($product);
        });
    }
}
