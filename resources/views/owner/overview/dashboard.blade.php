@extends('layout.sidebar')
@section('title', 'Dasbor')
@section('content')

    {{-- Breadcrumb --}}
    <p class="text-sm text-gray-400 mb-2">
        Operasional <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Dasbor</span>
    </p>

    {{-- Header row: title + search + notification --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Dasbor</h1>
            <p class="text-gray-500 mt-1">Manage and monitor your sales in one page</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
                </span>
                <input type="text" placeholder="Cari..."
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm w-full sm:w-64 bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
            </div>
            <button type="button" onclick="document.getElementById('inventoryAlert')?.scrollIntoView({behavior:'smooth',block:'center'})" aria-label="Notifications" class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 relative shrink-0 bg-white transition">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-600"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9a6 6 0 1 1 12 0v4.5l1.5 3H4.5L6 13.5V9Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 19a2.5 2.5 0 0 0 5 0"/></svg>
                <span class="absolute top-2.5 right-3 w-2 h-2 rounded-full bg-[#B8632E] ring-2 ring-white"></span>
            </button>
        </div>
    </div>

    {{-- Persediaan alert --}}
    @if($lowStokProduk > 0 || $outOfStokProduk > 0)
        <div id="inventoryAlert" class="bg-[#1F4D3D] text-white rounded-2xl px-5 sm:px-6 py-4 sm:py-5 flex items-start gap-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4 2.5 20h19L12 4Z"/><path stroke-linecap="round" d="M12 10.5v4M12 17h.01"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold">Persediaan needs attention</p>
                <p class="text-sm text-emerald-50/80 mt-0.5">
                    @if($outOfStokProduk > 0) {{ $outOfStokProduk }} item(s) out of stock. @endif
                    @if($lowStokProduk > 0) {{ $lowStokProduk }} item(s) stok menipis. @endif
                </p>
            </div>
            <a href="{{ route('product') }}" class="shrink-0 inline-flex items-center rounded-xl bg-white/10 hover:bg-white/15 px-3.5 py-2 text-xs font-semibold transition">Periksa stok</a>
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-9 h-9 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1F4D3D" stroke-width="1.5" class="w-5 h-5"><circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v9M14.5 9.7c0-1-1-1.7-2.5-1.7s-2.5.8-2.5 1.8c0 2.6 5 1.3 5 3.9 0 1-1 1.8-2.5 1.8s-2.5-.7-2.5-1.7"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Hari ini Penjualan</span>
            </div>
            <p class="font-['Space_Grotesk'] font-bold text-2xl text-gray-900">Rp {{ number_format($todayPenjualan, 0, ",", ".") }}</p>
            <p class="text-xs text-gray-400 mt-1">Penjualan recorded today</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-9 h-9 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1F4D3D" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4h6a1 1 0 0 1 1 1v1h1a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1V5a1 1 0 0 1 1-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6M9 15h6"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Total Penjualan</span>
            </div>
            <p class="font-['Space_Grotesk'] font-bold text-2xl text-gray-900">{{ number_format($totalPenjualan) }} Penjualan</p>
            <p class="text-xs text-gray-400 mt-1">All recorded transactions</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-9 h-9 rounded-lg bg-[#B8632E]/10 flex items-center justify-center shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#B8632E" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4 2.5 20h19L12 4Z"/><path stroke-linecap="round" d="M12 10.5v4M12 17h.01"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Running Menipis</span>
            </div>
            <p class="font-['Space_Grotesk'] font-bold text-2xl text-gray-900">{{ $lowStokProduk }} Produk</p>
            <p class="text-xs text-[#B8632E] mt-1 font-medium">Please restock your items</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m3.5 7.5 8.5-4 8.5 4-8.5 4-8.5-4Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.5 7.5v9l8.5 4 8.5-4v-9"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Habis of Stoks</span>
            </div>
            <p class="font-['Space_Grotesk'] font-bold text-2xl text-gray-900">{{ $outOfStokProduk }} Produk</p>
            <p class="text-xs text-gray-400 mt-1">All items in stock</p>
        </div>

    </div>

    {{-- Chart + Transaksi history --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900">Hari ini Transaksi</h3>
                <span class="font-['IBM_Plex_Mono'] text-[11px] text-gray-400 tracking-wider uppercase">Live</span>
            </div>
            <div class="relative h-[230px]">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900">Transaksi Riwayat</h3>
                <a href="{{ route('transaction') }}" class="text-sm text-[#1F4D3D] font-medium hover:underline">Lihat semua</a>
            </div>

            <ul class="divide-y divide-gray-100">
                @foreach ($recentPenjualan as $trx)
                    <li class="flex items-center gap-4 py-3.5 first:pt-0 last:pb-0">
                        <div class="w-10 h-10 rounded-full bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#1F4D3D" stroke-width="1.5" class="w-5 h-5"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8.5 12.5 2.3 2.3 4.7-5"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Payment via {{ $trx->payment_method }}</p>
                            <p class="font-['IBM_Plex_Mono'] text-[11px] text-gray-400 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-semibold text-gray-900">+ Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                            <span class="inline-block mt-1 text-[11px] font-medium text-[#1F4D3D] bg-[#1F4D3D]/10 px-2 py-0.5 rounded-full">Success</span>
                        </div>
                    </li>
                @endforeach
                @if ($recentPenjualan->isEmpty())<li class="py-8 text-center text-sm text-gray-400">No transactions yet.</li>@endif
            </ul>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart');

        // Jaga-jaga: hapus instance chart lama kalau script ini sempat
        // ter-load dua kali (misal karena hot-reload), supaya tidak dobel render.
        const existingChart = Chart.getChart(ctx);
        if (existingChart) {
            existingChart.destroy();
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Transaksi',
                    data: @json($chartData),
                    borderColor: '#1F4D3D',
                    backgroundColor: 'rgba(31, 77, 61, 0.08)',
                    tension: 0.35,
                    fill: true,
                    pointKembaligroundColor: '#1F4D3D',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                }]
            },
            options: {
                // responsive + maintainAspectRatio:false MEMBUTUHKAN parent
                // dengan tinggi tetap (lihat div "relative h-[230px]" di atas).
                // Tanpa itu, canvas & parent akan saling resize tanpa henti.
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F1F1' }, ticks: { font: { family: 'Inter', size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } }
                }
            }
        });
    </script>

@endsection