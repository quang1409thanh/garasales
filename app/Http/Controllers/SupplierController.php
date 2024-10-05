<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where("user_id", auth()->id())->get();

        // Khởi tạo giá trị
        $totalSellingPrice = 0;
        $totalBuyingPrice = 0;

        // Lặp qua từng nhà cung cấp để tính tổng giá bán và tổng giá trả lại
        foreach ($suppliers as $supplier) {
            $products = $supplier->products()->where('product_sold', '>', 0)->get();

            foreach ($products as $product) {
                $totalSellingPrice += $product->product_sold * $product->selling_price; // Tính tổng giá bán
                $totalBuyingPrice += $product->product_sold * $product->buying_price; // Tính tổng giá trả lại
            }
        }

        return view('suppliers.index', compact('suppliers', 'totalSellingPrice', 'totalBuyingPrice'));
    }

    public function showProducts()
    {
        $products = Product::where("user_id", auth()->id())->count();

        return view('suppliers.products', [
            'products' => $products
        ]);

    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $imageUrl = ""; // Biến để lưu URL của ảnh

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); // Tạo tên tệp duy nhất
            $path = 'suppliers/'; // Thư mục lưu ảnh trên GCS

            // Nếu đã tồn tại ảnh cũ, xóa ảnh cũ trước
            if ($request->user()->photo) {
                $oldImagePath = $request->user()->photo; // Lấy đường dẫn ảnh cũ
                if (Storage::disk('gcs')->exists($oldImagePath)) {
                    Storage::disk('gcs')->delete($oldImagePath); // Xóa ảnh cũ trên GCS
                }
            }

            // Lưu ảnh mới lên Google Cloud Storage
            $filePath = $file->storeAs(rtrim($path, '/'), $fileName, 'gcs'); // Lưu ảnh lên GCS

            // Lấy URL của ảnh đã lưu
            $imageUrl = Storage::disk('gcs')->url($filePath);
        }

        try {
            // Tạo mới nhà cung cấp và lưu URL ảnh vào cơ sở dữ liệu
            $supplier = Supplier::create([
                "user_id" => auth()->id(),
                "uuid" => Str::uuid(),
                'photo' => $imageUrl, // Lưu URL ảnh vào cơ sở dữ liệu
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'shopname' => $request->shopname,
                'type' => $request->type,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'address' => $request->address,
            ]);

            return redirect()
                ->route('suppliers.show', ['supplier' => $supplier->uuid]) // Thay thế $supplier->uuid bằng UUID từ nhà cung cấp mới
                ->with('success', 'New supplier has been created!');

        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra lỗi có liên quan đến trùng email hay các trường unique khác
            if ($e->errorInfo[1] == 1062) { // Mã lỗi SQL 1062 là Duplicate entry
                return back()->withErrors([
                    'error' => 'A supplier with the same email, phone, or other unique field already exists.'
                ])->withInput();
            }

            // Các lỗi khác
            return back()->withErrors([
                'error' => 'There was an error while creating the supplier. Please try again later.'
            ])->withInput();
        }
    }


    public function show($uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();
        $supplier->loadMissing('purchases')->get();

        return view('suppliers.show', [
            'supplier' => $supplier
        ]);
    }

    public function edit($uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();
        return view('suppliers.edit', [
            'supplier' => $supplier
        ]);
    }

    public function update(UpdateSupplierRequest $request, $uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();

        /**
         * Xử lý việc upload ảnh với Google Cloud Storage.
         */
        $imageUrl = $supplier->photo; // Lưu URL ảnh hiện tại của nhà cung cấp
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); // Tạo tên file duy nhất
            $path = 'suppliers/'; // Thư mục lưu ảnh trên GCS

            // Xóa ảnh cũ trên GCS nếu có
            if ($supplier->photo) {
                $oldImagePath = $supplier->photo; // Lấy đường dẫn ảnh cũ trên GCS
                if (Storage::disk('gcs')->exists($oldImagePath)) {
                    Storage::disk('gcs')->delete($oldImagePath); // Xóa ảnh cũ
                }
            }

            // Lưu ảnh mới lên Google Cloud Storage
            $filePath = $file->storeAs(rtrim($path, '/'), $fileName, 'gcs'); // Lưu ảnh lên GCS

            // Lấy URL của ảnh mới
            $imageUrl = Storage::disk('gcs')->url($filePath);
        }

        try {
            // Cập nhật thông tin nhà cung cấp trong cơ sở dữ liệu
            $supplier->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'photo' => $imageUrl, // Lưu URL ảnh vào cơ sở dữ liệu
                'shopname' => $request->shopname,
                'type' => $request->type,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'address' => $request->address,
            ]);
            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier has been updated!');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu không cập nhật được nhà cung cấp
            return redirect()
                ->back()
                ->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    public function destroy($uuid)
    {
        $supplier = Supplier::where("uuid", $uuid)->firstOrFail();
        /**
         * Delete photo if exists.
         */
        if ($supplier->photo) {
            unlink(public_path('storage/suppliers/') . $supplier->photo);
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier has been deleted!');
    }
    // Lưu thông tin thanh toán
    public function store_bill(Request $request, $uuid)
    {
        $request->validate([
            'bill_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_status' => 'required|in:pending,complete',
        ]);

        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        // Xử lý upload hình ảnh
        if ($request->hasFile('bill_image')) {
            // Tạo tên file duy nhất
            $imageName = time() . '.' . $request->bill_image->extension();

            // Lưu file vào Google Cloud Storage
            $path = $request->file('bill_image')->storeAs('bills', $imageName, 'gcs'); // Lưu file vào GCS

            // Lưu đường dẫn hình ảnh vào trường bill_image
            $supplier->bill_image = Storage::disk('gcs')->url($path); // Lấy URL công khai của tệp đã lưu
        }

        // Cập nhật trạng thái thanh toán
        $supplier->payment_status = $request->payment_status;
        $supplier->save();

        return redirect()->route('suppliers.payments.index', $supplier->uuid)
            ->with('success', 'Thông tin thanh toán đã được lưu.');
    }

    public function view_bill($uuid)
    {

        $supplier = Supplier::where('uuid', $uuid)->firstOrFail();

        return view('suppliers.view_bill',compact('supplier'));
    }
}
