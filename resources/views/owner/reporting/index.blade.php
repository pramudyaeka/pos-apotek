@extends('layout.sidebar')
@section('title', 'Laporan')
@section('content')
<div>
    <p class="text-sm text-gray-400 mb-2">Operasional <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Laporan</span></p>
    <div class="mb-6">
        <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Laporan</h1>
        <p class="text-gray-500 mt-1">Ringkasan penjualan untuk periode yang dipilih</p>
    </div>

    <form method="GET" action="{{ route('reporting') }}" class="flex flex-wrap items-end gap-3 mb-6">
        <div>
            <label for="from" class="block text-xs font-medium text-gray-500 mb-1.5">Dari</label>
            <input id="from" name="from" type="date" value="{{ $from->toDateString() }}" class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]">
        </div>
        <div>
            <label for="to" class="block text-xs font-medium text-gray-500 mb-1.5">Sampai</label>
            <input id="to" name="to" type="date" value="{{ $to->toDateString() }}" class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]">
        </div>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1F4D3D] text-white text-sm font-semibold hover:bg-[#173B2F] transition">Terapkan</button>
        @if(request()->filled('from') || request()->filled('to'))
            <a href="{{ route('reporting') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">Atur Ulang</a>
        @endif
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl border border-gray-100 bg-white p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Transaksi</p><p class="mt-2 text-2xl font-bold">{{ number_format($summary['orders']) }}</p></div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Pendapatan</p><p class="mt-2 text-2xl font-bold">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p></div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Rata-rata Transaksi</p><p class="mt-2 text-2xl font-bold">Rp {{ number_format($summary['average'], 0, ',', '.') }}</p></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100"><h2 class="font-semibold">Metode Pembayaran</h2></div>
            <div class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                    <div class="px-6 py-4 flex items-center justify-between"><span class="text-sm font-medium">{{ $payment->payment_method === 'Cash' ? 'Tunai' : $payment->payment_method }}</span><span class="text-sm text-gray-500">{{ $payment->orders }} transaksi · Rp {{ number_format($payment->total, 0, ',', '.') }}</span></div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-gray-400">Tidak ada transaksi pada periode ini.</p>
                @endforelse
            </div>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100"><h2 class="font-semibold">Produk Terlaris</h2></div>
            <div class="divide-y divide-gray-100">
                @forelse($topProducts as $product)
                    <div class="px-6 py-4 flex items-center justify-between gap-4"><span class="text-sm font-medium truncate">{{ $product->product_name }}</span><span class="text-sm text-gray-500 whitespace-nowrap">{{ $product->quantity }} terjual · Rp {{ number_format($product->total, 0, ',', '.') }}</span></div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-gray-400">Tidak ada penjualan produk pada periode ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
