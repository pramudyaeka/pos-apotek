@extends('layout.sidebar')
@section('title', 'Penjualan')
@section('content')

    {{-- Breadcrumb --}}
    <p class="text-sm text-gray-400 mb-2">
        Menu Utama <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Penjualan</span>
    </p>

    <div class="mb-6">
        <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Penjualan</h1>
        <p class="text-gray-500 mt-1">Kelola dan pantau penjualan dalam satu halaman</p>
    </div>

    {{-- Cari + live date/time --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m20 20-3.2-3.2"/></svg>
            </span>
            <input type="text" id="productCari" placeholder="Cari produk..." autocomplete="off" oninput="filterProducts(this.value)"
                class="pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm w-full bg-white focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
        </div>

        <div class="flex flex-wrap gap-2 shrink-0">
            <div class="flex items-center gap-2.5 border border-gray-200 rounded-xl px-4 py-3 bg-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5 text-gray-500 shrink-0"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path stroke-linecap="round" d="M8 3v4M16 3v4M3.5 9.5h17"/></svg>
                <span id="liveTanggal" class="text-sm font-medium text-gray-700 whitespace-nowrap"></span>
            </div>
            <div class="flex items-center gap-2.5 border border-gray-200 rounded-xl px-4 py-3 bg-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4.5 h-4.5 text-gray-500 shrink-0"><circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2"/></svg>
                <span id="liveWaktu" class="text-sm font-medium text-gray-700 whitespace-nowrap"></span>
            </div>
        </div>
    </div>

    {{-- Konten utama: grid produk (kiri) + ringkasan order (kanan) --}}
    <div class="grid grid-cols-1 md:grid-cols-[1fr_300px] lg:grid-cols-[1fr_360px] gap-5 md:gap-4 lg:gap-6 items-start">

        {{-- Grid produk --}}
        <div class="min-w-0">
            <div class="mb-3 flex items-center justify-between"><p id="productResultCount" class="text-xs text-gray-400">{{ $products->count() }} produk tersedia</p><button type="button" onclick="clearCart()" class="text-xs font-semibold text-gray-500 hover:text-red-600 transition">Kosongkan keranjang</button></div>
            <div id="productGrid" class="grid grid-cols-2 min-[860px]:grid-cols-3 gap-3 min-[860px]:gap-4 min-h-0 max-h-[calc(100vh-280px)] overflow-y-auto pr-1 pb-2">
            @foreach ($products as $p)
                @php $slug = \Illuminate\Support\Str::slug($p->name); $initials = strtoupper(substr($p->name, 0, 2)); @endphp
                <button type="button" data-product-id="{{ $p->id }}" data-product-name="{{ $p->name }}" data-product-price="{{ $p->price }}"
                    data-product-stock="{{ $p->stock }}" data-product-initials="{{ $initials }}" data-product-slug="{{ $slug }}"
                    data-search="{{ strtolower($p->name.' '.($p->category?->name ?? '')) }}"
                    onclick="addToCart(this)"
                    class="product-card text-left bg-white rounded-2xl border border-gray-100 p-3 hover:border-[#1F4D3D]/30 hover:shadow-md active:scale-[0.97] transition">
                    <div class="relative aspect-square rounded-xl bg-[#1F4D3D]/8 flex items-center justify-center mb-3">
                        <span class="font-['Space_Grotesk'] font-semibold text-2xl text-[#1F4D3D]/70">{{ $initials }}</span>
                        <span id="badge-{{ $p->id }}" class="hidden absolute top-2 right-2 min-w-[24px] h-6 px-1.5 rounded-full bg-[#1F4D3D] text-white text-[12px] font-semibold items-center justify-center">0</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $p->name }}</p>
                    <div class="flex items-center justify-between gap-1.5 mt-2">
                        <span class="text-[11px] font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full truncate">{{ $p->category?->name }}</span>
                        @if($p->stock <= 0)<span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">Habis</span>@elseif($p->stock <= $p->min_stock)<span class="text-[10px] font-semibold text-[#B8632E] bg-[#B8632E]/10 px-2 py-1 rounded-full">Menipis</span>@endif
                        <span class="text-sm font-semibold text-gray-900 shrink-0">Rp{{ number_format($p->price, 0, ',', '.') }}</span>
                    </div>
                </button>
            @endforeach

            <p id="noResults" class="hidden col-span-full text-center text-sm text-gray-400 py-10">
                Tidak ada produk yang sesuai dengan pencarian.
            </p>
            </div>
        </div>

        {{-- Ringkasan order --}}
        <div class="bg-[#F5F6F4] rounded-2xl border border-gray-100 md:sticky md:top-6 flex flex-col min-h-0 max-h-[calc(100vh-160px)]">

            <div class="text-center px-6 pt-6 pb-4 shrink-0">
                <h2 class="font-['Space_Grotesk'] font-semibold text-xl text-gray-900">Ringkasan Penjualan</h2>
                <p id="orderNumber" class="text-sm text-gray-400 mt-1">Pesanan Baru</p>
            </div>

            <div id="cartList" class="flex-1 min-h-0 overflow-y-auto px-4 space-y-3 pb-2"></div>

            <p id="cartEmpty" class="hidden text-center text-sm text-gray-400 px-6 py-8">

                Belum ada produk — pilih produk untuk memulai penjualan.
            </p>

            <div class="px-6 pt-4 pb-6 border-t border-gray-200/70 shrink-0 bg-[#F5F6F4] rounded-b-2xl">
                <div class="flex items-center justify-between text-sm text-gray-500 mb-1.5">
                    <span>Subtotal</span>
                    <span id="cartSubtotal">Rp 0</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    <span>Pajak</span>
                    <span id="cartTax">Rp 0</span>
                </div>
                <div class="mb-4">
                    <label for="paymentMetode" class="block text-xs font-medium text-gray-500 mb-1.5">Metode Pembayaran</label>
                    <select id="paymentMetode" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D]">
                        <option value="Cash">Tunai</option>
                        <option value="Debit">Debit</option>
                        <option value="QRIS">QRIS</option>
                    </select>
                </div>
                <div class="border-t border-dashed border-gray-300 pt-3 flex items-center justify-between mb-5">
                    <span class="font-['Space_Grotesk'] font-semibold text-gray-900">TOTAL</span>
                    <span id="cartTotal" class="font-['Space_Grotesk'] font-bold text-lg text-gray-900">Rp 0</span>
                </div>

                <button id="placeOrderBtn" onclick="placeOrder()" disabled
                    class="w-full py-4 rounded-xl font-['Space_Grotesk'] font-semibold text-white bg-[#1F4D3D] hover:bg-[#173B2F] disabled:bg-gray-300 disabled:cursor-not-allowed transition">
                    Buat Pesanan
                </button>
            </div>
        </div>

    </div>

    <script>
        let cart = [];
        let orderSubmitting = false;
        function clearCart(){if(!cart.length){showToast('Keranjang sudah kosong.','info');return;}if(!window.confirmAction('Kosongkan semua item dalam pesanan?'))return;cart=[];document.getElementById('orderNumber').textContent='Pesanan Baru';renderCart();showToast('Keranjang dikosongkan.','info');}

        function formatRupiah(n){return 'Rp '+Number(n).toLocaleString('id-ID');}
        function escapeHtml(value){return String(value).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[m]);}
        function addToCart(el){
            const productId=Number(el.dataset.productId), stock=Number(el.dataset.productStock);
            const existing=cart.find(i=>i.product_id===productId);
            if(stock <= 0){showToast('Produk sedang habis.','warning');return;} if(existing){if(existing.qty>=stock){showToast('Jumlah melebihi stok yang tersedia.','warning');return;}existing.qty++;}
            else cart.push({product_id:productId,name:el.dataset.productName,price:Number(el.dataset.productPrice),qty:1,initials:el.dataset.productInitials,slug:el.dataset.productSlug,stock});
            renderCart();
        }
        function changeQty(index,delta){
            const item=cart[index]; item.qty+=delta;
            if(item.qty<=0)cart.splice(index,1);
            if(item&&item.qty>item.stock)item.qty=item.stock;
            renderCart();
        }
        function renderCart(){
            const list=document.getElementById('cartList'), empty=document.getElementById('cartEmpty'), btn=document.getElementById('placeOrderBtn');
            if(!cart.length){list.innerHTML='';empty.classList.remove('hidden');}
            else{empty.classList.add('hidden');list.innerHTML=cart.map((item,index)=>`
                <div class="flex items-center gap-3 bg-white rounded-2xl p-3">
                    <div class="w-14 h-14 rounded-xl bg-[#1F4D3D]/8 flex items-center justify-center shrink-0"><span class="font-['Space_Grotesk'] font-semibold text-[#1F4D3D]/70">${item.initials}</span></div>
                    <div class="flex-1 min-w-0"><p class="text-sm font-semibold text-gray-900 truncate">${escapeHtml(item.name)}</p><p class="text-sm text-gray-500">${formatRupiah(item.price)}</p></div>
                    <div class="flex items-center gap-1 bg-gray-100 rounded-full p-1 shrink-0">
                        <button type="button" aria-label="Kurangi ${escapeHtml(item.name)}" onclick="changeQty(${index},-1)" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-white text-gray-600 font-medium">-</button>
                        <span class="w-6 text-center text-sm font-semibold text-gray-900">${item.qty}</span>
                        <button type="button" aria-label="Tambah ${escapeHtml(item.name)}" onclick="changeQty(${index},1)" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-white text-gray-600 font-medium">+</button>
                    </div>
                </div>`).join('');}
            const subtotal=cart.reduce((sum,i)=>sum+i.price*i.qty,0);
            document.getElementById('cartSubtotal').textContent=formatRupiah(subtotal);
            document.getElementById('cartTax').textContent=formatRupiah(0);
            document.getElementById('cartTotal').textContent=formatRupiah(subtotal);
            const count=cart.reduce((sum,i)=>sum+i.qty,0); document.getElementById('productResultCount').textContent=count ? count+' produk dalam keranjang' : document.querySelectorAll('.product-card:not(.hidden)').length+' produk tersedia';
            btn.disabled=!cart.length || orderSubmitting; updateProductBadges();
        }
        function updateProductBadges(){document.querySelectorAll('[data-product-slug]').forEach(card=>{const item=cart.find(i=>i.product_id===Number(card.dataset.productId)),badge=document.getElementById('badge-'+card.dataset.productId);if(item){badge.textContent=item.qty;badge.classList.remove('hidden');badge.classList.add('flex');}else{badge.classList.add('hidden');badge.classList.remove('flex');}});}
        async function placeOrder(){
            if(!cart.length){showToast('Tambahkan minimal satu produk ke pesanan.','warning');return;} if(orderSubmitting)return;
            const btn=document.getElementById('placeOrderBtn'); const originalText=btn.textContent; orderSubmitting=true; btn.disabled=true; btn.textContent='Memproses...';
            try {
                const response=await fetch('{{ route('sales.store') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({payment_method:document.getElementById('paymentMetode').value,items:cart.map(i=>({product_id:i.product_id,quantity:i.qty}))})});
                const data=await response.json();
                if(!response.ok){showToast(data.message||Object.values(data.errors||{}).flat().join(' ')||'Transaksi gagal.','error');return;}
                showToast('Transaksi berhasil. Nomor Faktur: '+data.invoice_number,'success',5000);
                cart=[]; document.getElementById('orderNumber').textContent='Nomor Faktur: '+data.invoice_number; renderCart();
            } catch (error) { showToast('Tidak dapat terhubung ke server. Silakan coba lagi.','error'); }
            finally { orderSubmitting=false; btn.disabled=!cart.length; btn.textContent=originalText; }
        }
        function filterProducts(keyword){keyword=keyword.trim().toLowerCase();let count=0;document.querySelectorAll('.product-card').forEach(card=>{const match=card.dataset.search.includes(keyword);card.classList.toggle('hidden',!match);if(match)count++;});document.getElementById('noResults').classList.toggle('hidden',count!==0);document.getElementById('productResultCount').textContent=count+' produk ditemukan';}
        function updateClock(){const now=new Date();document.getElementById('liveTanggal').textContent=new Intl.DateTimeFormat('id-ID',{weekday:'short',day:'2-digit',month:'short',year:'numeric',timeZone:'Asia/Makassar'}).format(now);document.getElementById('liveWaktu').textContent=new Intl.DateTimeFormat('id-ID',{hour:'2-digit',minute:'2-digit',hour12:false,timeZone:'Asia/Makassar'}).format(now)+' WITA';}
        document.addEventListener('keydown', e => {
            if(e.key==='/' && document.activeElement?.tagName!=='INPUT' && document.activeElement?.tagName!=='SELECT'){e.preventDefault();document.getElementById('productCari')?.focus();}
            if(e.key==='Escape' && document.activeElement?.id==='productCari'){document.getElementById('productCari').value='';filterProducts('');document.getElementById('productCari').blur();}
            if((e.ctrlKey||e.metaKey) && e.key==='Enter'){e.preventDefault();placeOrder();}
        });
        updateClock();setInterval(updateClock,1000);renderCart();
    </script>@endsection