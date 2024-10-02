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
        $totalSales = Order::sum('total_products'); // Tổng số lượng sản phẩm đã bán
        $totalRevenue = Order::sum('total'); // Tổng doanh thu
        $newClients = User::where('created_at', '>=', Carbon::now()->subDays(7))->count(); // Khách hàng mới trong 7 ngày qua
        $activeUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count(); // Người dùng hoạt động trong 7 ngày qua
        // Tính toán tỷ lệ phần trăm thay đổi
        $conversionRateChange = 7; // Tăng 7%
        $revenueChange = 8; // Tăng 8%
        $newClientsChange = 0; // Không thay đổi
        $activeUsersChange = 4; // Tăng 4%

        // Lấy dữ liệu từ cơ sở dữ liệu hoặc tính toán
        $salesPercentage = 75; // Tỷ lệ chuyển đổi
        $revenue = $totalRevenue; // Doanh thu

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
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'newClients' => $newClients,
            'activeUsers' => $activeUsers,
            'conversionRateChange' => $conversionRateChange,
            'revenueChange' => $revenueChange,
            'newClientsChange' => $newClientsChange,
            'activeUsersChange' => $activeUsersChange,
            'salesPercentage' => $salesPercentage,
            'revenue' =>$revenue,
        ]);
    }
}
