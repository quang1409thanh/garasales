<?php

namespace App\Models;

use App\Enums\SupplierType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'shopname',
        'type',
        'photo',
        'account_holder',
        'account_number',
        'bank_name',
        'user_id',
        'uuid',
        'payment_status', // Thêm trường payment_status
        'bill_image',     // Thêm trường bill_image
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'type' => SupplierType::class,
        'payment_status' => 'string', // Thêm để chuyển đổi payment_status
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('email', 'like', "%{$value}%")
            ->orWhere('phone', 'like', "%{$value}%")
            ->orWhere('shopname', 'like', "%{$value}%")
            ->orWhere('type', 'like', "%{$value}%");
    }

    public function products()
    {
        return $this->hasMany(Product::class); // Quan hệ 1-n với sản phẩm
    }

    /**
     * Get the user that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Hàm tính số lượng sản phẩm đã bán

    public function soldProductsCount()
    {
        return $this->products()->where('product_sold', '>', 0)->sum('product_sold');
    }

    public function getTotalSellingPriceAttribute()
    {
        // Lấy tất cả sản phẩm có product_sold > 0
        $products = $this->products()->where('product_sold', '>', 0)->get();

        // Tính tổng giá bán
        return $products->sum(function ($product) {
            return $product->selling_price * $product->product_sold;
        });
    }

    public function getTotalReturnPriceAttribute()
    {
        // Lấy tất cả sản phẩm có product_sold > 0
        $products = $this->products()->where('product_sold', '>', 0)->get();

        // Tính tổng giá trả lại
        return $products->sum(function ($product) {
            return $product->buying_price * $product->product_sold; // Đảm bảo rằng trường buying_price tồn tại
        });
    }

    public function getProfitAttribute()
    {
        return $this->total_selling_price - $this->total_return_price; // Lợi nhuận
    }
}
