@extends('layout.sidebar')
@section('title', 'Transaksi')
@section('content')

    <div x-data="transactionLogic()">

        {{-- Breadcrumb & Header --}}
        <p class="text-sm text-gray-400 mb-2">
            Menu Utama <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Transaksi</span>
        </p>

        <div class="mb-6">
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Transaksi</h1>
            <p class="text-gray-500 mt-1">Kelola dan pantau penjualan dalam satu halaman</p>
        </div>

        {{--
            Grid utama: tabel (kiri) + panel detail (kanan).
            Kolom kanan (380px) HANYA dialokasikan saat ada transaksi
            terpilih — sebelum itu, tabel memakai lebar penuh (grid-cols-1).
        --}}
        <div class="grid grid-cols-1 gap-6 items-start"
            :class="selectedTransaksi ? 'xl:grid-cols-[1fr_380px]' : 'xl:grid-cols-1'">

            {{-- Kolom kiri: action bar + tabel --}}
            <div class="min-w-0">

                {{-- Cari, Urutkan, Filter --}}
                <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 mb-4">
                    <div class="relative flex-1 w-full min-w-[200px]">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
                        </span>
                        <input type="text" x-model="searchQuery" placeholder="Cari..."
                            class="pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm w-full bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>

                    <div class="flex flex-wrap gap-2 shrink-0">
                        <select x-model="sortBy" aria-label="Urutkan transaksi" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]/20">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="highest">Nominal terbesar</option>
                            <option value="lowest">Nominal terkecil</option>
                        </select>
                        <select x-model="methodFilter" aria-label="Filter metode pembayaran" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]/20">
                            <option value="all">Semua metode</option>
                            <option value="Cash">Tunai</option>
                            <option value="Debit">Debit</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                </div>

                {{--
                    Tabel transaksi.
                    "overflow-x-auto" & "min-w" SENGAJA dihilangkan dari wrapper —
                    saat panel detail terbuka dan lebar kolom kiri menyempit,
                    ukuran font & padding sel diperkecil (lihat :class di tiap
                    th/td) supaya semua kolom tetap muat tanpa perlu discroll.
                --}}
                <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">
                    <table class="w-full text-left text-gray-700" :class="selectedTransaksi ? 'text-xs' : 'text-sm'">
                        <thead class="font-['IBM_Plex_Mono'] tracking-widest text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100"
                            :class="selectedTransaksi ? 'text-[9px]' : 'text-[11px]'">
                            <tr>
                                <th scope="col" class="font-medium whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2.5' : 'px-6 py-4'">#</th>
                                <th scope="col" class="font-medium whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2.5' : 'px-6 py-4'">Tanggal</th>
                                <th scope="col" class="font-medium whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2.5' : 'px-6 py-4'">Nomor Faktur</th>
                                <th scope="col" class="font-medium whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2.5' : 'px-6 py-4'">Metode</th>
                                <th scope="col" class="font-medium whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2.5' : 'px-6 py-4'">Nominal</th>
                                <th scope="col" class="font-medium whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2.5' : 'px-6 py-4'">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(trx, index) in filteredTransaksis()" :key="trx.id">
                                <tr @click="selectedTransaksi = trx"
                                    :class="selectedTransaksi && selectedTransaksi.id === trx.id ? 'bg-[#1F4D3D]/5' : 'hover:bg-gray-50'"
                                    class="transition-colors cursor-pointer">
                                    <td class="whitespace-nowrap text-gray-400" :class="selectedTransaksi ? 'px-3 py-2' : 'px-6 py-4'" x-text="index + 1"></td>
                                    <td class="whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2' : 'px-6 py-4'" x-text="trx.date"></td>
                                    <td class="whitespace-nowrap font-['IBM_Plex_Mono'] text-gray-500" :class="selectedTransaksi ? 'px-3 py-2' : 'px-6 py-4 text-[13px]'" x-text="trx.invoice"></td>
                                    <td class="whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2' : 'px-6 py-4'" x-text="trx.method"></td>
                                    <td class="whitespace-nowrap font-semibold text-gray-900" :class="selectedTransaksi ? 'px-3 py-2' : 'px-6 py-4'" x-text="formatRupiah(trx.amount)"></td>
                                    <td class="whitespace-nowrap" :class="selectedTransaksi ? 'px-3 py-2' : 'px-6 py-4'">
                                        <button @click.stop="selectedTransaksi = trx" class="font-semibold text-[#1F4D3D] hover:underline">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="mt-4 flex justify-center">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

            {{-- Kolom kanan: panel detail transaksi --}}
            <div x-show="selectedTransaksi !== null"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-6"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="bg-[#F5F6F4] rounded-2xl border border-gray-100 xl:sticky xl:top-6 flex flex-col max-h-[calc(100vh-160px)]">

                <template x-if="selectedTransaksi">
                    <div class="flex flex-col h-full overflow-y-auto">
                        <div class="px-6 pt-6 pb-2">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900">Detail Transaksi</h2>
                                <button @click="selectedTransaksi = null" aria-label="Tutup" class="text-gray-400 hover:text-gray-600 shrink-0">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                                </button>
                            </div>

                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-full bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#1F4D3D" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z"/></svg>
                                </div>
                                <span class="text-gray-500 text-sm">Penjualan</span>
                            </div>

                            <p class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900 mb-6" x-text="'+ ' + formatRupiah(selectedTransaksi.amount)"></p>

                            {{-- Detail list --}}
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Status</span>
                                    <span class="text-[11px] font-medium text-[#1F4D3D] bg-[#1F4D3D]/10 px-2.5 py-1 rounded-full" x-text="selectedTransaksi.status"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Waktu</span>
                                    <span class="text-sm font-medium text-gray-900 font-['IBM_Plex_Mono'] text-[13px]" x-text="selectedTransaksi.time"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Metode Pembayaran</span>
                                    <span class="text-sm font-medium text-gray-900" x-text="selectedTransaksi.method"></span>
                                </div>
                            </div>

                            <hr class="my-5 border-gray-200">

                            {{-- Summary items --}}
                            <div>
                                <p class="text-sm text-gray-500 mb-3">Ringkasan Transaksi</p>
                                <div class="space-y-2.5">
                                    <template x-for="(item, itemIndex) in selectedTransaksi.items" :key="item.product_id + '-' + itemIndex">
                                        <div class="flex justify-between items-center gap-2 bg-white rounded-xl px-3.5 py-2.5">
                                            <span class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></span>
                                            <span class="text-sm text-gray-500 whitespace-nowrap" x-text="formatRupiah(item.price)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto px-6 pt-4 pb-6">
                            <button @click="window.open(selectedTransaksi.receipt_url, '_blank')" class="w-full py-3.5 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition">
                                Cetak Struk
                            </button>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init',()=>{Alpine.data('transactionLogic',()=>({
            selectedTransaksi:null, searchQuery:'', sortBy:'latest', methodFilter:'all',
            filteredTransaksis(){const q=this.searchQuery.trim().toLowerCase(); let rows=this.transactions.filter(t=>(!q || (t.invoice+' '+t.method+' '+t.date).toLowerCase().includes(q)) && (this.methodFilter==='all' || t.method===this.methodFilter)); return [...rows].sort((a,b)=>{if(this.sortBy==='oldest')return a.id-b.id;if(this.sortBy==='highest')return Number(b.amount)-Number(a.amount);if(this.sortBy==='lowest')return Number(a.amount)-Number(b.amount);return b.id-a.id;});},
            transactions: @json($transactionData),
            formatRupiah(value){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(value).replace('Rp','Rp ');}
        }))})
    </script>    <style>
        [x-cloak] { display: none !important; }
    </style>

@endsection