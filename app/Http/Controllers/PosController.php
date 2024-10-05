<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'unit'])->get();

        $customers = Customer::all()->sortBy('name');

        $carts = Cart::content();

        return view('pos.index', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    public function addCartItem(Request $request)
    {
        $request->all();
        // Định nghĩa các quy tắc xác thực
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'selling_price' => 'required|numeric',
        ];


        $validatedData = $request->validate($rules);
        // Lấy số lượng sản phẩm từ kho
        $product = Product::find($validatedData['id']);
        if (!$product) {
            return redirect()
                ->back()
                ->with('error', 'Product not found!');
        }

        // Kiểm tra số lượng đơn hàng pending cho sản phẩm
        $pendingOrdersCount = Order::where('order_status', OrderStatus::PENDING->value)
            ->whereHas('details', function ($query) use ($validatedData) {
                $query->where('product_id', $validatedData['id']);
            })
            ->with('details') // Tải các chi tiết đơn hàng
            ->get()
            ->sum(function ($order) {
                return $order->details->sum('quantity'); // Tổng số lượng sản phẩm trong các đơn hàng pending
            });

        // So sánh số lượng đơn hàng pending với số lượng trong kho
        if ($pendingOrdersCount + 1 > $product->quantity) {
            return redirect()
                ->back()
                ->with('error', 'Không đủ hàng! Sản phẩm "' . $validatedData['name'] . '" hiện đang được xử lý bởi các đơn hàng khác.');
        }


        // Kiểm tra số lượng có lớn hơn 0 không
        if ($request['quantity'] <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Quantity must be greater than zero!'); // Thông báo lỗi nếu số lượng không hợp lệ
        }

        Cart::add(
            $validatedData['id'],
            $validatedData['name'],
            1,
            $validatedData['selling_price'],
            1,
            (array)$options = null
        );

        return redirect()
            ->back()
            ->with('success', 'Product has been added to cart!');
    }

    public function updateCartItem(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
            'product_id' => 'numeric'
        ];
        $product = Product::find($request['product_id']);
        if (!$product) {
            return redirect()
                ->back()
                ->with('error', 'Product not found!');
        }

        // Kiểm tra số lượng đơn hàng pending cho sản phẩm
        $pendingOrdersCount = Order::where('order_status', OrderStatus::PENDING->value)
            ->whereHas('details', function ($query) use ($request) {
                $query->where('product_id', $request['product_id']);
            })
            ->with('details') // Tải các chi tiết đơn hàng
            ->get()
            ->sum(function ($order) {
                return $order->details->sum('quantity'); // Tổng số lượng sản phẩm trong các đơn hàng pending
            });
        if ($pendingOrdersCount + $request['qty'] > $product->quantity) {
            return redirect()
                ->back()
                ->with('error', 'Không đủ hàng! Sản phẩm này hiện đang được xử lý bởi các đơn hàng khác.');
        }


        $validatedData = $request->validate($rules);
        if ($validatedData['qty'] > Product::where('id', intval($validatedData['product_id']))->value('quantity')) {
            return redirect()
                ->back()
                ->with('error', 'The requested quantity is not available in stock.');
        }


        Cart::update($rowId, $validatedData['qty']);

        return redirect()
            ->back()
            ->with('success', 'Product has been updated from cart!');
    }

    public function deleteCartItem(string $rowId)
    {
        Cart::remove($rowId);

        return redirect()
            ->back()
            ->with('success', 'Product has been deleted from cart!');
    }
}
