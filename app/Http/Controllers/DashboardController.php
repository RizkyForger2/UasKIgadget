<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalCategories = Category::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        
        // Recent Orders
        $recentOrders = Order::with('orderItems.product')
            ->latest()
            ->take(5)
            ->get();
        
        // Top Products
        $topProducts = Product::withCount(['orderItems as total_sold' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
        
        // Sales by Month (last 6 months)
        $salesByMonth = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Pending Reviews
        $pendingReviews = Review::where('is_approved', false)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts', 
            'totalOrders', 
            'totalRevenue', 
            'totalCategories',
            'pendingOrders',
            'lowStockProducts',
            'recentOrders',
            'topProducts',
            'salesByMonth',
            'pendingReviews'
        ));
    }
}