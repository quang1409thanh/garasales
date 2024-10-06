<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where("user_id", auth()->id())->count();
        $products = Product::where("user_id", auth()->id())->count();

        $purchases = Purchase::where("user_id", auth()->id())->count();
        $todayPurchases = Purchase::whereDate('date', today()->format('Y-m-d'))->count();
        $todayProducts = Product::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayQuotations = Quotation::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayOrders = Order::where('order_status', 1)->count();
        $categories = Category::where("user_id", auth()->id())->count();
        $quotations = Quotation::where("user_id", auth()->id())->count();

        $suppliers = Supplier::where("user_id", auth()->id())->get();

        // Khởi tạo giá trị
        $totalSellingPrice = 0;
        $totalBuyingPrice = 0;

        // Lặp qua từng nhà cung cấp để tính tổng giá bán và tổng giá trả lại
        foreach ($suppliers as $supplier) {
            $products_ = $supplier->products()->where('product_sold', '>', 0)->get();

            foreach ($products_ as $product_i) {
                $totalSellingPrice += $product_i->product_sold * $product_i->selling_price; // Tính tổng giá bán
                $totalBuyingPrice += $product_i->product_sold * $product_i->buying_price; // Tính tổng giá trả lại
            }
        }

        $totalProductSold = Order::where('order_status', '1')->sum('total_products'); // Tổng số lượng sản phẩm đã bán

        return view('dashboard', [
            'products' => $products,
            'orders' => $orders,
            'purchases' => $purchases,
            'todayPurchases' => $todayPurchases,
            'todayProducts' => $todayProducts,
            'todayQuotations' => $todayQuotations,
            'todayOrders' => $todayOrders,
            'categories' => $categories,
            'quotations' => $quotations,
            'totalSellingPrice' =>$totalSellingPrice,
            'totalBuyingPrice' => $totalBuyingPrice,
            'profit' => $totalSellingPrice -$totalBuyingPrice,
            'totalSellingPriceChange' => 8,
            'conversionRateChange' => 4,
            'newClientsChange' => 0,
            'activeUsersChange' => 5,
            'totalProductSold' => $totalProductSold,
        ]);
    }
}
