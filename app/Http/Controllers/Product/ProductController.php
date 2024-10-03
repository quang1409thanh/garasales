<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Str;

class ProductController extends Controller
{
    public function index()
    {
        // Chỉ lấy tên và ID của nhà cung cấp
        $products = Product::where("user_id", auth()->id())
            ->with(['supplier:id,name']) // Eager load chỉ các trường cần thiết
            ->get();

        return view('products.index', [
            'products' => $products,
        ]);
    }
    public function create(Request $request)
    {
        $categories = Category::where("user_id", auth()->id())->get(['id', 'name']);
        $units = Unit::where("user_id", auth()->id())->get(['id', 'name']);
        $suppliers = Supplier::all(); // Lấy danh sách tất cả nhà cung cấp từ DB
        if ($request->has('category')) {
            $categories = Category::where("user_id", auth()->id())->whereSlug($request->get('category'))->get();
        }
        // Lấy giá trị supplier từ request
        $supplier_id = $request->query('supplier'); // hoặc $request->supplier


        if ($request->has('unit')) {
            $units = Unit::where("user_id", auth()->id())->whereSlug($request->get('unit'))->get();
        }

        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
            'suppliers' => $suppliers,
            'supplier_id' => $supplier_id,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        /**
         * Handle upload image
         */
        $image = "";
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image')->store('products', 'public');
        }

        Product::create([
            "code" => IdGenerator::generate([
                'table' => 'products',
                'field' => 'code',
                'length' => 4,
                'prefix' => 'PC'
            ]),

            'product_image'     => $image,
            'name'              => $request->name,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'quantity'          => $request->quantity,
            'buying_price'      => $request->buying_price,
            'selling_price'     => $request->selling_price,
            'quantity_alert'    => $request->quantity_alert,
            'tax'               => $request->tax,
            'tax_type'          => $request->tax_type,
            'notes'             => $request->notes,
            "user_id" => auth()->id(),
            "slug" => Str::slug($request->name, '-'),
            "uuid" => Str::uuid(),
            'supplier_id'       => $request->supplier_id,  // Thêm dòng này để lưu supplier_id
        ]);


        return to_route('products.index')->with('success', 'Product has been created!');
    }

    public function show($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->code, $generator::TYPE_CODE_128);

        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    public function edit($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        $suppliers = Supplier::all(); // Lấy danh sách tất cả nhà cung cấp từ DB

        return view('products.edit', [
            'categories' => Category::where("user_id", auth()->id())->get(),
            'units' => Unit::where("user_id", auth()->id())->get(),
            'product' => $product,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(UpdateProductRequest $request, $uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        $product->update($request->except('product_image'));

        $image = $product->product_image;
        if ($request->hasFile('product_image')) {

            if ($product->product_image) {
                $oldImagePath = public_path('storage/') . $product->product_image;

                // Kiểm tra xem tệp có tồn tại không trước khi xóa
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $request->file('product_image')->store('products', 'public');
        }

        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-');
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->quantity = $request->quantity;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->quantity_alert = $request->quantity_alert;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->notes = $request->notes;
        $product->product_image = $image;
        $product->save();


        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been updated!');
    }

    public function destroy($uuid)
    {
        $product = Product::where("uuid", $uuid)->firstOrFail();
        /**
         * Delete photo if exists.
         */
        if ($product->product_image) {
            // check if image exists in our file system
            if (file_exists(public_path('storage/') . $product->product_image)) {
                unlink(public_path('storage/') . $product->product_image);
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }

    public function indexBySupplier($uuid)
    {
        // Tìm nhà cung cấp theo UUID
        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        // Lấy danh sách sản phẩm của nhà cung cấp
        $products = Product::where('supplier_id', $supplier->id)->get();

        // Trả về view với danh sách sản phẩm và nhà cung cấp
        return view('suppliers.products', compact('supplier', 'products'));
    }
}
