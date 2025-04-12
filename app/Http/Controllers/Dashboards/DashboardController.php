<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where("user_id", auth()->id())->count();
        $products = Product::where("user_id", auth()->id())->count();

        $purchases = Purchase::where("user_id", auth()->id())->count();
        $todayPurchases = Purchase::whereDate('date', today()->format('Y-m-d'))->count();
        $todayProducts = Product::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayOrders = Order::where('order_status', 1)->count();
        $categories = Category::where("user_id", auth()->id())->count();

        $suppliers = Supplier::where("user_id", auth()->id())->get();


        // Sử dụng DB query builder hoặc raw SQL để tính toán trực tiếp
        $totals = DB::table('products')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->where('products.product_sold', '>', 0)
            ->select(
                DB::raw('SUM(products.product_sold * products.selling_price / 100) as total_selling_price'),
                DB::raw('SUM(products.product_sold * products.buying_price / 100) as total_buying_price')
            )
            ->first();

        $totalSellingPrice = $totals->total_selling_price ?? 0;
        $totalBuyingPrice = $totals->total_buying_price ?? 0;

        $totalProductSold = Order::where('order_status', '1')->sum('total_products'); // Tổng số lượng sản phẩm đã bán

        return view('dashboard', [
            'products' => $products,
            'orders' => $orders,
            'purchases' => $purchases,
            'todayPurchases' => $todayPurchases,
            'todayProducts' => $todayProducts,
            'todayOrders' => $todayOrders,
            'categories' => $categories,
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
