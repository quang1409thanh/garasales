<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Str;

class SoldController extends Controller
{
    public function index()
    {
        // Chỉ lấy tên và ID của nhà cung cấp
        $products = Product::where("user_id", auth()->id())
            ->where("product_sold", '>', 0)
            ->with(['supplier:id,name']) // Eager load chỉ các trường cần thiết
            ->get();
        return view('sold.index', [
            'products' => $products,
        ]);
    }
}
