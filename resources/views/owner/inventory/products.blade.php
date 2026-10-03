@extends('layout.sidebar')
@section('title', 'Products')
@section('content')

    <div x-data="productsLogic()">

        {{-- Breadcrumb --}}
        <p class="text-sm text-gray-400 mb-2">
            Main Menu <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Products</span>
        </p>

        <div class="mb-6">
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Products</h1>
            <p class="text-gray-500 mt-1">Manage and monitoring your product inventory</p>
        </div>

        {{-- Search + Add Items + Sort + Filter --}}
        <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 mb-5">
            <div class="relative flex-1 w-full min-w-[200px]">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
                </span>
                <input type="text" x-model="searchQuery" placeholder="Search..."
                    class="pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm w-full bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
            </div>

            <div class="flex gap-3 shrink-0">
                <button @click="openAddModal()"
                    class="px-5 py-3 text-sm font-semibold text-white bg-[#1F4D3D] hover:bg-[#173B2F] rounded-xl transition whitespace-nowrap flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                    Add Items
                </button>
                <button class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">Sort</button>
                <button class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">Filter</button>
            </div>
        </div>

        {{-- Tabel produk --}}
        <div class="rounded-2xl border border-gray-100 bg-white overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 min-w-[720px]">
                <thead class="font-['IBM_Plex_Mono'] text-[11px] tracking-widest text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap w-16">#</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Item Name</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Category</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Price</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Stock</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Unit</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(item, index) in filteredProducts()" :key="item.id">
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
                                    <span x-text="item.stock" :class="item.stock <= item.minStock ? 'text-[#B8632E] font-semibold' : 'text-gray-900'"></span>
                                    <span x-show="item.stock <= item.minStock"
                                        class="text-[10px] font-semibold text-[#B8632E] bg-[#B8632E]/10 px-2 py-0.5 rounded-full whitespace-nowrap">
                                        Low Stock
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="item.unit"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(item)" aria-label="Edit"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1F4D3D] hover:border-[#1F4D3D]/30 hover:bg-[#1F4D3D]/5 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 4.5a2.1 2.1 0 0 1 3 3L8 19l-4 1 1-4Z"/></svg>
                                    </button>
                                    <button @click="deleteProduct(item.id)" aria-label="Delete"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1l1-12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredProducts().length === 0">
                        <td colspan="7" class="px-6 py-14 text-center text-sm text-gray-400">
                            No products found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah/Edit Produk --}}
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
                    <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900" x-text="editingProduct ? 'Edit Item' : 'Add New Item'"></h2>
                    <button @click="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400 mb-6">Fill in the product details below.</p>

                <p class="text-[11px] font-['IBM_Plex_Mono'] tracking-widest text-gray-400 uppercase mb-2">Product info</p>
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Item Name</label>
                        <input type="text" x-model="form.name" placeholder="e.g. Nexium 20mg"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                            <select x-model="form.category_id" class="border border-gray-300 rounded-xl py-2.5 px-3 w-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                                <option value="">Select</option>
                                <template x-for="c in categoryOptions" :key="c.id">
                                    <option :value="c.id" x-text="c.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit</label>
                            <select x-model="form.unit" class="border border-gray-300 rounded-xl py-2.5 px-3 w-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                                <option value="">Select</option>
                                <template x-for="u in unitOptions" :key="u">
                                    <option :value="u" x-text="u"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <p class="text-[11px] font-['IBM_Plex_Mono'] tracking-widest text-gray-400 uppercase mb-2">Price and stock</p>
                <div class="space-y-4 mb-7">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Price</label>
                        <input type="number" x-model.number="form.price" placeholder="0"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Stock</label>
                            <input type="number" x-model.number="form.stock" placeholder="0"
                                class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Stock</label>
                            <input type="number" x-model.number="form.minStock" placeholder="0"
                                class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="closeModal()" class="flex-1 py-3 rounded-xl font-medium text-sm text-gray-700 border border-gray-200 hover:bg-gray-50 transition">Cancel</button>
                    <button @click="saveProduct()" class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition" x-text="editingProduct ? 'Save' : 'Add Item'"></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productsLogic', () => ({
                searchQuery: '', showModal: false, editingProduct: null,
                categoryOptions: @json($categories->map(fn($c) => ['id'=>$c->id,'name'=>$c->name])->values()),
                unitOptions: ['Tablet','Strip','Box','Tube','Sachet','Capsule','Pcs'],
                form: { name:'', category_id:'', unit:'', price:null, stock:null, min_stock:null, is_active:true },
                products: @json($products->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'category_id'=>$p->category_id,'category_name'=>$p->category?->name,'price'=>(float)$p->price,'stock'=>$p->stock,'min_stock'=>$p->min_stock,'unit'=>$p->unit,'is_active'=>$p->is_active])->values()),
                filteredProducts() { const q=this.searchQuery.trim().toLowerCase(); return q ? this.products.filter(p=>(p.name+' '+(p.category_name||'')).toLowerCase().includes(q)) : this.products; },
                formatRupiah(n) { return 'Rp '+Number(n||0).toLocaleString('id-ID'); },
                openAddModal(){this.editingProduct=null;this.form={name:'',category_id:'',unit:'',price:null,stock:null,min_stock:null,is_active:true};this.showModal=true;},
                openEditModal(item){this.editingProduct=item;this.form={name:item.name,category_id:item.category_id,unit:item.unit,price:item.price,stock:item.stock,min_stock:item.min_stock,is_active:item.is_active};this.showModal=true;},
                closeModal(){this.showModal=false;},
                async saveProduct(){
                    if(!this.form.name.trim()||!this.form.category_id||!this.form.unit) return;
                    const editing=this.editingProduct; const url=editing?'{{ url('/product') }}/'+editing.id:'{{ route('product.store') }}';
                    const response=await fetch(url,{method:editing?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify(this.form)});
                    const data=await response.json(); if(!response.ok){alert(data.message||Object.values(data.errors||{}).flat().join('\n')||'Unable to save product');return;}
                    const normalized={id:data.id,name:data.name,category_id:data.category_id,category_name:data.category?.name,price:Number(data.price),stock:data.stock,min_stock:data.min_stock,unit:data.unit,is_active:data.is_active};
                    if(editing) Object.assign(editing,normalized); else this.products.push(normalized); this.closeModal();
                },
                async deleteProduct(id){if(!confirm('Delete this product?'))return;const response=await fetch('{{ url('/product') }}/'+id,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});const data=await response.json();if(!response.ok){alert(data.message||'Unable to delete product');return;}this.products=this.products.filter(p=>p.id!==id);}
            }))
        })
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>

@endsection