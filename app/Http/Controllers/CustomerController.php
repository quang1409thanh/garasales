<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Illuminate\Support\Facades\Storage;
use Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('user_id', auth()->id())->count();

        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        /**
         * Xử lý việc upload ảnh với Google Cloud Storage.
         */
        $imageUrl = ''; // Biến lưu URL của ảnh

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); // Tạo tên file duy nhất
            $path = 'customers/'; // Thư mục lưu ảnh trên GCS

            // Lưu ảnh lên Google Cloud Storage
            $filePath = $file->storeAs(rtrim($path, '/'), $fileName, 'gcs'); // Lưu ảnh lên GCS

            // Lấy URL của ảnh đã lưu
            $imageUrl = Storage::disk('gcs')->url($filePath);

            // Sử dụng rtrim() để đảm bảo không có dấu '/' thừa ở cuối URL
            $imageUrl = rtrim($imageUrl, '/');
        }

        // Tạo mới khách hàng và lưu URL ảnh vào cơ sở dữ liệu
        Customer::create([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
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
            ->route('customers.index')
            ->with('success', 'New customer has been created!');
    }

    public function show($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        $customer->loadMissing(['orders'])->get();

        return view('customers.show', [
            'customer' => $customer
        ]);
    }

    public function edit($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, $uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();

        /**
         * Xử lý việc upload ảnh với Google Cloud Storage.
         */
        $imageUrl = $customer->photo; // Lấy URL ảnh hiện tại nếu không có ảnh mới
        if ($request->hasFile('photo')) {
            // Xóa ảnh cũ trên GCS nếu tồn tại

            if ($customer->photo) {
                $oldImagePath = $customer->photo; // Lấy đường dẫn ảnh cũ trên GCS
                if (Storage::disk('gcs')->exists($oldImagePath)) {
                    Storage::disk('gcs')->delete($oldImagePath); // Xóa ảnh cũ
                }
            }

            // Lưu ảnh mới lên GCS
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); // Tạo tên file duy nhất
            $path = 'customers/'; // Thư mục lưu ảnh trên GCS

            // Lưu file vào GCS
            $filePath = $file->storeAs(rtrim($path, '/'), $fileName, 'gcs'); // Lưu ảnh lên GCS

            // Lấy URL của ảnh mới
            $imageUrl = Storage::disk('gcs')->url($filePath);

            // Sử dụng rtrim() để loại bỏ dấu '/' cuối cùng nếu có
            $imageUrl = rtrim($imageUrl, '/');
        }

        // Cập nhật thông tin khách hàng
        $customer->update([
            'photo' => $imageUrl, // Cập nhật URL ảnh
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
            ->route('customers.index')
            ->with('success', 'Customer has been updated!');
    }

    public function destroy($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        if ($customer->photo) {
            unlink(public_path('storage/') . $customer->photo);
        }

        $customer->delete();

        return redirect()
            ->back()
            ->with('success', 'Customer has been deleted!');
    }
}
