@extends('layout.sidebar')
@section('title', 'User Management')
@section('content')

    <div x-data="usersLogic()">

        {{-- Breadcrumb --}}
        <p class="text-sm text-gray-400 mb-2">
            Main Menu <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">User Management</span>
        </p>

        <div class="mb-6">
            <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">User Management</h1>
            <p class="text-gray-500 mt-1">Manage staff accounts and access for this pharmacy</p>
        </div>

        {{-- Search + Add User + Sort + Filter --}}
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
                    Add User
                </button>
                <button class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">Sort</button>
                <button class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">Filter</button>
            </div>
        </div>

        {{-- Tabel user --}}
        <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="font-['IBM_Plex_Mono'] text-[11px] tracking-widest text-gray-400 uppercase bg-gray-50/70 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap w-16">#</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Name</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Role</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap">Status</th>
                        <th scope="col" class="px-6 py-4 font-medium whitespace-nowrap text-right">Action</th>
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
                                    <span x-show="u.id === currentUserId" class="text-[10px] font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full shrink-0">You</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                    :class="u.role === 'Owner' ? 'bg-[#1F4D3D]/10 text-[#1F4D3D]' : 'bg-gray-100 text-gray-600'">
                                    <span x-text="u.role"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                    :class="u.status === 'Active' ? 'bg-[#1F4D3D]/10 text-[#1F4D3D]' : 'bg-gray-100 text-gray-500'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="u.status === 'Active' ? 'bg-[#1F4D3D]' : 'bg-gray-400'"></span>
                                    <span x-text="u.status"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(u)" aria-label="Edit"
                                        class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1F4D3D] hover:border-[#1F4D3D]/30 hover:bg-[#1F4D3D]/5 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 4.5a2.1 2.1 0 0 1 3 3L8 19l-4 1 1-4Z"/></svg>
                                    </button>
                                    <button @click="deleteUser(u)" :disabled="!canDelete(u)" :title="!canDelete(u) ? deleteBlockedReason(u) : 'Delete user'"
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
                            No users found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah/Edit User --}}
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
                    <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900" x-text="editingUser ? 'Edit User' : 'Add New User'"></h2>
                    <button @click="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400 mb-6">Fill in the staff account details below.</p>

                <div class="space-y-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                        <input type="text" x-model="form.name" placeholder="e.g. Jenny Wilson"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" x-model="form.email" placeholder="name@apotek.com"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password
                            <span x-show="editingUser" class="text-gray-400 font-normal">(leave blank to keep current password)</span>
                        </label>
                        <input type="password" x-model="form.password" placeholder="••••••••"
                            class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                        <select x-model="form.role" :disabled="editingUser && isLastOwner(editingUser)"
                            class="border border-gray-300 rounded-xl py-2.5 px-3 w-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition disabled:bg-gray-50 disabled:text-gray-400">
                            <option value="Owner">Owner</option>
                            <option value="Cashier">Cashier</option>
                        </select>
                        <p x-show="editingUser && isLastOwner(editingUser)" class="text-xs text-[#B8632E] mt-1.5">
                            This is the last Owner account — role can't be changed until another Owner is added.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-7">
                    <span class="text-sm font-medium text-gray-700">Active Status</span>
                    <button type="button" @click="form.status = form.status === 'Active' ? 'Inactive' : 'Active'"
                        :disabled="editingUser && editingUser.id === currentUserId"
                        class="w-11 h-6 rounded-full transition relative shrink-0 disabled:opacity-40 disabled:cursor-not-allowed"
                        :class="form.status === 'Active' ? 'bg-[#1F4D3D]' : 'bg-gray-300'">
                        <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white transition-all"
                            :class="form.status === 'Active' ? 'left-[22px]' : 'left-0.5'"></span>
                    </button>
                </div>
                <p x-show="editingUser && editingUser.id === currentUserId" class="text-xs text-gray-400 -mt-5 mb-6">
                    You can't deactivate your own account.
                </p>

                <div class="flex gap-3">
                    <button @click="closeModal()" class="flex-1 py-3 rounded-xl font-medium text-sm text-gray-700 border border-gray-200 hover:bg-gray-50 transition">Cancel</button>
                    <button @click="saveUser()" class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition" x-text="editingUser ? 'Save' : 'Add User'"></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('usersLogic', () => ({
                searchQuery: '',
                showModal: false,
                editingUser: null,
                // Simulasi user yang sedang login. Nanti ganti dengan
                // {{ auth()->id() }} dari Laravel.
                currentUserId: 1,
                form: { name: '', email: '', password: '', role: 'Cashier', status: 'Active' },

                users: [
                    { id: 1, name: 'Robert Fox',        email: 'robert@apotek.com',   role: 'Owner',   status: 'Active' },
                    { id: 2, name: 'Leslie Alexander',  email: 'leslie@apotek.com',   role: 'Cashier', status: 'Active' },
                    { id: 3, name: 'Guy Hawkins',       email: 'guy@apotek.com',      role: 'Cashier', status: 'Active' },
                    { id: 4, name: 'Jenny Wilson',      email: 'jenny@apotek.com',    role: 'Cashier', status: 'Active' },
                    { id: 5, name: 'Brooklyn Simmons',  email: 'brooklyn@apotek.com', role: 'Cashier', status: 'Inactive' },
                ],

                filteredUsers() {
                    const q = this.searchQuery.trim().toLowerCase();
                    if (!q) return this.users;
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || u.role.toLowerCase().includes(q)
                    );
                },

                initials(name) {
                    return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
                },

                ownerCount() {
                    return this.users.filter(u => u.role === 'Owner').length;
                },

                isLastOwner(user) {
                    return user.role === 'Owner' && this.ownerCount() === 1;
                },

                canDelete(user) {
                    if (user.id === this.currentUserId) return false;
                    if (this.isLastOwner(user)) return false;
                    return true;
                },

                deleteBlockedReason(user) {
                    if (user.id === this.currentUserId) return "You can't delete your own account";
                    if (this.isLastOwner(user)) return "Can't delete the last Owner account";
                    return '';
                },

                openAddModal() {
                    this.editingUser = null;
                    this.form = { name: '', email: '', password: '', role: 'Cashier', status: 'Active' };
                    this.showModal = true;
                },

                openEditModal(user) {
                    this.editingUser = user;
                    this.form = { name: user.name, email: user.email, password: '', role: user.role, status: user.status };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                },

                saveUser() {
                    if (!this.form.name.trim() || !this.form.email.trim()) return;
                    if (!this.editingUser && !this.form.password.trim()) return;

                    if (this.editingUser) {
                        this.editingUser.name = this.form.name;
                        this.editingUser.email = this.form.email;
                        this.editingUser.role = this.isLastOwner(this.editingUser) ? 'Owner' : this.form.role;
                        this.editingUser.status = this.editingUser.id === this.currentUserId ? 'Active' : this.form.status;
                        // this.form.password diabaikan jika kosong — hanya
                        // dikirim ke backend kalau diisi (update password).
                    } else {
                        const newId = this.users.length ? Math.max(...this.users.map(u => u.id)) + 1 : 1;
                        this.users.push({ id: newId, name: this.form.name, email: this.form.email, role: this.form.role, status: this.form.status });
                    }
                    this.closeModal();
                },

                deleteUser(user) {
                    if (!this.canDelete(user)) return;
                    if (!confirm(`Delete ${user.name}?`)) return;
                    this.users = this.users.filter(u => u.id !== user.id);
                }
            }))
        })
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

@endsection