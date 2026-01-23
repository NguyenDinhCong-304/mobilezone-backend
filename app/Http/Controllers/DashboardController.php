<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'posts' => Post::count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::count(),

            'latest_orders' => Order::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'status', 'created_at']),

            'latest_posts' => Post::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'title', 'status', 'created_at']),
        ]);
    }
    public function chart()
    {
        $startDate = Carbon::now()->subDays(6);

        $orders = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chuẩn hóa đủ 7 ngày
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $found = $orders->firstWhere('date', $date);

            $data[] = [
                'date' => $date,
                'total' => $found ? $found->total : 0
            ];
        }

        return response()->json($data);
    }
}
