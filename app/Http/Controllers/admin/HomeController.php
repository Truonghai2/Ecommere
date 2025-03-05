<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Nhận giá trị từ request để xác định khoảng thời gian cần lọc
        $timeframe = $request->input('timeframe', 'month'); // Giá trị mặc định là 'day' nếu không có request

        switch ($timeframe) {
            case 'week':
                $salesData = Order::selectRaw('DAY(created_at) as day, SUM(price_new) as total_sales')
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();
                $labels = $salesData->pluck('day')->map(function ($day) {
                    return "Ngày $day";
                });
                break;

            case 'month':
                $salesData = Order::selectRaw('DAY(created_at) as day, SUM(price_new) as total_sales')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();
                $labels = $salesData->pluck('day')->map(function ($day) {
                    return "Ngày $day";
                });
                break;

            case 'year':
                $salesData = Order::selectRaw('MONTH(created_at) as month, SUM(price_new) as total_sales')
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                $labels = $salesData->pluck('month')->map(function ($month) {
                    return "Tháng $month";
                });
                break;

            default: // Mặc định là lọc theo ngày hiện tại
                $salesData = Order::selectRaw('HOUR(created_at) as hour, SUM(price_new) as total_sales')
                    ->whereDate('created_at', Carbon::today())
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();
                $labels = $salesData->pluck('hour')->map(function ($hour) {
                    return "$hour:00";
                });
                break;
        }

        // Chuẩn bị dữ liệu cho biểu đồ
        $totals = $salesData->pluck('total_sales');

        // Trả về view với dữ liệu đã chuẩn bị
        return view('admin.pages.dashboard', compact('labels', 'totals', 'timeframe'));
    }

}
