@extends('layout.sidebar')

@section('title', 'Riwayat')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-['Space_Grotesk'] font-semibold">Riwayat</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor aktivitas pengguna dan perubahan stok pada sistem.</p>
    </div>

    <section class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
            <div>
                <h2 class="font-['Space_Grotesk'] font-semibold text-lg">Activity Riwayat</h2>
                <p class="text-xs text-gray-400 mt-1">Catatan perubahan data dan aktivitas sistem.</p>
            </div>
            <form method="GET" action="{{ route('history') }}" class="flex flex-col sm:flex-row gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..."
                    class="w-full sm:w-56 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]/20">
                <select name="module" class="px-3 py-2 rounded-xl border border-gray-200 text-sm">
                    <option value="all">Semua modul</option>
                    @foreach(['Authentication' => 'Authentication','Kategori' => 'Kategori','Produk' => 'Produk','Pengguna' => 'Pengguna','Sale' => 'Sale'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('module') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="action" class="px-3 py-2 rounded-xl border border-gray-200 text-sm">
                    <option value="all">Semua aksi</option>
                    @foreach(['create'=>'Create','update'=>'Update','delete'=>'Hapus','sale'=>'Sale','login'=>'Login','logout'=>'Logout'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('action') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2 rounded-xl bg-[#1F4D3D] text-white text-sm font-medium hover:opacity-90 transition">Filter</button>
                @if(request()->hasAny(['search','module','action']))<a href="{{ route('history', ['stock_type'=>request('stock_type','all')]) }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">Hapus filter</a>@endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left text-xs text-gray-400 uppercase tracking-wide">
                        <th class="py-3 pr-4">Tanggal</th>
                        <th class="py-3 pr-4">Pengguna</th>
                        <th class="py-3 pr-4">Modul</th>
                        <th class="py-3 pr-4">Aksi</th>
                        <th class="py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr class="border-b border-gray-50">
                            <td class="py-3 pr-4 whitespace-nowrap text-gray-500">
                                {{ $activity->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3 pr-4 font-medium">{{ $activity->user?->name ?? 'System' }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs">{{ $activity->module }}</span>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="px-2.5 py-1 rounded-full bg-[#1F4D3D]/10 text-[#1F4D3D] text-xs font-medium">
                                    {{ ucfirst($activity->action) }}
                                </span>
                            </td>
                            <td class="py-3 text-gray-600">{{ $activity->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-gray-400">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="mt-4">{{ $activities->links() }}</div>
        @endif
    </section>

    <section class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="mb-5">
            <h2 class="font-['Space_Grotesk'] font-semibold text-lg">Stok Movement Riwayat</h2>
            <p class="text-xs text-gray-400 mt-1">Riwayat barang masuk, keluar, dan penyesuaian stok.</p>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <p class="text-xs text-gray-400">{{ $activities->total() }} aktivitas tercatat</p>
            <div class="flex gap-2">
            <a href="{{ route('history', array_merge(request()->query(), ['stock_type'=>'all'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs {{ request('stock_type', 'all') === 'all' ? 'bg-[#1F4D3D] text-white' : 'bg-gray-100 text-gray-600' }}">All</a>
            <a href="{{ route('history', array_merge(request()->query(), ['stock_type'=>'IN'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs {{ request('stock_type') === 'IN' ? 'bg-[#1F4D3D] text-white' : 'bg-gray-100 text-gray-600' }}">IN</a>
            <a href="{{ route('history', array_merge(request()->query(), ['stock_type'=>'OUT'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs {{ request('stock_type') === 'OUT' ? 'bg-[#1F4D3D] text-white' : 'bg-gray-100 text-gray-600' }}">OUT</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left text-xs text-gray-400 uppercase tracking-wide">
                        <th class="py-3 pr-4">Tanggal</th><th class="py-3 pr-4">Produk</th><th class="py-3 pr-4">Pengguna</th>
                        <th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Qty</th><th class="py-3">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockMovements as $movement)
                        <tr class="border-b border-gray-50">
                            <td class="py-3 pr-4 whitespace-nowrap text-gray-500">{{ $movement->created_at->format('d M Y H:i') }}</td>
                            <td class="py-3 pr-4 font-medium">{{ $movement->product?->name ?? 'Hapusd product' }}</td>
                            <td class="py-3 pr-4">{{ $movement->user?->name ?? 'System' }}</td>
                            <td class="py-3 pr-4"><span class="px-2.5 py-1 rounded-full {{ $movement->type === 'IN' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }} text-xs">{{ $movement->type }}</span></td>
                            <td class="py-3 pr-4">{{ $movement->quantity }}</td>
                            <td class="py-3">{{ $movement->stock_before }} → {{ $movement->stock_after }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-gray-400">Belum ada pergerakan stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockMovements->hasPages())
            <div class="mt-4">{{ $stockMovements->links() }}</div>
        @endif
    </section>
</div>
@endsection
