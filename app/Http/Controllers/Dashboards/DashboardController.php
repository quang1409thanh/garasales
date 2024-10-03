<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quotation;
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
        $todayOrders = Order::whereDate('created_at', today()->format('Y-m-d'))->count();

        $categories = Category::where("user_id", auth()->id())->count();
        $quotations = Quotation::where("user_id", auth()->id())->count();

        // Tính toán dữ liệu động
        $totalProductSold = Order::where('order_status', '1')->sum('total_products'); // Tổng số lượng sản phẩm đã bán
        $totalRevenue = Order::where('order_status', '1')->sum('total'); // Tổng doanh thu
        $orderPending = Order::where('order_status', '0')->sum('total'); // Tổng doanh thu // Khách hàng mới trong 7 ngày qua
        $pendingOrderCount = Order::where('order_status', '0')->sum('total_products'); // Tổng doanh thu // Khách hàng mới trong 7 ngày qua
        // Tính toán tỷ lệ phần trăm thay đổi
        $conversionRateChange = 7; // Tăng 7%
        $revenueChange = 8; // Tăng 8%
        $newClientsChange = 0; // Không thay đổi
        $activeUsersChange = 4; // Tăng 4%


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
            'orderPending' => $orderPending,
            'pendingOrderCount' => $pendingOrderCount,
            'conversionRateChange' => $conversionRateChange,
            'revenueChange' => $revenueChange,
            'newClientsChange' => $newClientsChange,
            'activeUsersChange' => $activeUsersChange,
            'totalProductSold' => $totalProductSold,
            'revenue' =>$totalRevenue,
        ]);
    }
}
