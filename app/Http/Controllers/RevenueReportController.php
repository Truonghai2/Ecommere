<?php

namespace App\Http\Controllers;

use App\Exports\RevenueReportExport;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RevenueReportController extends Controller
{
    public function getRevenueReport(Request $request){
        $type = $request->type;

        if($type === 'revenue-report'){
            $orders = Order::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(price_new) as total_revenue')
                )
                ->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfMonth(),
                    Carbon::parse($request->end_date)->endOfMonth()
                ])
                ->groupBy('year', 'month')
                ->get();

            return Excel::download(new RevenueReportExport($orders), 'revenue_report.xlsx');
        }
    }
}
