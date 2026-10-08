<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $report = $this->reportData($request);

        return view('owner.reporting.index', $report);
    }

    public function exportExcel(Request $request)
    {
        $report = $this->reportData($request);
        $from = $report['from'];
        $to = $report['to'];
        $summary = $report['summary'];
        $payments = $report['payments'];
        $topProducts = $report['topProducts'];

        $filename = 'Laporan Penjualan - ' . $from->translatedFormat('F Y') . '.xls';

        $e = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
        $html .= '<style>
            body{font-family:Arial,sans-serif;color:#222}
            h1,h2{margin-bottom:8px}
            p{margin:4px 0 12px}
            table{border-collapse:collapse;width:100%;margin-bottom:24px}
            th,td{border:1px solid #ccc;padding:8px}
            th{background:#1F4D3D;color:#fff}
            .number{text-align:right}
        </style></head><body>';
        $html .= '<h1>Laporan Penjualan</h1>';
        $html .= '<p>Periode: ' . $e($from->format('d M Y')) . ' - ' . $e($to->format('d M Y')) . '</p>';

        $html .= '<h2>Ringkasan</h2><table>';
        $html .= '<tr><th>Informasi</th><th>Nilai</th></tr>';
        $html .= '<tr><td>Total Transaksi</td><td class="number">' . number_format($summary['orders']) . '</td></tr>';
        $html .= '<tr><td>Pendapatan</td><td class="number">Rp ' . number_format($summary['revenue'], 0, ',', '.') . '</td></tr>';
        $html .= '<tr><td>Rata-rata Transaksi</td><td class="number">Rp ' . number_format($summary['average'], 0, ',', '.') . '</td></tr>';
        $html .= '</table>';

        $html .= '<h2>Metode Pembayaran</h2><table>';
        $html .= '<tr><th>Metode</th><th>Jumlah Transaksi</th><th>Total Penjualan</th></tr>';
        foreach ($payments as $payment) {
            $method = $payment->payment_method === 'Cash' ? 'Tunai' : $payment->payment_method;
            $html .= '<tr><td>' . $e($method) . '</td><td class="number">' . number_format($payment->orders) . '</td><td class="number">Rp ' . number_format($payment->total, 0, ',', '.') . '</td></tr>';
        }
        if ($payments->isEmpty()) {
            $html .= '<tr><td colspan="3">Belum ada transaksi pada periode ini.</td></tr>';
        }
        $html .= '</table>';

        $html .= '<h2>Produk Terlaris</h2><table>';
        $html .= '<tr><th>Peringkat</th><th>Produk</th><th>Unit Terjual</th><th>Nilai Penjualan</th></tr>';
        foreach ($topProducts as $index => $product) {
            $html .= '<tr><td class="number">' . ($index + 1) . '</td><td>' . $e($product->product_name) . '</td><td class="number">' . number_format($product->quantity) . '</td><td class="number">Rp ' . number_format($product->total, 0, ',', '.') . '</td></tr>';
        }
        if ($topProducts->isEmpty()) {
            $html .= '<tr><td colspan="4">Belum ada penjualan pada periode ini.</td></tr>';
        }
        $html .= '</table></body></html>';

        return new Response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $report = $this->reportData($request);

        return view('owner.reporting.pdf', $report);
    }

    private function reportData(Request $request): array
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $salesQuery = Sale::whereBetween('created_at', [$from, $to]);

        $summary = [
            'orders' => (clone $salesQuery)->count(),
            'revenue' => (clone $salesQuery)->sum('total'),
            'average' => (clone $salesQuery)->avg('total') ?? 0,
        ];

        $payments = (clone $salesQuery)
            ->selectRaw('payment_method, COUNT(*) as orders, SUM(total) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $topProducts = SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.created_at', [$from, $to])
            ->selectRaw('sale_items.product_id, sale_items.product_name, SUM(sale_items.quantity) as quantity, SUM(sale_items.subtotal) as total')
            ->groupBy('sale_items.product_id', 'sale_items.product_name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();

        return compact('from', 'to', 'summary', 'payments', 'topProducts');
    }
}
