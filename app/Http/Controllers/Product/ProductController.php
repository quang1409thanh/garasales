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
use Intervention\Image\Facades\Image;
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
        $imagePath = "";
        $thumbnailPath = "";

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');

            // Lưu hình ảnh gốc
            $imagePath = $image->store('products', 'public');

            // Tạo đường dẫn cho thumbnail
            $thumbnailPath = 'thumbnails/' . pathinfo($imagePath, PATHINFO_BASENAME);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

            // Nén hình ảnh và lưu vào vị trí gốc
            $img = Image::make($image->getRealPath());
            $img->save(storage_path('app/public/' . $imagePath), 50); // Lưu hình ảnh gốc với chất lượng 75

            // Kiểm tra và tạo thư mục thumbnails nếu chưa tồn tại
            if (!file_exists(dirname($thumbnailFullPath))) {
                mkdir(dirname($thumbnailFullPath), 0755, true); // Tạo thư mục nếu chưa tồn tại
            }

            // Resize hình ảnh cho thumbnail
            $img->resize(90, 90, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Lưu thumbnail vào thư mục
            $img->save($thumbnailFullPath);
        }

        // Tạo sản phẩm và lưu vào cơ sở dữ liệu
        Product::create([
            "code" => IdGenerator::generate([
                'table' => 'products',
                'field' => 'code',
                'length' => 4,
                'prefix' => 'PC'
            ]),

            'product_image' => $imagePath, // Đường dẫn hình ảnh gốc
            'thumbnail_url' => $thumbnailPath, // Lưu đường dẫn thumbnail vào cơ sở dữ liệu
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'quantity_alert' => $request->quantity_alert,
            'tax' => $request->tax,
            'tax_type' => $request->tax_type,
            'notes' => $request->notes,
            "user_id" => auth()->id(),
            "slug" => Str::slug($request->name, '-'),
            "uuid" => Str::uuid(),
            'supplier_id' => $request->supplier_id,
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

        // Cập nhật thông tin sản phẩm mà không bao gồm hình ảnh
        $product->update($request->except('product_image'));

        // Biến để lưu đường dẫn hình ảnh gốc
        $image = $product->product_image;
        $thumbnailPath = "";

        if ($request->hasFile('product_image')) {

            // Xóa hình ảnh cũ nếu có
            if ($product->product_image) {
                $oldImagePath = public_path('storage/') . $product->product_image;
                $oldThumbnailPath = public_path('storage/thumbnails/') . pathinfo($product->product_image, PATHINFO_BASENAME);

                // Kiểm tra xem tệp có tồn tại không trước khi xóa
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                // Xóa thumbnail cũ nếu có
                if (file_exists($oldThumbnailPath)) {
                    unlink($oldThumbnailPath);
                }
            }

            // Lưu hình ảnh gốc mới
            $image = $request->file('product_image')->store('products', 'public');

            // Tạo đường dẫn cho thumbnail
            $thumbnailPath = 'thumbnails/' . pathinfo($image, PATHINFO_BASENAME);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

            // Nén hình ảnh và lưu vào vị trí gốc
            $img = Image::make($request->file('product_image')->getRealPath());
            $img->save(storage_path('app/public/' . $image), 50); // Lưu hình ảnh gốc với chất lượng 75

            // Kiểm tra và tạo thư mục thumbnails nếu chưa tồn tại
            if (!file_exists(dirname($thumbnailFullPath))) {
                mkdir(dirname($thumbnailFullPath), 0755, true); // Tạo thư mục nếu chưa tồn tại
            }

            // Resize hình ảnh cho thumbnail
            $img->resize(90, 90, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Lưu thumbnail vào thư mục
            $img->save($thumbnailFullPath);
        }

        // Cập nhật thông tin sản phẩm
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

        // Chỉ cập nhật đường dẫn hình ảnh và thumbnail nếu có ảnh mới
        if ($request->hasFile('product_image')) {
            $product->product_image = $image;
            $product->thumbnail_url = $thumbnailPath; // Cập nhật đường dẫn thumbnail
        }

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
