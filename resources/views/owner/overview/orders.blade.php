@extends('layout.sidebar')
@section('title', 'Orders')
@section('content')

    {{-- Breadcrumb --}}
    <p class="text-sm text-gray-400 mb-2">
        Main Menu <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Orders</span>
    </p>

    <div class="mb-6">
        <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Orders</h1>
        <p class="text-gray-500 mt-1">Manage and monitoring your sales in one page</p>
    </div>

    {{-- Search + live date/time --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
            </span>
            <input type="text" id="productSearch" placeholder="Search..." oninput="filterProducts(this.value)"
                class="pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm w-full bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
        </div>

        <div class="flex gap-3 shrink-0">
            <div class="flex items-center gap-2.5 border border-gray-200 rounded-xl px-4 py-3 bg-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5 text-gray-500 shrink-0"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3.5 9.5h17"/></svg>
                <span id="liveDate" class="text-sm font-medium text-gray-700 whitespace-nowrap"></span>
            </div>
            <div class="flex items-center gap-2.5 border border-gray-200 rounded-xl px-4 py-3 bg-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5 text-gray-500 shrink-0"><circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2"/></svg>
                <span id="liveTime" class="text-sm font-medium text-gray-700 whitespace-nowrap"></span>
            </div>
        </div>
    </div>

    {{-- Konten utama: grid produk (kiri) + ringkasan order (kanan) --}}
    <div class="grid grid-cols-1 md:grid-cols-[1fr_300px] lg:grid-cols-[1fr_360px] gap-5 md:gap-4 lg:gap-6 items-start">

        {{-- Grid produk --}}
        <div id="productGrid" class="grid grid-cols-2 min-[860px]:grid-cols-3 gap-3 min-[860px]:gap-4 min-h-0 max-h-[calc(100vh-280px)] overflow-y-auto pr-1 pb-2">
            @php
                $products = [
                    ['name' => 'Somitril', 'category' => 'Pain Relief', 'price' => 5000],
                    ['name' => 'Tropiprazole', 'category' => 'Digestive', 'price' => 20000],
                    ['name' => 'Tropbove', 'category' => 'Cold & Flu', 'price' => 12000],
                    ['name' => 'Somikalim', 'category' => 'Vitamin', 'price' => 17000],
                    ['name' => 'Virafibatide', 'category' => 'Antiviral', 'price' => 15000],
                    ['name' => 'Pegamostim', 'category' => 'Pain Relief', 'price' => 10000],
                    ['name' => 'Preditirelin', 'category' => 'Digestive', 'price' => 21000],
                    ['name' => 'Nablutril', 'category' => 'Cold & Flu', 'price' => 25000],
                    ['name' => 'Bolipristin', 'category' => 'Vitamin', 'price' => 25000],
                    ['name' => 'Klorfenamin', 'category' => 'Cold & Flu', 'price' => 7000],
                    ['name' => 'Dexaven', 'category' => 'Pain Relief', 'price' => 13000],
                    ['name' => 'Metildopa', 'category' => 'Antiviral', 'price' => 18000],
                ];
            @endphp

            @foreach ($products as $p)
                @php
                    $slug = \Illuminate\Support\Str::slug($p['name']);
                    $initials = strtoupper(substr($p['name'], 0, 2));
                @endphp
                <button type="button" data-product-name="{{ $p['name'] }}" data-product-price="{{ $p['price'] }}"
                    data-product-initials="{{ $initials }}" data-product-slug="{{ $slug }}"
                    data-search="{{ strtolower($p['name'].' '.$p['category']) }}"
                    onclick="addToCart(this)"
                    class="product-card text-left bg-white rounded-2xl border border-gray-100 p-3 hover:border-[#1F4D3D]/30 hover:shadow-md active:scale-[0.97] transition">

                    <div class="relative aspect-square rounded-xl bg-[#1F4D3D]/8 flex items-center justify-center mb-3">
                        <span class="font-['Space_Grotesk'] font-semibold text-2xl text-[#1F4D3D]/70">{{ $initials }}</span>
                        <span id="badge-{{ $slug }}" class="hidden absolute top-2 right-2 min-w-[24px] h-6 px-1.5 rounded-full bg-[#1F4D3D] text-white text-[12px] font-semibold items-center justify-center">0</span>
                    </div>

                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $p['name'] }}</p>
                    <div class="flex items-center justify-between gap-1.5 mt-2">
                        <span class="text-[11px] font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full truncate">{{ $p['category'] }}</span>
                        <span class="text-sm font-semibold text-gray-900 shrink-0">Rp{{ number_format($p['price'], 0, ',', '.') }}</span>
                    </div>
                </button>
            @endforeach

            <p id="noResults" class="hidden col-span-full text-center text-sm text-gray-400 py-10">
                No products match your search.
            </p>
        </div>

        {{-- Ringkasan order --}}
        <div class="bg-[#F5F6F4] rounded-2xl border border-gray-100 md:sticky md:top-6 flex flex-col min-h-0 max-h-[calc(100vh-160px)]">

            <div class="text-center px-6 pt-6 pb-4 shrink-0">
                <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900">Summary Order</h2>
                <p class="text-sm text-gray-400 mt-1">Order Number : #021</p>
            </div>

            <div id="cartList" class="flex-1 min-h-0 overflow-y-auto px-4 space-y-3 pb-2"></div>

            <p id="cartEmpty" class="hidden text-center text-sm text-gray-400 px-6 py-8">
                No items yet — tap a product on the left to add it here.
            </p>

            <div class="px-6 pt-4 pb-6 border-t border-gray-200/70 shrink-0 bg-[#F5F6F4] rounded-b-2xl">
                <div class="flex items-center justify-between text-sm text-gray-500 mb-1.5">
                    <span>Subtotal</span>
                    <span id="cartSubtotal">Rp 0</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    <span>Tax</span>
                    <span id="cartTax">Rp 0</span>
                </div>
                <div class="border-t border-dashed border-gray-300 pt-3 flex items-center justify-between mb-5">
                    <span class="font-['Space_Grotesk'] font-semibold text-gray-900">TOTAL</span>
                    <span id="cartTotal" class="font-['Space_Grotesk'] font-bold text-lg text-gray-900">Rp 0</span>
                </div>

                <button id="placeOrderBtn" onclick="placeOrder()" disabled
                    class="w-full py-4 rounded-xl font-['Space_Grotesk'] font-semibold text-white bg-[#1F4D3D] hover:bg-[#173B2F] disabled:bg-gray-300 disabled:cursor-not-allowed transition">
                    Place Order
                </button>
            </div>
        </div>

    </div>

    <script>
        // ---------- Keranjang (state disimpan di memory browser) ----------
        // Diisi awal sesuai contoh pada wireframe. Hapus 3 baris ini kalau
        // ingin keranjang mulai kosong secara default.
        let cart = [
            { name: 'Predprofen', price: 15000, qty: 1, initials: 'PR', slug: 'predprofen' },
            { name: 'Nabbufen',   price: 15000, qty: 1, initials: 'NA', slug: 'nabbufen' },
            { name: 'Bolipafant', price: 15000, qty: 1, initials: 'BO', slug: 'bolipafant' },
        ];

        function formatRupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function addToCart(el) {
            const name = el.dataset.productName;
            const price = parseInt(el.dataset.productPrice, 10);
            const initials = el.dataset.productInitials;
            const slug = el.dataset.productSlug;

            const existing = cart.find(i => i.slug === slug);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ name, price, qty: 1, initials, slug });
            }
            renderCart();
        }

        function changeQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }
            renderCart();
        }

        function renderCart() {
            const list = document.getElementById('cartList');
            const emptyState = document.getElementById('cartEmpty');
            const placeOrderBtn = document.getElementById('placeOrderBtn');

            if (cart.length === 0) {
                list.innerHTML = '';
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
                list.innerHTML = cart.map((item, index) => `
                    <div class="flex items-center gap-3 bg-white rounded-2xl p-3">
                        <div class="w-14 h-14 rounded-xl bg-[#1F4D3D]/8 flex items-center justify-center shrink-0">
                            <span class="font-['Space_Grotesk'] font-semibold text-[#1F4D3D]/70">${item.initials}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">${item.name}</p>
                            <p class="text-sm text-gray-500">${formatRupiah(item.price)}</p>
                        </div>
                        <div class="flex items-center gap-1 bg-gray-100 rounded-full p-1 shrink-0">
                            <button type="button" onclick="changeQty(${index}, -1)" aria-label="Kurangi"
                                class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-white active:scale-90 transition text-gray-600 font-medium">-</button>
                            <span class="w-6 text-center text-sm font-semibold text-gray-900">${item.qty}</span>
                            <button type="button" onclick="changeQty(${index}, 1)" aria-label="Tambah"
                                class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-white active:scale-90 transition text-gray-600 font-medium">+</button>
                        </div>
                    </div>
                `).join('');
            }

            const subtotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
            const tax = 0;
            const total = subtotal + tax;

            document.getElementById('cartSubtotal').textContent = formatRupiah(subtotal);
            document.getElementById('cartTax').textContent = formatRupiah(tax);
            document.getElementById('cartTotal').textContent = formatRupiah(total);
            placeOrderBtn.disabled = cart.length === 0;

            updateProductBadges();
        }

        // Menampilkan badge jumlah di kartu produk kiri, sesuai isi keranjang
        function updateProductBadges() {
            document.querySelectorAll('[data-product-slug]').forEach(card => {
                const slug = card.dataset.productSlug;
                const badge = document.getElementById('badge-' + slug);
                const item = cart.find(i => i.slug === slug);

                if (item) {
                    badge.textContent = item.qty;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            });
        }

        function placeOrder() {
            if (cart.length === 0) return;
            // TODO: ganti dengan submit ke backend (route POST transaksi)
            alert('Order placed! (integrasi backend menyusul)');
            cart = [];
            renderCart();
        }

        // ---------- Pencarian produk ----------
        function filterProducts(keyword) {
            keyword = keyword.trim().toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const match = card.dataset.search.includes(keyword);
                card.classList.toggle('hidden', !match);
                if (match) visibleCount++;
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount !== 0);
        }

        // ---------- Jam & tanggal live (dikunci ke WITA) ----------
        function updateClock() {
            const now = new Date();
            const dateFmt = new Intl.DateTimeFormat('en-GB', {
                weekday: 'short', day: '2-digit', month: 'short', year: 'numeric', timeZone: 'Asia/Makassar'
            }).format(now);
            const timeFmt = new Intl.DateTimeFormat('en-GB', {
                hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Makassar'
            }).format(now);

            document.getElementById('liveDate').textContent = dateFmt;
            document.getElementById('liveTime').textContent = timeFmt + ' WITA';
        }

        updateClock();
        setInterval(updateClock, 1000);
        renderCart();
    </script>

@endsection