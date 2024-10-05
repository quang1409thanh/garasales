<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ClientController extends Controller
{
    //
    // Hiển thị danh sách sản phẩm
    public function productsIndex()
    {
        $products = Product::all(); // Hoặc áp dụng phân trang nếu cần
        return view('client.products.index', [
            'products' => $products,
        ]);

    }

    public function view_bill($uuid)
    {

        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        return view('client.suppliers.client_bill', compact('supplier'));
    }

    // Hiển thị chi tiết sản phẩm
    public function productsShow($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->code, $generator::TYPE_CODE_128);

        return view('client.products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }


    // Hiển thị danh sách nhà cung cấp
    public function suppliersIndex()
    {
        $suppliers = Supplier::all(); // Hoặc áp dụng phân trang nếu cần
        return view('client.suppliers.index', compact('suppliers'));
    }

    public function categoryIndex()
    {
        $categories = Category::all();
        return view('client.categories.index',compact('categories'));
    }

    // Hiển thị chi tiết nhà cung cấp
    public function suppliersShow($uuid)
    {
        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();
        return view('client.suppliers.show', compact('supplier'));
    }

    public function supplierProductsIndex($uuid)
    {

        // Tìm nhà cung cấp theo UUID
        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        // Lấy danh sách sản phẩm của nhà cung cấp
        $products = Product::where('supplier_id', $supplier->id)->get();

        // Trả về view với danh sách sản phẩm và nhà cung cấp
        return view('client.suppliers.show_products', compact('supplier', 'products'));
    }

    public function categoryProductsIndex($slug)
    {
        // Lấy category dựa trên slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Lấy sản phẩm theo category
        // Bạn có thể sử dụng paginate() nếu muốn phân trang
        $products = Product::where('category_id', $category->id)->paginate(10);

        // Trả về view với dữ liệu category và sản phẩm
        return view('client.categories.show', compact('category', 'products'));
    }

}
