<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId("user_id")->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('code');
            //$table->string('product_barcode_symbology')->nullable();
            $table->integer('quantity');
            $table->integer('buying_price')->comment('Return Price (Price at which the product will be bought back)');
            $table->integer('selling_price')->comment('Selling Price');
            $table->integer('quantity_alert');
            $table->integer('tax')->nullable();
            $table->tinyInteger('tax_type')->nullable();
            $table->text('notes')->nullable();
            // Thêm trường product_sold và fee
            $table->integer('product_sold')->default(0)->comment('Number of products sold');
            $table->integer('fee')->nullable()->comment('Fee associated with the product');

            $table->string('product_image')->nullable(); // Hình ảnh gốc
            $table->string('thumbnail_url')->nullable(); // Thêm trường thumbnail_url

            // Liên kết với bảng Category
            $table->foreignIdFor(\App\Models\Category::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Liên kết với bảng Unit
            $table->foreignIdFor(\App\Models\Unit::class)->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
            // Thêm khóa ngoại supplier_id liên kết với bảng suppliers
            $table->foreignIdFor(\App\Models\Supplier::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
