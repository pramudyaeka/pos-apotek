<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $sale->invoice_number }}</title>
    <style>
        body{font-family:Arial,sans-serif;max-width:420px;margin:32px auto;padding:0 16px;color:#111}
        h1{text-align:center;font-size:20px;margin-bottom:4px}.muted{color:#666;font-size:12px;text-align:center}
        table{width:100%;border-collapse:collapse;margin:24px 0}td{padding:7px 0;font-size:13px}td:last-child{text-align:right}
        .total{border-top:1px dashed #999;font-weight:700;padding-top:12px}.actions{text-align:center}
        button{padding:10px 18px;border:0;border-radius:8px;background:#1F4D3D;color:white;cursor:pointer}
        @media print{.actions{display:none}body{margin:0;max-width:none}}
    </style>
</head>
<body>
    <h1>Apotek</h1>
    <p class="muted">{{ $sale->invoice_number }} · {{ $sale->created_at->format('d/m/Y H:i') }}</p>
    <p class="muted">Cashier: {{ $sale->user?->name ?? '-' }}</p>
    <table>
        @foreach($sale->items as $item)
            <tr><td>{{ $item->quantity }} × {{ $item->product_name }}</td><td>Rp {{ number_format($item->subtotal,0,',','.') }}</td></tr>
        @endforeach
        <tr><td>Subtotal</td><td>Rp {{ number_format($sale->subtotal,0,',','.') }}</td></tr>
        <tr><td>Pembayaran</td><td>{{ $sale->payment_method === 'Cash' ? 'Tunai' : $sale->payment_method }}</td></tr>
        @if($sale->amount_received !== null)
            <tr><td>Uang diterima</td><td>Rp {{ number_format($sale->amount_received,0,',','.') }}</td></tr>
            <tr><td>Kembalian</td><td>Rp {{ number_format($sale->change_amount ?? 0,0,',','.') }}</td></tr>
        @endif
        <tr class="total"><td>Total</td><td>Rp {{ number_format($sale->total,0,',','.') }}</td></tr>
    </table>
    <div class="actions"><button onclick="window.print()">Print Receipt</button></div>
</body>
</html>