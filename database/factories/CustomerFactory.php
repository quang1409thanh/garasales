<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected static $currentIndex = 0; // Định nghĩa thuộc tính tĩnh

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specificNames = [
            'admin_garasale',
        ];

        // Lấy tên theo thứ tự, và quay lại đầu danh sách nếu vượt quá số lượng
        $name = $specificNames[self::$currentIndex % count($specificNames)];

        // Tăng chỉ số cho lần tạo tiếp theo
        self::$currentIndex++;

        return [
            "user_id" => 1,
            "uuid" => Str::uuid(),
            'name' => $name, // Sử dụng tên theo thứ tự
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'account_holder' => fake()->name(),
            'account_number' => fake()->randomNumber(8, true),
            'bank_name' => fake()->randomElement([
                'Agribank',
                'BIDV',
                'Vietcombank',
                'VietinBank',
                'Techcombank',
                'Sacombank',
                'Eximbank',
                'MB Bank',
                'ACB',
                'VPBank',
                'SHB',
                'OceanBank',
                'HDBank',
                'Kiên Long Bank',
                'NAB Bank',
                'TPBank'
            ]),
            'photo' => 'https://storage.googleapis.com/garasales/default.jpg'
        ];
    }
}
