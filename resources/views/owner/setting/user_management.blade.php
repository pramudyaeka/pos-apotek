@extends('layout.sidebar')
@section('title', 'Manajemen Pengguna')
@section('content')

    <div x-data="usersLogic()">

        {{-- Breadcrumb --}}
        <p class="text-sm text-gray-400 mb-2">
            Main Menu <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Manajemen Pengguna</span>
        </p>

        <div class="mb-6">
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Manajemen Pengguna</h1>
            <p class="text-gray-500 mt-1">Kelola akun pengguna dan hak akses apotek.</p>
        </div>

        {{-- Search + Tambah Pengguna + Sort + Filter --}}
        <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 mb-5">
            <div class="relative flex-1 w-full min-w-[200px]">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
                </span>
                <input type="text" x-model="searchQuery" placeholder="Cari..."
                    class="pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm w-full bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
            </div>

            <div class="flex flex-wrap gap-2 shrink-0">
                <select x-model="roleFilter" aria-label="Filter role" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl"><option value="all">Semua peran</option><option value="Owner">Pemilik</option><option value="Cashier">Kasir</option></select>
                <select x-model="statusFilter" aria-label="Filter status" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl"><option value="all">Semua status</option><option value="Active">Aktif</option><option value="Inactive">Tidak Aktif</option></select>
                <button @click="openAddModal()"
                    class="px-5 py-3 text-sm font-semibold text-white bg-[#1F4D3D] hover:bg-[#173B2F] rounded-xl transition whitespace-nowrap flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                    Tambah Pengguna
                </button>
                            </div>
        </div>

        {{-- Tabel user --}}
        <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="font-['IBM_Plex_Mono'] text-[11px] tracking-widest text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap w-16">#</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Nama</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Peran</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Status</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(u, index) in filteredUsers()" :key="u.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400" x-text="index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                                        <span class="font-['Space_Grotesk'] font-semibold text-xs text-[#1F4D3D]" x-text="initials(u.name)"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate" x-text="u.name"></p>
                                        <p class="text-xs text-gray-400 truncate" x-text="u.email"></p>
                                    </div>
                                    <span x-show="u.id === currentUserId" class="text-[10px] font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full shrink-0">Anda</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                    :class="u.role === 'Owner' ? 'bg-[#1F4D3D]/10 text-[#1F4D3D]' : 'bg-gray-100 text-gray-600'">
                                    <span x-text="u.role === 'Owner' ? 'Pemilik' : 'Kasir'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                    :class="u.status === 'Active' ? 'bg-[#1F4D3D]/10 text-[#1F4D3D]' : 'bg-gray-100 text-gray-500'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="u.status === 'Active' ? 'bg-[#1F4D3D]' : 'bg-gray-400'"></span>
                                    <span x-text="u.status === 'Active' ? 'Aktif' : 'Tidak Aktif'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(u)" aria-label="Ubah"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1F4D3D] hover:border-[#1F4D3D]/30 hover:bg-[#1F4D3D]/5 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 4.5a2.1 2.1 0 0 1 3 3L8 19l-4 1 1-4Z"/></svg>
                                    </button>
                                    <button @click="deleteUser(u)" :disabled="!canDelete(u)" :title="!canDelete(u) ? deleteBlockedReason(u) : 'Hapus pengguna'"
                                        class="w-9 h-9 rounded-lg border flex items-center justify-center transition"
                                        :class="canDelete(u) ? 'border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50' : 'border-gray-100 text-gray-300 cursor-not-allowed'">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1l1-12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredUsers().length === 0">
                        <td colspan="5" class="px-6 py-14 text-center text-sm text-gray-400">
                            Pengguna tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah/Ubah Pengguna --}}
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
                    <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900" x-text="editingUser ? 'Ubah Pengguna' : 'Tambah Pengguna'"></h2>
                    <button @click="closeModal()" aria-label="Tutup" class="text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400 mb-6">Isi informasi akun pengguna di bawah.</p>

                <div class="space-y-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" x-model="form.name" placeholder="e.g. Jenny Wilson"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Surel</label>
                        <input type="email" x-model="form.email" placeholder="name@apotek.com"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kata Sandi
                            <span x-show="editingUser" class="text-gray-400 font-normal">(kosongkan untuk mempertahankan kata sandi saat ini)</span>
                        </label>
                        <input type="password" x-model="form.password" placeholder="••••••••"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Peran</label>
                        <select x-model="form.role" :disabled="editingUser && isLastOwner(editingUser)"
                            class="border border-gray-300 rounded-xl py-2.5 px-3 w-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition disabled:bg-gray-50 disabled:text-gray-400">
                            <option value="Owner">Owner</option>
                            <option value="Kasir">Kasir</option>
                        </select>
                        <p x-show="editingUser && isLastOwner(editingUser)" class="text-xs text-[#B8632E] mt-1.5">
                            This is the last Owner account — role can't be changed until another Owner is added.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-7">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                    <button type="button" @click="form.status = form.status === 'Active' ? 'Inactive' : 'Active'"
                        :disabled="editingUser && editingUser.id === currentUserId"
                        class="w-11 h-6 rounded-full transition relative shrink-0 disabled:opacity-40 disabled:cursor-not-allowed"
                        :class="form.status === 'Active' ? 'bg-[#1F4D3D]' : 'bg-gray-300'">
                        <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white transition-all"
                            :class="form.status === 'Active' ? 'left-[22px]' : 'left-0.5'"></span>
                    </button>
                </div>
                <p x-show="editingUser && editingUser.id === currentUserId" class="text-xs text-gray-400 -mt-5 mb-6">
                    Anda can't deactivate your own account.
                </p>

                <div class="flex gap-3">
                    <button @click="closeModal()" class="flex-1 py-3 rounded-xl font-medium text-sm text-gray-700 border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                    <button @click="saveUser()" class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition" x-text="editingUser ? 'Simpan' : 'Tambah Pengguna'"></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init',()=>{Alpine.data('usersLogic',()=>({
            searchQuery:'',roleFilter:'all',statusFilter:'all',showModal:false,editingUser:null,currentUserId:{{ auth()->id() }},
            form:{name:'',email:'',password:'',role:'Cashier',status:'Active'},
            users: @json($users),
            filteredUsers(){const q=this.searchQuery.trim().toLowerCase();return this.users.filter(u=>(!q||(u.name+' '+u.email+' '+u.role).toLowerCase().includes(q))&&(this.roleFilter==='all'||u.role===this.roleFilter)&&(this.statusFilter==='all'||u.status===this.statusFilter));},
            initials(name){return name.split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase();},
            ownerCount(){return this.users.filter(u=>u.role==='Owner').length;},
            isLastOwner(user){return user.role==='Owner'&&this.ownerCount()===1;},
            canDelete(user){return user.id!==this.currentUserId&&!this.isLastOwner(user);},
            deleteBlockedReason(user){if(user.id===this.currentUserId)return "Anda tidak dapat menghapus akun sendiri";if(this.isLastOwner(user))return "Tidak dapat menghapus akun Owner terakhir";return '';},
            openAddModal(){this.editingUser=null;this.form={name:'',email:'',password:'',role:'Cashier',status:'Active'};this.showModal=true;},
            openEditModal(user){this.editingUser=user;this.form={name:user.name,email:user.email,password:'',role:user.role,status:user.status};this.showModal=true;},
            closeModal(){this.showModal=false;},
            async saveUser(){
                if(!this.form.name.trim()){showToast('Nama wajib diisi.','warning');return;}if(!this.form.email.trim()){showToast('Surel wajib diisi.','warning');return;}if(!this.editingUser&&!this.form.password.trim()){showToast('Kata Sandi wajib diisi.','warning');return;}
                const editing=this.editingUser,url=editing?'{{ url('/user') }}/'+editing.id:'{{ route('user.store') }}';
                try { const response=await fetch(url,{method:editing?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify(this.form)}); const data=await response.json();if(!response.ok){showToast(data.message||Object.values(data.errors||{}).flat().join(' ')||'Pengguna gagal disimpan.','error');return;} const normalized={id:data.id,name:data.name,email:data.email,role:data.role,status:data.status}; if(editing)Object.assign(editing,normalized);else this.users.push(normalized);this.closeModal();showToast(editing?'Pengguna berhasil diperbarui.':'Pengguna berhasil ditambahkan.'); } catch (error) { showToast('Tidak dapat terhubung ke server. Silakan coba lagi.','error'); }
            },
            async deleteUser(user){if(!this.canDelete(user)||!confirmAksi('Hapus pengguna '+user.name+'? Tindakan ini tidak dapat dibatalkan.'))return;try { const response=await fetch('{{ url('/user') }}/'+user.id,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});const data=await response.json();if(!response.ok){showToast(data.message||'Pengguna gagal dihapus.','error');return;}this.users=this.users.filter(u=>u.id!==user.id);showToast('Pengguna berhasil dihapus.'); } catch (error) { showToast('Tidak dapat terhubung ke server. Silakan coba lagi.','error'); }}
        }))})
    </script>    <style>
        [x-cloak] { display: none !important; }
    </style>

@endsection