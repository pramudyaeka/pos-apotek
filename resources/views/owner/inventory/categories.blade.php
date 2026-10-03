@extends('layout.sidebar')
@section('title', 'Categories')
@section('content')

    <div x-data="categoriesLogic()">

        {{-- Breadcrumb --}}
        <p class="text-sm text-gray-400 mb-2">
            Main Menu <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Categories</span>
        </p>

        <div class="mb-6">
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Categories</h1>
            <p class="text-gray-500 mt-1">Manage and monitoring your product categories</p>
        </div>

        {{-- Search + Add Category + Sort + Filter --}}
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
                    Add Category
                </button>
                <button class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">Sort</button>
                <button class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">Filter</button>
            </div>
        </div>

        {{-- Tabel kategori --}}
        <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="font-['IBM_Plex_Mono'] text-[11px] tracking-widest text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap w-16">#</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Category Name</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Status</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Total Items</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(cat, index) in filteredCategories()" :key="cat.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400" x-text="index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="cat.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                    :class="cat.is_active ? 'bg-[#1F4D3D]/10 text-[#1F4D3D]' : 'bg-gray-100 text-gray-500'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="cat.is_active ? 'bg-[#1F4D3D]' : 'bg-gray-400'"></span>
                                    <span x-text="cat.is_active ? 'Active' : 'Inactive'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full" x-text="cat.products_count + ' Items'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(cat)" aria-label="Edit"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1F4D3D] hover:border-[#1F4D3D]/30 hover:bg-[#1F4D3D]/5 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 4.5a2.1 2.1 0 0 1 3 3L8 19l-4 1 1-4Z"/></svg>
                                    </button>
                                    <button @click="deleteCategory(cat.id)" aria-label="Delete"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1l1-12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredCategories().length === 0">
                        <td colspan="5" class="px-6 py-14 text-center text-sm text-gray-400">
                            No categories found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah/Edit Kategori --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.45)">
            <div @click.outside="closeModal()"
                x-show="showModal"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl w-full max-w-sm p-6">

                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900" x-text="editingCategory ? 'Edit Category' : 'Add New Category'"></h2>
                    <button @click="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400 mb-6">Fill in the category details below.</p>

                <div class="mb-5">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-1.5">Category Name</label>
                    <input type="text" id="categoryName" x-model="form.name" placeholder="e.g. Pain Relief"
                        class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                </div>

                <div class="flex items-center justify-between mb-7">
                    <span class="text-sm font-medium text-gray-700">Active Status</span>
                    <button type="button" @click="form.is_active = !form.is_active"
                        class="w-11 h-6 rounded-full transition relative shrink-0"
                        :class="form.is_active ? 'bg-[#1F4D3D]' : 'bg-gray-300'">
                        <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white transition-all"
                            :class="form.is_active ? 'left-[22px]' : 'left-0.5'"></span>
                    </button>
                </div>

                <div class="flex gap-3">
                    <button @click="closeModal()" class="flex-1 py-3 rounded-xl font-medium text-sm text-gray-700 border border-gray-200 hover:bg-gray-50 transition">Cancel</button>
                    <button @click="saveCategory()" class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('categoriesLogic', () => ({
                searchQuery:'', showModal:false, editingCategory:null,
                form:{name:'',is_active:true},
                categories: @json($categoryData),
                filteredCategories(){const q=this.searchQuery.trim().toLowerCase();return q?this.categories.filter(c=>c.name.toLowerCase().includes(q)):this.categories;},
                openAddModal(){this.editingCategory=null;this.form={name:'',is_active:true};this.showModal=true;},
                openEditModal(cat){this.editingCategory=cat;this.form={name:cat.name,is_active:cat.is_active};this.showModal=true;},
                closeModal(){this.showModal=false;},
                async saveCategory(){
                    if(!this.form.name.trim())return;
                    const editing=this.editingCategory;const url=editing?'{{ url('/category') }}/'+editing.id:'{{ route('category.store') }}';
                    const response=await fetch(url,{method:editing?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify(this.form)});
                    const data=await response.json();if(!response.ok){alert(data.message||Object.values(data.errors||{}).flat().join('\n')||'Unable to save category');return;}
                    const normalized={id:data.id,name:data.name,is_active:data.is_active,products_count:data.products_count??editing?.products_count??0};
                    if(editing)Object.assign(editing,normalized);else this.categories.push(normalized);this.closeModal();
                },
                async deleteCategory(id){
                    if(!confirm('Delete this category?'))return;
                    const response=await fetch('{{ url('/category') }}/'+id,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});
                    const data=await response.json();if(!response.ok){alert(data.message||'Unable to delete category');return;}this.categories=this.categories.filter(c=>c.id!==id);
                }
            }))
        })
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>

@endsection