<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            [
                'id'    => 1,
                'name'  => 'Quần áo',
                'slug'  => 'quan-ao',
                'user_id' => 1,
            ],
            [
                'id'    => 2,
                'name'  => 'Sách vở',
                'slug'  => 'sach-vo',
                'user_id' => 1,
            ],
            [
                'id'    => 3,
                'name'  => 'Đồ dùng phụ kiện',
                'slug'  => 'do-dung-phu-kien',
                'user_id' => 1,
            ],
            [
                'id'    => 4,
                'name'  => 'Giày dép',
                'slug'  => 'giay-dep',
                'user_id' => 1,
            ],
            [
                'id'    => 5,
                'name'  => 'Đồ dùng học tập',
                'slug'  => 'do-dung-hoc-tap',
                'user_id' => 1,
            ],
            [
                'id'    => 6,
                'name'  => 'Khác',
                'slug'  => 'khac',
                'user_id' => 1,
            ],
        ]);
        $categories->each(function ($category) {
            Category::insert($category);
        });
    }
}
