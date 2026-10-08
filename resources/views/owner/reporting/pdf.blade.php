<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $from->locale('id')->translatedFormat('F Y') }}</title>
    <style>
        *{box-sizing:border-box}
        body{font-family:Arial,Helvetica,sans-serif;color:#1f2937;background:#fff;margin:0;padding:32px}
        .toolbar{display:flex;justify-content:flex-end;gap:8px;margin-bottom:24px}
        button{border:0;border-radius:8px;padding:10px 16px;font-weight:700;cursor:pointer}
        .print{background:#1F4D3D;color:#fff}.close{background:#f3f4f6;color:#374151}
        h1{margin:0 0 6px;font-size:24px}.subtitle{color:#6b7280;margin:0 0 24px;font-size:13px}
        .summary{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
        .card{border:1px solid #e5e7eb;border-radius:10px;padding:16px}
        .label{font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:700}
        .value{font-size:19px;font-weight:700;margin-top:8px}
        section{margin-top:22px} h2{font-size:16px;margin:0 0 10px}
        table{width:100%;border-collapse:collapse;font-size:12px}
        th{background:#1F4D3D;color:#fff;text-align:left}
        th,td{border:1px solid #d1d5db;padding:8px}
        td.number,th.number{text-align:right}
        .empty{text-align:center;color:#6b7280;padding:16px}
        @media print{
            body{padding:0}
            .toolbar{display:none}
            @page{size:A4;margin:14mm}
        }
        @media(max-width:700px){.summary{grid-template-columns:1fr}body{padding:16px}}
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="close" onclick="window.close()">Tutup</button>
        <button class="print" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <h1>Laporan Penjualan - {{ $from->locale('id')->translatedFormat('F Y') }}</h1>
    <p class="subtitle">Periode {{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}</p>

    <div class="summary">
        <div class="card"><div class="label">Total Transaksi</div><div class="value">{{ number_format($summary['orders']) }}</div></div>
        <div class="card"><div class="label">Pendapatan</div><div class="value">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</div></div>
        <div class="card"><div class="label">Rata-rata Transaksi</div><div class="value">Rp {{ number_format($summary['average'], 0, ',', '.') }}</div></div>
    </div>

    <section>
        <h2>Metode Pembayaran</h2>
        <table>
            <thead><tr><th>Metode</th><th class="number">Jumlah Transaksi</th><th class="number">Total Penjualan</th></tr></thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_method === 'Cash' ? 'Tunai' : $payment->payment_method }}</td>
                        <td class="number">{{ number_format($payment->orders) }}</td>
                        <td class="number">Rp {{ number_format($payment->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">Belum ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section>
        <h2>Produk Terlaris</h2>
        <table>
            <thead><tr><th class="number">Peringkat</th><th>Produk</th><th class="number">Unit Terjual</th><th class="number">Nilai Penjualan</th></tr></thead>
            <tbody>
                @forelse($topProducts as $index => $product)
                    <tr>
                        <td class="number">{{ $index + 1 }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td class="number">{{ number_format($product->quantity) }}</td>
                        <td class="number">Rp {{ number_format($product->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Belum ada penjualan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 250);
        });
    </script>
</body>
</html>
