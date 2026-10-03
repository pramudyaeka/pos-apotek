<!DOCTYPE html>
<html lang="en">

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
                    <button onclick="closeMobileSidebar()" aria-label="Close menu"
                        class="md:hidden text-gray-400 hover:text-gray-600 shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-4 md:px-2 lg:px-4 py-6 space-y-7">

                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Main menu</p>
                        <ul class="space-y-1">
                            @if(auth()->user()->isOwner())
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5
                                {{ request()->routeIs('dashboard') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 9.5 12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5Z" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Overview</span>
                                </a>
                            </li>
                             @endif
                            <li>
                                <a href="{{ route('orders') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5
                                {{ request()->routeIs('orders') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 4h2l1.5 9.5a2 2 0 0 0 2 1.7h7a2 2 0 0 0 2-1.6L19 8H6" />
                                        <circle cx="9" cy="19" r="1" />
                                        <circle cx="17" cy="19" r="1" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Orders</span>
                                </a>
                            </li>
                            @if(auth()->user()->isOwner())
                            <li>
                                <a href="{{ route('transaction') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('transaction') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 7h13m0 0-3-3m3 3-3 3M20 17H7m0 0 3 3m-3-3 3-3" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Transaction</span>
                                </a>
                            </li>
                             @endif
                        </ul>
                    </div>

                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Inventory</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('category') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('category') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('product') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('product') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m3.5 7.5 8.5-4 8.5 4v9l-8.5 4-8.5-4v-9Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.5 7.5 12 11.5m0 0 8.5-4M12 11.5V20" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Products</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if(auth()->user()->isOwner())
                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Report</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('reporting') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('reporting') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 4h6a1 1 0 0 1 1 1v1h1a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1V5a1 1 0 0 1 1-1Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6M9 15h6" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Reporting</span>
                                </a>
                            </li>
                        </ul>
                    
                    @endif
                    </div>

                    @if(auth()->user()->isOwner())
                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Settings</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('user-management') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('user-management') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <circle cx="12" cy="8" r="3.2" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">User Management</span>
                                </a>
                            </li>
                        </ul>
                    
                    @endif
                    </div>

                </nav>

                <div class="p-3 border-t border-gray-100 shrink-0">
                    <div
                        class="flex items-center gap-3 md:justify-center lg:justify-start px-2.5 py-2.5 rounded-xl hover:bg-gray-50 transition">
                        <div class="w-9 h-9 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                            <span class="text-[#1F4D3D] text-xs font-['Space_Grotesk'] font-semibold">
                                {{ substr(auth()->user()->name ?? 'Pranata Eka Pramudya', 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0 md:hidden lg:block">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Pranata Eka Pramudya' }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ??
                                'pranata.dyo@gmail.com' }}</p>
                        </div>
                        <button aria-label="Settings"
                            class="text-gray-400 hover:text-gray-600 shrink-0 md:hidden lg:block">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                class="w-4.5 h-4.5">
                                <circle cx="12" cy="12" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.4 13.5a7.6 7.6 0 0 0 0-3l1.8-1.4-2-3.4-2.1.7a7.6 7.6 0 0 0-2.6-1.5L14 2h-4l-.5 2.2a7.6 7.6 0 0 0-2.6 1.5l-2.1-.7-2 3.4L4.6 10a7.6 7.6 0 0 0 0 3l-1.8 1.5 2 3.4 2.1-.7c.8.7 1.7 1.2 2.6 1.5L10 22h4l.5-2.2c.9-.3 1.8-.8 2.6-1.5l2.1.7 2-3.4-1.8-1.5Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </aside>

            {{-- Kolom kanan: top bar mobile + konten halaman --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top bar khusus HP: hamburger + logo --}}
                <div class="md:hidden flex items-center gap-3 h-16 px-4 border-b border-gray-100 shrink-0">
                    <button onclick="openMobileSidebar()" aria-label="Open menu"
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

    <script>
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

</html                    @if(auth()->user()->isOwner())

                    @endif</div>

                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Inventory</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('category') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('category') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('product') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('product') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m3.5 7.5 8.5-4 8.5 4v9l-8.5 4-8.5-4v-9Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.5 7.5 12 11.5m0 0 8.5-4M12 11.5V20" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Products</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if(auth()->user()->isOwner())
                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Report</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('reporting') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('reporting') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 4h6a1 1 0 0 1 1 1v1h1a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1V5a1 1 0 0 1 1-1Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6M9 15h6" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">Reporting</span>
                                </a>
                            </li>
                        </ul>
                    
                    @endif</div>

                    @if(auth()->user()->isOwner())
                    <div>
                        <p
                            class="block md:hidden lg:block px-3 text-[10px] font-['IBM_Plex_Mono'] tracking-[0.15em] text-gray-400 uppercase mb-2">
                            Settings</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('user-management') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                                md:flex-col md:gap-1 md:px-1 md:py-3 md:text-center
                                lg:flex-row lg:gap-3 lg:px-3 lg:py-2.5 {{ request()->routeIs('user-management') ? 'bg-[#1F4D3D] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="w-5 h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 shrink-0">
                                        <circle cx="12" cy="8" r="3.2" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                                    </svg>
                                    <span class="md:text-[10px] md:leading-tight lg:text-sm">User Management</span>
                                </a>
                            </li>
                        </ul>
                    
                    @endif</div>

                </nav>

                <div class="p-3 border-t border-gray-100 shrink-0">
                    <div
                        class="flex items-center gap-3 md:justify-center lg:justify-start px-2.5 py-2.5 rounded-xl hover:bg-gray-50 transition">
                        <div class="w-9 h-9 rounded-lg bg-[#1F4D3D]/10 flex items-center justify-center shrink-0">
                            <span class="text-[#1F4D3D] text-xs font-['Space_Grotesk'] font-semibold">
                                {{ substr(auth()->user()->name ?? 'Pranata Eka Pramudya', 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0 md:hidden lg:block">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Pranata Eka Pramudya' }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ??
                                'pranata.dyo@gmail.com' }}</p>
                        </div>
                        <button aria-label="Settings"
                            class="text-gray-400 hover:text-gray-600 shrink-0 md:hidden lg:block">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                class="w-4.5 h-4.5">
                                <circle cx="12" cy="12" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.4 13.5a7.6 7.6 0 0 0 0-3l1.8-1.4-2-3.4-2.1.7a7.6 7.6 0 0 0-2.6-1.5L14 2h-4l-.5 2.2a7.6 7.6 0 0 0-2.6 1.5l-2.1-.7-2 3.4L4.6 10a7.6 7.6 0 0 0 0 3l-1.8 1.5 2 3.4 2.1-.7c.8.7 1.7 1.2 2.6 1.5L10 22h4l.5-2.2c.9-.3 1.8-.8 2.6-1.5l2.1.7 2-3.4-1.8-1.5Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </aside>

            {{-- Kolom kanan: top bar mobile + konten halaman --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top bar khusus HP: hamburger + logo --}}
                <div class="md:hidden flex items-center gap-3 h-16 px-4 border-b border-gray-100 shrink-0">
                    <button onclick="openMobileSidebar()" aria-label="Open menu"
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

    <script>
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
