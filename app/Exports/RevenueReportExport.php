<?php 

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueReportExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders->map(function($order) {
            return [
                'Order ID' => $order->id,
                'Customer Name' => $order->to_user_name,
                'Total Amount' => $order->price_new,
                'Order Date' => $order->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Total Amount',
            'Order Date',
        ];
    }
}
