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
                'code' => 001,
                'quantity' => 15,
                'buying_price' => 100,
                'selling_price' => 150,
                'quantity_alert' => 5,
                'tax' => 10,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 1, // Quần áo
                'unit_id' => 1,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'assets/img/products/ao-thun-nam.png',
                'supplier_id' => 1

            ],
            [
                'name' => 'Sách giáo khoa toán',
                'slug' => 'sach-giao-khoa-toan',
                'code' => 002,
                'quantity' => 20,
                'buying_price' => 50,
                'selling_price' => 80,
                'quantity_alert' => 10,
                'tax' => 10,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 2, // Sách vở
                'unit_id' => 1,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'assets/img/products/sach-giao-khoa-toan.png',
                'supplier_id' => 2

            ],
            [
                'name' => 'Ba lô học sinh',
                'slug' => 'ba-lo-hoc-sinh',
                'code' => 003,
                'quantity' => 10,
                'buying_price' => 200,
                'selling_price' => 300,
                'quantity_alert' => 5,
                'tax' => 10,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 3, // Đồ dùng phụ kiện
                'unit_id' => 1,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'assets/img/products/ba-lo-hoc-sinh.png',
                'supplier_id' => 3
            ],
            [
                'name' => 'Tai nghe Bluetooth',
                'slug' => 'tai-nghe-bluetooth',
                'code' => 004,
                'quantity' => 12,
                'buying_price' => 300,
                'selling_price' => 450,
                'quantity_alert' => 5,
                'tax' => 10,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 4, // Đồ điện tử
                'unit_id' => 1,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'assets/img/products/tai-nghe-bluetooth.png',
                'supplier_id' => 4

            ],
            [
                'name' => 'Son môi Lì',
                'slug' => 'son-moi-li',
                'code' => 005,
                'quantity' => 25,
                'buying_price' => 100,
                'selling_price' => 150,
                'quantity_alert' => 10,
                'tax' => 10,
                'tax_type' => 1,
                'notes' => null,
                'category_id' => 5, // Mỹ phẩm
                'unit_id' => 1,
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'product_image' => 'assets/img/products/son-moi-li.png',
                'supplier_id' => 1

            ]
        ]);

        $products->each(function ($product){
            Product::create($product);
        });
    }
}
