@extends('layout.sidebar')
@section('title', 'Laporan')
@section('content')
<div class="-m-5 md:-m-6 lg:-m-8 min-h-screen bg-[#F7F9F7] p-5 md:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <p class="text-sm text-gray-400 mb-2">
            Operasional <span class="mx-1">&gt;</span>
            <span class="text-gray-900 font-medium">Laporan</span>
        </p>

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
            <div>
                <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Laporan</h1>
                <p class="text-gray-500 mt-1">Ringkasan penjualan berdasarkan periode yang dipilih</p>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-2.5">
                <form method="GET" action="{{ route('reporting') }}" class="flex flex-wrap items-end gap-2.5 bg-white rounded-2xl border border-gray-200 p-3 shadow-sm">

                <div>
                    <label for="from" class="block text-xs font-medium text-gray-500 mb-1.5">Dari</label>
                    <input id="from" name="from" type="date" value="{{ $from->toDateString() }}"
                        class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent">
                </div>
                <div>
                    <label for="to" class="block text-xs font-medium text-gray-500 mb-1.5">Sampai</label>
                    <input id="to" name="to" type="date" value="{{ $to->toDateString() }}"
                        class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1F4D3D] text-white text-sm font-semibold hover:bg-[#173B2F] transition">
                    Terapkan
                </button>
                @if(request()->filled('from') || request()->filled('to'))
                    <a href="{{ route('reporting') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">
                        Atur Ulang
                    </a>
                @endif
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('reporting.export.excel', request()->only('from', 'to')) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-[#1F4D3D]/20 bg-white text-[#1F4D3D] text-sm font-semibold hover:bg-[#1F4D3D]/5 transition">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v16H5zM8 8l3 4-3 4M13 16h3"/></svg>
                        Excel
                    </a>
                    <a href="{{ route('reporting.export.pdf', request()->only('from', 'to')) }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-[#B8632E]/20 bg-white text-[#B8632E] text-sm font-semibold hover:bg-[#B8632E]/5 transition">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h9l3 3v13H6zM14 4v4h4M9 12h6M9 15h6"/></svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-5 flex items-center gap-2 text-xs text-gray-500">
            <span class="w-2 h-2 rounded-full bg-[#1F4D3D]"></span>
            Periode laporan:
            <span class="font-semibold text-gray-700">{{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#1F4D3D]"></div>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                        <p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-gray-900">{{ number_format($summary['orders']) }}</p>
                        <p class="mt-1 text-xs text-gray-400">Transaksi tercatat</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#1F4D3D]/10 text-[#1F4D3D] flex items-center justify-center shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12v16H6zM9 8h6M9 12h6M9 16h4"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#B8632E]"></div>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendapatan</p>
                        <p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-gray-900">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-400">Total penjualan periode ini</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#B8632E]/10 text-[#B8632E] flex items-center justify-center shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-5 h-5"><circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" d="M12 7v10M14.5 9.5c0-1-1-2-2.5-2s-2.5.8-2.5 1.8c0 2.5 5 1.3 5 3.8 0 1-1 1.9-2.5 1.9s-2.5-.9-2.5-1.9"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#5C8B75]"></div>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Transaksi</p>
                        <p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-gray-900">Rp {{ number_format($summary['average'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-400">Nilai rata-rata per transaksi</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#5C8B75]/10 text-[#5C8B75] flex items-center justify-center shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-5 h-5"><path stroke-linecap="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <section class="mt-5 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-['Space_Grotesk'] font-semibold text-gray-900">Daftar Transaksi</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Seluruh transaksi pada periode yang dipilih</p>
                </div>
                <span class="text-xs font-semibold text-[#1F4D3D] bg-[#1F4D3D]/10 px-3 py-1.5 rounded-full">
                    {{ number_format($sales->count()) }} transaksi
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-semibold text-gray-500">
                            <th class="px-5 py-3">No.</th>
                            <th class="px-5 py-3">Nomor Faktur</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Kasir</th>
                            <th class="px-5 py-3">Pembayaran</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sales as $index => $sale)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-3 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 font-semibold text-gray-800 whitespace-nowrap">{{ $sale->invoice_number }}</td>
                                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $sale->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $sale->user?->name ?? 'Sistem' }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $sale->payment_method === 'Cash' ? 'Tunai' : $sale->payment_method }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <p class="text-sm font-medium text-gray-500">Belum ada transaksi</p>
                                    <p class="text-xs text-gray-400 mt-1">Tidak ada transaksi pada periode yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-['Space_Grotesk'] font-semibold text-gray-900">Metode Pembayaran</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Distribusi transaksi berdasarkan metode pembayaran</p>
                    </div>
                    <span class="w-9 h-9 rounded-xl bg-[#1F4D3D]/10 text-[#1F4D3D] flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4.5 h-4.5"><rect x="3" y="5.5" width="18" height="13" rx="2"/><path stroke-linecap="round" d="M3 10h18"/></svg>
                    </span>
                </div>
                <div class="p-5">
                    @php $paymentTotal = max((int) $summary['orders'], 1); @endphp
                    @forelse($payments as $payment)
                        @php $percentage = round(($payment->orders / $paymentTotal) * 100); @endphp
                        <div class="mb-5 last:mb-0">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $payment->payment_method === 'Cash' ? 'Tunai' : $payment->payment_method }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $payment->orders }} transaksi</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->total, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-400">{{ $percentage }}%</p>
                                </div>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full bg-[#1F4D3D]" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <p class="text-sm font-medium text-gray-500">Belum ada transaksi</p>
                            <p class="text-xs text-gray-400 mt-1">Tidak ada data pembayaran pada periode ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-['Space_Grotesk'] font-semibold text-gray-900">Produk Terlaris</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Produk dengan jumlah unit terjual terbanyak</p>
                    </div>
                    <span class="w-9 h-9 rounded-xl bg-[#B8632E]/10 text-[#B8632E] flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 20V10M12 20V4M19 20v-7"/></svg>
                    </span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topProducts as $index => $product)
                        <div class="px-5 py-3.5 flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg {{ $index === 0 ? 'bg-[#1F4D3D] text-white' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center text-xs font-bold shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->product_name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Rp {{ number_format($product->total, 0, ',', '.') }} nilai penjualan</p>
                            </div>
                            <span class="text-xs font-semibold text-[#1F4D3D] bg-[#1F4D3D]/10 px-2.5 py-1.5 rounded-full whitespace-nowrap">
                                {{ number_format($product->quantity) }} unit
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <p class="text-sm font-medium text-gray-500">Belum ada penjualan</p>
                            <p class="text-xs text-gray-400 mt-1">Produk terlaris akan muncul setelah transaksi tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection