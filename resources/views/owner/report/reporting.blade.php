@extends('layout.sidebar')
@section('title', 'Reporting')
@section('content')

    {{--
        Wrapper ini sengaja diberi background sendiri (menegasikan padding
        default dari <main> di layout, lalu menerapkannya lagi di sini)
        supaya seluruh halaman punya latar abu-hijau lembut — bukan putih
        polos seperti halaman lain — sehingga kartu putih di atasnya benar-benar
        kontras/"mengambang", bukan menyatu dengan background.
    --}}
    <div class="-m-5 md:-m-6 lg:-m-8 p-5 md:p-6 lg:p-8 min-h-[calc(100vh-4rem)] md:min-h-screen">

        {{-- Breadcrumb --}}
        <p class="text-sm text-gray-400 mb-2">
            Main Menu <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Reporting</span>
        </p>

        {{-- Header + period picker --}}
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-5">
            <div>
                <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Reporting</h1>
                <p class="text-gray-500 mt-1">Manage and monitoring your sales in one page</p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="flex items-center gap-2.5 border border-gray-200 rounded-xl px-4 py-2.5 bg-white">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5 text-gray-500 shrink-0"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3.5 9.5h17"/></svg>
                    <input type="month" value="2026-08" class="text-sm font-medium text-gray-700 bg-transparent focus:outline-none">
                </div>
                <button class="px-6 py-2.5 text-sm font-semibold text-white bg-[#1F4D3D] hover:bg-[#173B2F] rounded-xl transition whitespace-nowrap">
                    Load
                </button>
            </div>
        </div>

        {{--
            1. KPI ROW — prioritas pertama pengguna: angka + pembanding
            periode sebelumnya (naik/turun), bukan sekadar angka mentah.
            Dibuat full-width, bukan berbagi kolom dengan chart lain.
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

            {{-- Total Sales — kartu hero, solid warna --}}
            <div class="bg-[#1F4D3D] rounded-2xl p-4 relative overflow-hidden shadow-sm">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-white/5"></div>
                <div class="flex items-center gap-2.5 mb-3 relative">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v9M14.5 9.7c0-1-1-1.7-2.5-1.7s-2.5.8-2.5 1.8c0 2.6 5 1.3 5 3.9 0 1-1 1.8-2.5 1.8s-2.5-.7-2.5-1.7"/></svg>
                    </div>
                    <span class="text-sm font-medium text-emerald-100">Total Sales</span>
                </div>
                <p class="font-['Space_Grotesk'] font-bold text-xl text-white relative">Rp 68.500.000</p>
                <div class="flex items-center gap-1 mt-1.5 relative">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#86EFC4" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6-6 6 6"/></svg>
                    <span class="text-xs font-semibold text-[#86EFC4]">+12.4%</span>
                    <span class="text-xs text-emerald-200/60">vs last month</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#3E7A6420">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#3E7A64" stroke-width="1.5" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4h6a1 1 0 0 1 1 1v1h1a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1V5a1 1 0 0 1 1-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6M9 15h6"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Total Orders</span>
                </div>
                <p class="font-['Space_Grotesk'] font-bold text-xl text-gray-900">312 Orders</p>
                <div class="flex items-center gap-1 mt-1.5">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#3E7A64" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6-6 6 6"/></svg>
                    <span class="text-xs font-semibold text-[#3E7A64]">+3.1%</span>
                    <span class="text-xs text-gray-400">vs last month</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#B8632E1A">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#B8632E" stroke-width="1.5" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="m3.5 7.5 8.5-4 8.5 4v9l-8.5 4-8.5-4v-9Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.5 7.5 12 11.5m0 0 8.5-4M12 11.5V20"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Total Profit</span>
                </div>
                <p class="font-['Space_Grotesk'] font-bold text-xl text-gray-900">Rp 21.200.000</p>
                <div class="flex items-center gap-1 mt-1.5">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#B8632E" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                    <span class="text-xs font-semibold text-[#B8632E]">-2.5%</span>
                    <span class="text-xs text-gray-400">vs last month</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#7FA89530">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#5C8B75" stroke-width="1.5" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h13m0 0-3-3m3 3-3 3M20 17H7m0 0 3 3m-3-3 3-3"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Avg. Order Value</span>
                </div>
                <p class="font-['Space_Grotesk'] font-bold text-xl text-gray-900">Rp 219.500</p>
                <div class="flex items-center gap-1 mt-1.5">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#5C8B75" stroke-width="2" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6-6 6 6"/></svg>
                    <span class="text-xs font-semibold text-[#5C8B75]">+0.8%</span>
                    <span class="text-xs text-gray-400">vs last month</span>
                </div>
            </div>
        </div>

        {{--
            2. SALES CHART — prioritas kedua: tren. Diberi ruang penuh
            (full-width), bukan lagi berbagi kolom dengan Payment Method.
        --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900 text-[15px]">Sales Chart</h3>
                <span class="font-['IBM_Plex_Mono'] text-[11px] text-gray-400 tracking-wider uppercase">Aug 2026</span>
            </div>
            <div class="relative min-h-[260px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{--
            3. BREAKDOWN — prioritas ketiga: komposisi pendukung
            (kategori & metode bayar), sejajar sama besar.
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900 mb-3 text-[15px]">Categories Statistics</h3>
                <div class="relative flex-1 min-h-[180px]">
                    <canvas id="categoriesChart"></canvas>
                </div>
                <div id="categoriesLegend" class="flex flex-wrap gap-x-3 gap-y-1.5 mt-3"></div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900 mb-4 text-[15px]">Payment Method</h3>
                <div class="space-y-4 flex-1">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#1F4D3D" stroke-width="1.5" class="w-4.5 h-4.5"><rect x="3" y="6" width="18" height="13" rx="2"/><path stroke-linecap="round" d="M3 10h18"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">Cash</span>
                                <span class="text-gray-500 font-['IBM_Plex_Mono'] text-xs">68%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full bg-[#1F4D3D]" style="width: 68%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#B8632E]/10 flex items-center justify-center shrink-0">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#B8632E" stroke-width="1.5" class="w-4.5 h-4.5"><rect x="3.5" y="3.5" width="7" height="7" rx="1"/><rect x="13.5" y="3.5" width="7" height="7" rx="1"/><rect x="3.5" y="13.5" width="7" height="7" rx="1"/><path stroke-linecap="round" d="M14 16h3.5M14 19.5h6.5M20.5 14v3"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">QRIS</span>
                                <span class="text-gray-500 font-['IBM_Plex_Mono'] text-xs">32%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full bg-[#B8632E]" style="width: 32%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-5">Based on 312 transactions this period.</p>
            </div>
        </div>

        {{--
            4. ACTIONABLE LIST — prioritas terakhir: dipakai untuk keputusan
            restock, bukan untuk memahami performa secara umum.
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900 mb-3 text-[15px]">Best Selling Products</h3>
                <ul class="divide-y divide-gray-100">
                    @php
                        $bestSelling = [
                            ['name' => 'Nablutril', 'qty' => 142],
                            ['name' => 'Bolipristin', 'qty' => 118],
                            ['name' => 'Tropiprazole', 'qty' => 97],
                            ['name' => 'Preditirelin', 'qty' => 81],
                            ['name' => 'Somikalim', 'qty' => 76],
                        ];
                        $rankStyles = [
                            1 => 'bg-[#C9A227] text-white',
                            2 => 'bg-gray-400 text-white',
                            3 => 'bg-[#B8632E] text-white',
                        ];
                    @endphp
                    @foreach ($bestSelling as $i => $p)
                        <li class="flex items-center gap-3 py-2.5 first:pt-0 last:pb-0">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 {{ $rankStyles[$i + 1] ?? 'bg-gray-100 text-gray-400' }}">
                                {{ $i + 1 }}
                            </span>
                            <span class="flex-1 text-sm font-medium text-gray-900">{{ $p['name'] }}</span>
                            <span class="text-xs font-semibold text-[#1F4D3D] bg-[#1F4D3D]/10 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $p['qty'] }} sold</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-['Space_Grotesk'] font-semibold text-gray-900 mb-3 text-[15px]">Least Selling Products</h3>
                <ul class="divide-y divide-gray-100">
                    @php
                        $leastSelling = [
                            ['name' => 'Virafibatide', 'qty' => 3],
                            ['name' => 'Metildopa', 'qty' => 5],
                            ['name' => 'Dexaven', 'qty' => 6],
                            ['name' => 'Klorfenamin', 'qty' => 9],
                            ['name' => 'Pegamostim', 'qty' => 11],
                        ];
                    @endphp
                    @foreach ($leastSelling as $i => $p)
                        <li class="flex items-center gap-3 py-2.5 first:pt-0 last:pb-0">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 bg-gray-100 text-gray-400">
                                {{ $i + 1 }}
                            </span>
                            <span class="flex-1 text-sm font-medium text-gray-900">{{ $p['name'] }}</span>
                            <span class="text-xs font-semibold text-[#B8632E] bg-[#B8632E]/10 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $p['qty'] }} sold</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ---------- Sales Chart (dengan gradient fill) ----------
        const salesCtx = document.getElementById('salesChart');
        const existingSalesChart = Chart.getChart(salesCtx);
        if (existingSalesChart) existingSalesChart.destroy();

        const salesGradient = salesCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
        salesGradient.addColorStop(0, 'rgba(31, 77, 61, 0.35)');
        salesGradient.addColorStop(1, 'rgba(31, 77, 61, 0)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Sales',
                    data: [14500000, 16800000, 15200000, 22000000],
                    borderColor: '#1F4D3D',
                    borderWidth: 2.5,
                    backgroundColor: salesGradient,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#1F4D3D',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F1F1' },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            callback: (value) => 'Rp ' + (value / 1000000) + 'jt'
                        }
                    },
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } }
                }
            }
        });

        // ---------- Categories Statistics (doughnut) ----------
        const categoriesCtx = document.getElementById('categoriesChart');
        const existingCategoriesChart = Chart.getChart(categoriesCtx);
        if (existingCategoriesChart) existingCategoriesChart.destroy();

        const categoriesData = [
            { label: 'Pain Relief', value: 39, color: '#1F4D3D' },
            { label: 'Digestive',   value: 31, color: '#3E7A64' },
            { label: 'Vitamin',     value: 22, color: '#7FA895' },
            { label: 'Cold & Flu',  value: 12, color: '#B8632E' },
            { label: 'Antiviral',   value: 6,  color: '#E0C097' },
        ];

        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: categoriesData.map(c => c.label),
                datasets: [{
                    data: categoriesData.map(c => c.value),
                    backgroundColor: categoriesData.map(c => c.color),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: { legend: { display: false } }
            }
        });

        // Legend manual, supaya bisa dikustom stylenya (bukan legend bawaan Chart.js)
        const legendContainer = document.getElementById('categoriesLegend');
        legendContainer.innerHTML = categoriesData.map(c => `
            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                <span class="w-2 h-2 rounded-full shrink-0" style="background:${c.color}"></span>
                ${c.label}
            </div>
        `).join('');
    </script>

@endsection