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
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Str;

class ProductController extends Controller
{

    public function index()
    {
        $userId = auth()->id();

        // Sử dụng select và join để lấy thông tin supplier luôn
        $products = Product::where("products.user_id", $userId)
            ->select(
                'products.*',
                'suppliers.name as supplier_name',
                'suppliers.id as supplier_uuid'
            )
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->orderBy('products.created_at', 'desc')
            ->paginate(15);

        return view('products.index', [
            'products' => $products,
        ]);
    }
    public function toggleVisibility($uuid)
    {
        // Tìm sản phẩm theo UUID
        $product = Product::where('uuid', $uuid)->firstOrFail();
        if ($product->tax_type->value === 0) {
            $product->tax_type = 1;
        } else if ($product->tax_type->value === 1) {
            $product->tax_type = 0;
        };
        // Toggle giá trị is_show_visible
        $product->save();

        // Trả về phản hồi Json
        return redirect()
            ->route('products.index')
            ->with('success', 'Trạng thái xem của sản phẩm đã được cập nhật!');

    }

    public function create(Request $request)
    {
        // Lấy danh sách các categories, units, và suppliers từ database theo người dùng hiện tại
        $categories = Category::where("user_id", auth()->id())->get(['id', 'name']);
        $units = Unit::where("user_id", auth()->id())->get(['id', 'name']);

        // Lấy danh sách tất cả nhà cung cấp từ DB
        $suppliers = Supplier::all();

        // Kiểm tra và lấy giá trị 'category' từ request, nếu có thì chỉ lọc category theo slug
        if ($request->has('category')) {
            $categories = Category::where("user_id", auth()->id())
                ->whereSlug($request->get('category'))->get();
        }

        // Lấy giá trị 'supplier' từ query string (nếu có)
        $supplier_id = $request->query('supplier');

        // Kiểm tra xem supplier_id có hợp lệ không
        if ($supplier_id) {
            $supplier = Supplier::find($supplier_id);
            if (!$supplier) {
                return redirect()->back()->withErrors(['error' => 'Supplier không tồn tại']);
            }
        }

        // Kiểm tra và lấy giá trị 'unit' từ request, nếu có thì chỉ lọc unit theo slug
        if ($request->has('unit')) {
            $units = Unit::where("user_id", auth()->id())
                ->whereSlug($request->get('unit'))->get();
        }

        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
            'suppliers' => $suppliers,
            'supplier_id' => $supplier_id,
        ]);
    }

    public function generateProductCode()
    {
        // Lấy mã sản phẩm lớn nhất trong bảng
        $lastProduct = Product::orderBy('code', 'desc')->first();
        if ($lastProduct) {

            // Tách phần số của mã sản phẩm
            $lastCodeNumber = (int)substr($lastProduct->code, 2); // 'pc' là 2 ký tự đầu
            $newCodeNumber = $lastCodeNumber + 1; // Tăng dần
        } else {
            $newCodeNumber = 1; // Bắt đầu từ 1 nếu không có sản phẩm nào
        }

        // Tạo mã sản phẩm mới
        return 'PC' . str_pad($newCodeNumber, 7, '0', STR_PAD_LEFT); // Tạo mã như pc0000001
    }

    public function store(StoreProductRequest $request)
    {
        $imageUrl = ""; // Đường dẫn hình ảnh gốc
        $thumbnailUrl = ""; // Đường dẫn thumbnail

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');

            // Tạo tên file duy nhất
            $fileName = time() . '_' . $image->getClientOriginalName();
            $path = 'products/'; // Thư mục lưu ảnh trên GCS

            // Tạo thumbnail và lưu vào một file tạm
            $thumbnailTmpPath = sys_get_temp_dir() . '/' . 'thumbnail_' . $fileName;

            try {
                // Resize và lưu thumbnail tạm thời
                $thumbnail = Image::make($image->getRealPath());
                $thumbnail->resize(90, 90, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($thumbnailTmpPath);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['photo' => 'Có lỗi xảy ra khi xử lý thumbnail: ' . $e->getMessage()]);
            }

            // Đường dẫn tạm để lưu ảnh đã nén
            $compressedTmpPath = sys_get_temp_dir() . '/' . $fileName;

            try {
                // Nén ảnh trước khi upload lên Cloud Storage, giữ nguyên orientation
                $compressedImage = Image::make($image->getRealPath());

                // Kiểm tra và xoay ảnh dựa trên EXIF (nếu có)
                if ($compressedImage->exif('Orientation')) {
                    switch ($compressedImage->exif('Orientation')) {
                        case 3:
                            $compressedImage->rotate(180);
                            break;
                        case 6:
                            $compressedImage->rotate(-90);
                            break;
                        case 8:
                            $compressedImage->rotate(90);
                            break;
                    }
                }

                // Lưu ảnh đã xoay (nếu cần) và nén lại
                $compressedImage->save($compressedTmpPath, 75); // Lưu ảnh nén với chất lượng 75%
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['photo' => 'Có lỗi xảy ra khi xử lý ảnh: ' . $e->getMessage()]);
            }

            // Upload **ảnh nén** đã được tạo lên Google Cloud Storage
            try {
                // Đẩy file ảnh nén thay vì ảnh gốc
                $imagePath = Storage::disk('gcs')->putFileAs('products', new File($compressedTmpPath), $fileName);
                $imageUrl = Storage::disk('gcs')->url($imagePath);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['photo' => 'Có lỗi xảy ra khi upload ảnh gốc: ' . $e->getMessage()]);
            }

            // Upload thumbnail lên Google Cloud Storage
            try {
                $thumbnailPath = 'thumbnails/' . $fileName;
                Storage::disk('gcs')->put($thumbnailPath, file_get_contents($thumbnailTmpPath));
                $thumbnailUrl = Storage::disk('gcs')->url($thumbnailPath);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['photo' => 'Có lỗi xảy ra khi upload thumbnail: ' . $e->getMessage()]);
            }

            // Xóa file thumbnail tạm sau khi upload
            unlink($thumbnailTmpPath);
            unlink($compressedTmpPath); // Xóa file ảnh nén tạm sau khi upload
        }

        // Tạo sản phẩm và lưu vào cơ sở dữ liệu với URL hình ảnh từ Cloud Storage
        Product::create([
            'code' => $this->generateProductCode(),
            'product_image' => $imageUrl, // Lưu URL ảnh gốc (đã nén) vào cơ sở dữ liệu
            'thumbnail_url' => $thumbnailUrl, // Lưu URL thumbnail vào cơ sở dữ liệu
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'quantity_alert' => $request->quantity_alert,
            'tax' => $request->tax,
            'tax_type' => 1,
            'notes' => $request->notes,
            "user_id" => auth()->id(),
            "slug" => Str::slug($request->name, '-'),
            "uuid" => Str::uuid(),
            'supplier_id' => $request->supplier_id,
            'fee' => $request->fee,
            'product_sold' => 0,
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
        // Tìm sản phẩm dựa trên uuid
        $product = Product::where("uuid", $uuid)->firstOrFail();

        // Cập nhật thông tin sản phẩm mà không bao gồm hình ảnh
        $product->update($request->except('product_image'));

        // Biến để lưu đường dẫn hình ảnh gốc
        $imageUrl = $product->product_image; // Khởi tạo với giá trị hiện tại
        $thumbnailUrl = "";

        // Kiểm tra xem có hình ảnh mới được tải lên không
        if ($request->hasFile('product_image')) {
            // Xóa hình ảnh cũ nếu có
            if ($product->product_image) {
                $oldImagePath = $product->product_image;
                $oldThumbnailPath = 'thumbnails/' . pathinfo($oldImagePath, PATHINFO_BASENAME);

                // Kiểm tra tồn tại trước khi xóa
                if (Storage::disk('gcs')->exists($oldImagePath)) {
                    Storage::disk('gcs')->delete($oldImagePath);
                }

                if (Storage::disk('gcs')->exists($oldThumbnailPath)) {
                    Storage::disk('gcs')->delete($oldThumbnailPath);
                }
            }

            // Tạo tên file duy nhất cho hình ảnh mới
            $imageFile = $request->file('product_image');
            $fileName = time() . '_' . $imageFile->getClientOriginalName();

            try {
                // Resize và nén ảnh trước khi upload lên Cloud Storage
                $img = Image::make($imageFile->getRealPath());

                // Kiểm tra và xoay ảnh dựa trên EXIF (nếu có)
                if ($img->exif('Orientation')) {
                    switch ($img->exif('Orientation')) {
                        case 3:
                            $img->rotate(180);
                            break;
                        case 6:
                            $img->rotate(-90);
                            break;
                        case 8:
                            $img->rotate(90);
                            break;
                    }
                }

                // Đường dẫn tạm thời để lưu ảnh đã nén
                $compressedTmpPath = sys_get_temp_dir() . '/' . $fileName;

                // Lưu ảnh nén tạm thời với chất lượng nén
                $img->save($compressedTmpPath, 80); // Chất lượng 80%

                // Upload ảnh gốc đã được nén lên Google Cloud Storage
                $imagePath = Storage::disk('gcs')->putFileAs('products', new File($compressedTmpPath), $fileName);
                $imageUrl = Storage::disk('gcs')->url($imagePath);

                // Xóa ảnh tạm sau khi upload
                unlink($compressedTmpPath);
            } catch (\Exception $e) {
                // Xử lý lỗi nếu có xảy ra trong quá trình nén và upload
                return redirect()->back()->withErrors(['photo' => 'Có lỗi xảy ra khi xử lý ảnh: ' . $e->getMessage()]);
            }

            // Tạo thumbnail và lưu vào một file tạm
            $thumbnailTmpPath = sys_get_temp_dir() . '/' . 'thumbnail_' . $fileName;

            try {
                // Resize và lưu thumbnail tạm thời
                $img->resize(90, 90, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($thumbnailTmpPath);
            } catch (\Exception $e) {
                // Nếu có lỗi trong quá trình xử lý ảnh, trả về lỗi kèm thông báo chi tiết
                return redirect()->back()->withErrors(['photo' => 'Có lỗi xảy ra khi xử lý ảnh: ' . $e->getMessage()]);
            }

            // Upload thumbnail lên Google Cloud Storage
            $thumbnailPath = 'thumbnails/' . $fileName;
            Storage::disk('gcs')->put($thumbnailPath, file_get_contents($thumbnailTmpPath));
            $thumbnailUrl = Storage::disk('gcs')->url($thumbnailPath);

            // Xóa file thumbnail tạm sau khi upload
            unlink($thumbnailTmpPath);
        }

        // Cập nhật đường dẫn hình ảnh và thumbnail nếu có ảnh mới
        if ($request->hasFile('product_image')) {
            $product->product_image = $imageUrl; // Cập nhật đường dẫn ảnh gốc
            $product->thumbnail_url = $thumbnailUrl; // Cập nhật đường dẫn thumbnail
        }

        try {
            // Cập nhật thông tin sản phẩm
            $product->name = $request->name;
            $product->slug = Str::slug($request->name, '-');
            $product->category_id = $request->category_id;
            $product->unit_id = $request->unit_id;
            $product->quantity = $request->quantity;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->quantity_alert = 0;
            $product->tax = 1;
            $product->tax_type = 1;
            $product->notes = $request->notes;
            $product->fee = $request->fee;

            // Lưu thông tin sản phẩm
            $product->save();

            return redirect()
                ->route('products.index')
                ->with('success', 'Product has been updated!');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu không cập nhật được sản phẩm
            return redirect()
                ->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy($uuid)
    {
        // Tìm sản phẩm dựa trên uuid
        $product = Product::where("uuid", $uuid)->firstOrFail();

        /**
         * Xóa hình ảnh và thumbnail nếu có.
         */
        if ($product->product_image) {
            // Xóa ảnh gốc từ Google Cloud Storage
            if (Storage::disk('gcs')->exists($product->product_image)) {
                Storage::disk('gcs')->delete($product->product_image);
            }
        }

        // Xóa thumbnail nếu có thumbnail_url
        if ($product->thumbnail_url) {
            // Xóa thumbnail từ Google Cloud Storage
            if (Storage::disk('gcs')->exists($product->thumbnail_url)) {
                Storage::disk('gcs')->delete($product->thumbnail_url);
            }
        }

        // Xóa sản phẩm khỏi cơ sở dữ liệu
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }

    public function indexBySupplier($uuid)
    {
        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();
        $products = Product::where('supplier_id', $supplier->id)->get();

        // Tính tổng giá bán và giá trả lại cho các sản phẩm có `product_sold > 1`
        $totalSellingPrice = $products->where('product_sold', '>=', 1)
            ->sum(function ($product) {
                return $product->product_sold * $product->selling_price;
            });

        $totalBuyingPrice = $products->where('product_sold', '>=', 1)
            ->sum(function ($product) {
                return $product->product_sold * $product->buying_price;
            });

        // Trả về view với danh sách sản phẩm và nhà cung cấp
        return view('suppliers.products', compact('supplier', 'products', 'totalSellingPrice', 'totalBuyingPrice'));
    }
}
