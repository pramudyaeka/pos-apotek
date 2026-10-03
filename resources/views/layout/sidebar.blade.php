<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | POS Apotek</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500&family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Inter'] antialiased bg-white text-gray-900">
    <div class="flex min-h-screen">

        {{-- Overlay gelap, hanya aktif saat drawer mobile terbuka --}}
        <div id="sidebarOverlay" onclick="closeMobileSidebar()" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden">
        </div>

        {{--
        Sidebar — 3 mode berdasarkan breakpoint lebar layar:
        1) < md : drawer tersembunyi, dibuka via hamburger 2) md–lg : rail sempit, ikon + label kecil di bawah 3)>= lg :
            sidebar penuh, ikon + label sejajar

            Ganti breakpoint "md"/"lg" di sini (atau custom di app.css)
            sesuai titik yang Anda inginkan.
            --}}
            <aside id="sidebar" class="
                fixed md:sticky inset-y-0 md:inset-auto md:top-0 left-0 z-50
                w-64 md:w-20 lg:w-64
                md:h-screen
                -translate-x-full md:translate-x-0
                transition-transform duration-200 ease-in-out
                flex flex-col bg-white border-r border-gray-100 shrink-0
            ">

                <div
                    class="h-20 flex items-center justify-between px-6 md:px-3 lg:px-6 border-b border-gray-100 shrink-0">
                    <div
                        class="flex items-center gap-2.5 md:gap-0 lg:gap-2.5 md:justify-center lg:justify-start w-full">
                        <div class="w-8 h-8 rounded-lg bg-[#1F4D3D] flex items-center justify-center shrink-0">
                            <span class="text-white text-sm font-['Space_Grotesk'] leading-none">+</span>
                        </div>
                        <span class="font-['Space_Grotesk'] font-semibold text-lg md:hidden lg:inline">Apotek</span>
                    </div>

                    {{-- Tombol tutup, hanya tampil di mode drawer mobile --}}
                    <button onclick="closeMobileSidebar()" aria-label="Tutup menu"
                        class="md:hidden text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-4 md:px-2 lg:px-4 py-6 space-y-7">
                    <div>
                        <p class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">Operasional</p>
                        <ul class="space-y-1">
                            @if(auth()->user()->isOwner())
                            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span>⌂</span><span>Dasbor</span></a></li>
                            @endif
                            <li><a href="{{ route('orders') }}" class="nav-link {{ request()->routeIs('orders') ? 'active' : '' }}"><span>🛒</span><span>Penjualan</span></a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">Persediaan</p>
                        <ul class="space-y-1">
                            <li><a href="{{ route('product') }}" class="nav-link {{ request()->routeIs('product') ? 'active' : '' }}"><span>▣</span><span>Produk</span></a></li>
                            <li><a href="{{ route('category') }}" class="nav-link {{ request()->routeIs('category') ? 'active' : '' }}"><span>▦</span><span>Kategori</span></a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">Pemantauan</p>
                        <ul class="space-y-1">
                            <li><a href="{{ route('history') }}" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}"><span>◷</span><span>Aktivitas Sistem</span></a></li>
                            @if(auth()->user()->isOwner())
                            <li><a href="{{ route('transaction') }}" class="nav-link {{ request()->routeIs('transaction') ? 'active' : '' }}"><span>↔</span><span>Riwayat Penjualan</span></a></li>
                            @endif
                        </ul>
                    </div>

                    @if(auth()->user()->isOwner())
                    <div>
                        <p class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">Analisis</p>
                        <ul class="space-y-1">
                            <li><a href="{{ route('reporting') }}" class="nav-link {{ request()->routeIs('reporting') ? 'active' : '' }}"><span>▤</span><span>Laporan</span></a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">Administrasi</p>
                        <ul class="space-y-1">
                            <li><a href="{{ route('user-management') }}" class="nav-link {{ request()->routeIs('user-management') ? 'active' : '' }}"><span>♙</span><span>Pengguna</span></a></li>
                        </ul>
                    </div>
                    @endif
                </nav>

                <div class="p-3 border-t border-gray-100 shrink-0">
                    <div
                        class="flex items-center gap-3 md:justify-center lg:justify-start px-2.5 py-2.5 rounded-xl hover:bg-gray-50 transition">
                        <div class="w-9 h-9 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                            <span class="text-[#1F4D3D] text-xs font-['Space_Grotesk'] font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0 md:hidden lg:block">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmAction('Keluar dari sistem sekarang?')">
                            @csrf
                            <button type="submit" aria-label="Keluar"
                                class="text-gray-400 hover:text-red-500 transition shrink-0 md:hidden lg:block"
                                title="Keluar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    class="w-4.5 h-4.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14 8l4 4-4 4M18 12H9" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Kolom kanan: top bar mobile + konten halaman --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top bar khusus HP: hamburger + logo --}}
                <div class="md:hidden flex items-center gap-3 h-16 px-4 border-b border-gray-100 shrink-0">
                    <button onclick="openMobileSidebar()" aria-label="Buka menu"
                        class="text-gray-600 hover:text-gray-900">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-[#1F4D3D] flex items-center justify-center shrink-0">
                            <span class="text-white text-xs font-['Space_Grotesk'] leading-none">+</span>
                        </div>
                        <span class="font-['Space_Grotesk'] font-semibold">Apotek</span>
                    </div>
                </div>

                <main class="flex-1 p-5 md:p-6 lg:p-8">
                    @yield('content')
                </main>
            </div>

    </div>

    {{-- Global toast notification --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded',()=>showToast(@json(session('success')),'success'));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded',()=>showToast(@json(session('error')),'error'));</script>
    @endif

    <div id="toastContainer" class="fixed top-4 right-4 z-[100] w-[min(92vw,380px)] space-y-2 pointer-events-none" aria-live="polite" aria-atomic="true"></div>

    <script>
        window.showToast = function(message, type = 'success', duration = 3500) {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const styles = {
                success: { icon: '✓', tone: 'border-[#1F4D3D]/15 bg-white text-gray-800', iconTone: 'bg-[#1F4D3D]/10 text-[#1F4D3D]' },
                error: { icon: '!', tone: 'border-red-100 bg-white text-gray-800', iconTone: 'bg-red-50 text-red-600' },
                warning: { icon: '!', tone: 'border-amber-100 bg-white text-gray-800', iconTone: 'bg-amber-50 text-amber-600' },
                info: { icon: 'i', tone: 'border-blue-100 bg-white text-gray-800', iconTone: 'bg-blue-50 text-blue-600' }
            }[type] || { icon: 'i', tone: 'border-gray-100 bg-white text-gray-800', iconTone: 'bg-gray-50 text-gray-600' };
            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3.5 shadow-lg shadow-black/5 translate-x-4 opacity-0 transition-all duration-200 ' + styles.tone;
            toast.innerHTML = '<span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold ' + styles.iconTone + '">' + styles.icon + '</span><p class="min-w-0 flex-1 text-sm leading-5">' + String(message).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[m]; }) + '</p><button type="button" class="shrink-0 text-gray-400 hover:text-gray-700" aria-label="Tutup">×</button>';
            const remove = () => { toast.classList.add('opacity-0','translate-x-4'); setTimeout(() => toast.remove(), 220); };
            toast.querySelector('button').addEventListener('click', remove);
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('opacity-0','translate-x-4'));
            setTimeout(remove, duration);
        };

        window.confirmAction = function(message) {
            return window.confirm(message);
        };

        function openMobileSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        }
        function closeMobileSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        }
    </script>
</body>

</html>
