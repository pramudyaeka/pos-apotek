@extends('layout.sidebar')
@section('title', 'Produk')
@section('content')

    <div x-data="productsLogic()">

        {{-- Breadcrumb --}}
        <p class="text-sm text-gray-400 mb-2">
            Menu Utama <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Produk</span>
        </p>

        <div class="mb-6">
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Produk</h1>
            <p class="text-gray-500 mt-1">Kelola dan pantau persediaan produk</p>
        </div>

        {{-- Cari + Tambah Produk + Urutkan + Filter --}}
        <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 mb-5">
            <div class="relative flex-1 w-full min-w-[200px]">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
                </span>
                <input type="text" x-model="searchQuery" placeholder="Cari..."
                    class="pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm w-full bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
            </div>

            <div class="flex flex-wrap gap-2 shrink-0">
                <select x-model="sortBy" aria-label="Urutkan produk" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]/20">
                    <option value="name">Nama A-Z</option><option value="stock-low">Stok low-high</option><option value="stock-high">Stok high-low</option><option value="price-low">Harga low-high</option><option value="price-high">Harga high-low</option>
                </select>
                <select x-model="stockFilter" aria-label="Filter stok" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]/20">
                    <option value="all">Semua stok</option><option value="low">Stok menipis</option><option value="out">Habis</option><option value="healthy">Stok aman</option>
                </select>
                <button @click="openAddModal()"
                    class="px-5 py-3 text-sm font-semibold text-white bg-[#1F4D3D] hover:bg-[#173B2F] rounded-xl transition whitespace-nowrap flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                    Tambah Produk
                </button>
                            </div>
        </div>

        {{-- Tabel produk --}}
        <div class="rounded-2xl border border-gray-100 bg-white overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 min-w-[720px]">
                <thead class="font-['IBM_Plex_Mono'] text-[11px] tracking-widest text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap w-16">#</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Item Nama</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Kategori</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Harga</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Stok</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Satuan</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(item, index) in filteredProduk()" :key="item.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400" x-text="index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="item.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <span x-text="item.category_name"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="formatRupiah(item.price)"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span x-text="item.stock" :class="item.stock <= item.min_stock ? 'text-[#B8632E] font-semibold' : 'text-gray-900'"></span>
                                    <span x-show="item.stock <= item.min_stock"
                                        class="text-[10px] font-semibold text-[#B8632E] bg-[#B8632E]/10 px-2 py-0.5 rounded-full whitespace-nowrap">
                                        Menipis Stok
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="item.unit"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openUbahModal(item)" aria-label="Ubah"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1F4D3D] hover:border-[#1F4D3D]/30 hover:bg-[#1F4D3D]/5 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 4.5a2.1 2.1 0 0 1 3 3L8 19l-4 1 1-4Z"/></svg>
                                    </button>
                                    <button @click="deleteProduk(item.id)" aria-label="Hapus"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1l1-12"/></svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredProduk().length === 0">
                        <td colspan="7" class="px-6 py-14 text-center text-sm text-gray-400">
                            Produk tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah/Ubah Produk --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.45)">
            <div @click.outside="closeModal()"
                x-show="showModal"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl w-full max-w-sm p-6 max-h-[90vh] overflow-y-auto">

                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900" x-text="editingProduk ? 'Ubah Item' : 'Tambah Produk'"></h2>
                    <button @click="closeModal()" aria-label="Tutup" class="text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400 mb-6">Isi informasi produk di bawah.</p>

                <p class="text-[11px] font-['IBM_Plex_Mono'] tracking-widest text-gray-400 uppercase mb-2">Produk info</p>
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Item Nama</label>
                        <input type="text" x-model="form.name" placeholder="e.g. Nexium 20mg"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                            <select x-model="form.category_id" class="border border-gray-300 rounded-xl py-2.5 px-3 w-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                                <option value="">Select</option>
                                <template x-for="c in categoryOptions" :key="c.id">
                                    <option :value="c.id" x-text="c.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Satuan</label>
                            <select x-model="form.unit" class="border border-gray-300 rounded-xl py-2.5 px-3 w-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                                <option value="">Select</option>
                                <template x-for="u in unitOptions" :key="u">
                                    <option :value="u" x-text="u"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <p class="text-[11px] font-['IBM_Plex_Mono'] tracking-widest text-gray-400 uppercase mb-2">Harga and stock</p>
                <div class="space-y-4 mb-7">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga</label>
                        <input type="number" x-model.number="form.price" placeholder="0"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Stok</label>
                            <input type="number" x-model.number="form.stock" placeholder="0"
                                class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Stok</label>
                            <input type="number" x-model.number="form.min_stock" placeholder="0"
                                class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="closeModal()" class="flex-1 py-3 rounded-xl font-medium text-sm text-gray-700 border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                    <button data-product-save @click="saveProduk()" class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition" x-text="editingProduk ? 'Simpan' : 'Tambah Produk'"></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productsLogic', () => ({
                searchQuery: '', sortBy:'name', stockFilter:'all', showModal: false, editingProduk: null,
                categoryOptions: @json($categoryData),
                unitOptions: ['Tablet','Strip','Box','Tube','Sachet','Capsule','Pcs'],
                form: { name:'', category_id:'', unit:'', price:null, stock:null, min_stock:null, is_active:true },
                products: @json($productData),
                filteredProduk() { const q=this.searchQuery.trim().toLowerCase(); let rows=this.products.filter(p=>(!q || (p.name+' '+(p.category_name||'')).toLowerCase().includes(q)) && (this.stockFilter==='all' || (this.stockFilter==='out' && Number(p.stock)===0) || (this.stockFilter==='low' && Number(p.stock)>0 && Number(p.stock)<=Number(p.min_stock)) || (this.stockFilter==='healthy' && Number(p.stock)>Number(p.min_stock)))); return [...rows].sort((a,b)=>{if(this.sortBy==='stock-low')return a.stock-b.stock;if(this.sortBy==='stock-high')return b.stock-a.stock;if(this.sortBy==='price-low')return Number(a.price)-Number(b.price);if(this.sortBy==='price-high')return Number(b.price)-Number(a.price);return a.name.localeCompare(b.name);}); },
                formatRupiah(n) { return 'Rp '+Number(n||0).toLocaleString('id-ID'); },
                openAddModal(){this.editingProduk=null;this.form={name:'',category_id:'',unit:'',price:null,stock:null,min_stock:null,is_active:true};this.showModal=true;},
                openUbahModal(item){this.editingProduk=item;this.form={name:item.name,category_id:item.category_id,unit:item.unit,price:item.price,stock:item.stock,min_stock:item.min_stock,is_active:item.is_active};this.showModal=true;},
                closeModal(){this.showModal=false;},
                async saveProduk(){
                    if(!this.form.name.trim()){showToast('Nama produk wajib diisi.','warning');return;} if(!this.form.category_id){showToast('Pilih kategori produk.','warning');return;} if(!this.form.unit){showToast('Pilih satuan produk.','warning');return;}
                    const editing=this.editingProduk; const url=editing?'{{ url('/product') }}/'+editing.id:'{{ route('product.store') }}';
                    const saveButton=document.querySelector('[data-product-save]'); if(saveButton) saveButton.disabled=true; const response=await fetch(url,{method:editing?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify(this.form)});
                    const data=await response.json();if(!response.ok){if(saveButton) saveButton.disabled=false;showToast(data.message||Object.values(data.errors||{}).flat().join(' ')||'Produk gagal disimpan.','error');return;}
                    const normalized={id:data.id,name:data.name,category_id:data.category_id,category_name:data.category?.name,price:Number(data.price),stock:data.stock,min_stock:data.min_stock,unit:data.unit,is_active:data.is_active};
                    if(editing) Object.assign(editing,normalized); else this.products.push(normalized); this.closeModal();if(saveButton) saveButton.disabled=false;showToast(editing?'Produk berhasil diperbarui.':'Produk berhasil ditambahkan.');
                },
                async deleteProduk(id){if(!confirmAksi('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.'))return;const response=await fetch('{{ url('/product') }}/'+id,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});const data=await response.json();if(!response.ok){showToast(data.message||'Produk gagal dihapus.','error');return;}this.products=this.products.filter(p=>p.id!==id);showToast('Produk berhasil dihapus.');}
            }))
        })
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>

@endsection