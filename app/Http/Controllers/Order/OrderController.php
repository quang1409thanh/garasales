<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Mail\StockAlert;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Str;

class OrderController extends Controller
{

    public function index()
    {
        $orders_count = Order::where('user_id', auth()->id())->count();
        // Lấy tất cả các đơn hàng có order_status là 1 và thuộc về người dùng đang đăng nhập
        $orders = Order::where('order_status', 1)
            ->where('user_id', auth()->id())
            ->get();

        // Tính tổng số tiền cho tất cả các đơn hàng có order_status là 1
        $totalOrdersAmount = $orders->sum('total');

        return view('orders.index', [
            'orders' => $orders_count,
            'totalOrdersAmount' => $totalOrdersAmount
        ]);
    }

    public function detail($uuid)
    {
        // Lấy khách hàng dựa trên UUID
        $customer = Customer::where('uuid', $uuid)->firstOrFail();

        // Lấy danh sách đơn hàng của khách hàng có trạng thái 1
        $orders = Order::where('customer_id', $customer->id)
            ->where('order_status', 1) // Chỉ lấy đơn hàng có order_status là 1
            ->get();

        // Tính tổng tất cả các đơn hàng
        $totalAmount = $orders->sum('total'); // Giả sử trường 'total' lưu giá trị đơn hàng

        return view('orders.order_of_customer', [
            'orders' => $orders,
            'totalAmount' => $totalAmount // Truyền tổng vào view
        ]);
    }


    public function create()
    {
        $products = Product::where('user_id', auth()->id())
            ->where('quantity', '>', 0)
            ->with(['category', 'unit'])
            ->get();

        $customers = Customer::where('user_id', auth()->id())->get(['id', 'name']);
        // Nếu chỉ có một khách hàng, có thể lấy trực tiếp
        $customer = $customers->first(); // Lấy khách hàng đầu tiên

        $carts = Cart::content();
        foreach (Cart::content() as $item) {
            Cart::setTax($item->rowId, 0);
        }

        return view('orders.create', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
            'customer' => $customer, // Truyền khách hàng duy nhất đến view

        ]);
    }

    public function store(OrderStoreRequest $request)
    {
        $paymentImageUrl = ""; // Biến để lưu URL của ảnh thanh toán

        // Xử lý upload file
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension(); // Tạo tên file duy nhất
            $path = 'payment_proofs/'; // Thư mục lưu ảnh trên GCS

            // Lưu file mới lên Google Cloud Storage
            $filePath = $file->storeAs(rtrim($path, '/'), $fileName, 'gcs'); // Lưu ảnh lên GCS

            // Lấy URL của ảnh đã lưu
            $paymentImageUrl = Storage::disk('gcs')->url($filePath);
        }

        // Tạo đơn hàng
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'payment_type' => $request->payment_type,
            'pay' => $request->pay,
            'order_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'order_status' => OrderStatus::COMPLETE->value,
            'total_products' => Cart::count(),
            'sub_total' => Cart::subtotal(),
            'vat' => Cart::tax(),
            'total' => Cart::total(),
            'invoice_no' => IdGenerator::generate([
                'table' => 'orders',
                'field' => 'invoice_no',
                'length' => 10,
                'prefix' => 'INV-'
            ]),
            'due' => (Cart::total() - $request->pay),
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'payment_image_url' => $paymentImageUrl, // Lưu URL của ảnh vào đây
        ]);

        // Tạo chi tiết đơn hàng
        $contents = Cart::content();
        $oDetails = [];

        foreach ($contents as $content) {
            $oDetails['order_id'] = $order['id'];
            $oDetails['product_id'] = $content->id;
            $oDetails['quantity'] = $content->qty;
            $oDetails['unitcost'] = $content->price;
            $oDetails['total'] = $content->subtotal;
            $oDetails['created_at'] = Carbon::now();

            OrderDetails::insert($oDetails);
        }

        // Xóa giỏ hàng sau khi tạo đơn hàng
        Cart::destroy();

        return redirect()
            ->route('orders.complete')
            ->with('success', 'Order has been completed!');
    }

    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->loadMissing(['customer', 'details'])->get();
        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function update($uuid, Request $request)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        // TODO refactoring

        // Reduce the stock
        $products = OrderDetails::where('order_id', $order->id)->get();

        $stockAlertProducts = [];

        foreach ($products as $product) {
            $productEntity = Product::where('id', $product->product_id)->first();
            // Kiểm tra xem sản phẩm có tồn tại không (phòng trường hợp dữ liệu bị lỗi)
            if (!$productEntity || $productEntity->quantity <= 0) {
                return redirect()->back()->withErrors(['error' => 'Sản phẩm .' . $productEntity->name . ' đã hết hàng! Vui lòng xóa order và chọn sản phẩm khác']);
            }

            $newQty = $productEntity->quantity - $product->quantity;
            if ($newQty < $productEntity->quantity_alert) {
                $stockAlertProducts[] = $productEntity;
            }
            $product_sold = $productEntity->product_sold + $product->quantity;
            $productEntity->update(['quantity' => $newQty]);
            $productEntity->update(['product_sold' => $product_sold]);
        }

        if (count($stockAlertProducts) > 0) {
            $listAdmin = [];
            foreach (User::all('email') as $admin) {
                $listAdmin [] = $admin->email;
            }
            Mail::to($listAdmin)->send(new StockAlert($stockAlertProducts));
        }
        $order->update([
            'order_status' => OrderStatus::COMPLETE,
            'due' => '0',
            'pay' => $order->total
        ]);

        return redirect()
            ->route('orders.complete')
            ->with('success', 'Order has been completed!');
    }

    public function destroy($uuid)
    {
        // Tìm đơn hàng dựa trên uuid
        $order = Order::where('uuid', $uuid)->firstOrFail();

        /**
         * Xóa ảnh thanh toán nếu có.
         */
        if ($order->payment_image_url) {
            // Xóa ảnh thanh toán từ Google Cloud Storage
            if (Storage::disk('gcs')->exists($order->payment_image_url)) {
                Storage::disk('gcs')->delete($order->payment_image_url);
            }
        }

        // Xóa đơn hàng khỏi cơ sở dữ liệu
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been deleted!');
    }


    public function downloadInvoice($uuid)
    {
        $order = Order::with(['customer', 'details'])->where('uuid', $uuid)->firstOrFail();
        // TODO: Need refactor
        //dd($order);

        //$order = Order::with('customer')->where('id', $order_id)->first();
        // $order = Order::
        //     ->where('id', $order)
        //     ->first();
//        dd($order);
        return view('orders.print-invoice', [
            'order' => $order,
        ]);
    }

    public function cancel(Order $order)
    {
        $order->update([
            'order_status' => 2
        ]);
        $orders = Order::where('user_id', auth()->id())->count();

        return redirect()
            ->route('orders.index', [
                'orders' => $orders
            ])
            ->with('success', 'Order has been canceled!');
    }
}
