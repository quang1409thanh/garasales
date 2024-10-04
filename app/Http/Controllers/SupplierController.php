<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use Illuminate\Support\Facades\Storage;
use Str;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where("user_id", auth()->id())->count();

        return view('suppliers.index', [
            'suppliers' => $suppliers
        ]);
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
            ->route('suppliers.show', ['supplier' => $supplier->uuid]) // Thay thế $supplier->uuid bằng cách lấy UUID từ nhà cung cấp mới
            ->with('success', 'New supplier has been created!');
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

        // Cập nhật thông tin nhà cung cấp trong cơ sở dữ liệu
        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
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
}
