<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function index(Request $request)
    {
        $outlet = current_outlet();
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $stats = $this->reportService->periodStats($outlet->id, $from, $to);
        $topItems = $this->reportService->topItemsForPeriod($outlet->id, $from, $to);
        $recentOrders = $this->reportService->ordersQuery($outlet->id, $from, $to)->limit(15)->get();

        return view('admin.reports.index', compact('outlet', 'from', 'to', 'stats', 'topItems', 'recentOrders'));
    }

    public function export(Request $request): StreamedResponse
    {
        $outlet = current_outlet();
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $orders = $this->reportService->ordersQuery($outlet->id, $from, $to)->get();
        $filename = 'laporan-' . $from . '-' . $to . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No Order', 'Tanggal', 'Pelanggan', 'Status', 'Metode Bayar', 'Total (Rp)']);

            foreach ($orders as $order) {
                fputcsv($out, [
                    $order->order_number,
                    $order->created_at?->format('Y-m-d H:i'),
                    $order->displayCustomer(),
                    $order->status,
                    $order->payment_method ?? '-',
                    $order->total,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
