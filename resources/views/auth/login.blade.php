@extends('layout.app')
@section('title', 'Sign In')
@section('content')

    <section class="min-h-screen flex items-center justify-center bg-[#EEF2EF] p-4">
        <div
            class="w-full max-w-4xl rounded-3xl overflow-hidden shadow-xl shadow-black/5 bg-white flex flex-col md:flex-row relative">

            {{-- Panel kiri: label resep --}}
            <div
                class="hidden md:flex md:w-[42%] bg-[#1F4D3D] flex-col justify-between px-10 py-10 relative overflow-hidden">
                {{-- tekstur titik ala blister pack --}}
                <div class="absolute inset-0 opacity-[0.07]"
                    style="background-image: radial-gradient(circle, white 2.5px, transparent 2.5px); background-size: 22px 22px;">
                </div>

                <div class="relative">
                    <p class="font-['IBM_Plex_Mono'] text-[11px] tracking-[0.25em] text-emerald-200/60 uppercase mb-6">Rx //
                        Pos System</p>
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-5">
                        <span class="text-white text-xl font-['Space_Grotesk'] leading-none">+</span>
                    </div>
                    <h1 class="font-['Space_Grotesk'] font-semibold text-2xl text-white leading-snug">
                        Apotek Sehat<br>Sentosa
                    </h1>
                </div>

                {{-- baris data ala label kemasan obat --}}
                <div class="relative font-['IBM_Plex_Mono'] text-[11px] text-emerald-100/50 space-y-1.5 leading-relaxed">
                    <p>SIMPAN DI TEMPAT SEJUK &amp; KERING</p>
                    <p>BRANCH — 01 · SINGLE STORE</p>
                    <p>AKSES TERBATAS UNTUK STAF TERDAFTAR</p>
                </div>
            </div>

            {{-- Garis perforasi vertikal (hanya tampil saat split aktif, md ke atas) --}}
            <div class="hidden md:block absolute top-0 bottom-0 left-[42%] w-4 -ml-2 z-10"
                style="background-image: radial-gradient(circle, #EEF2EF 6px, transparent 6.5px); background-size: 16px 16px; background-position: center;">
            </div>

            {{-- Panel kanan: form --}}
            <div class="w-full md:w-[58%] px-8 py-10 md:px-12 flex flex-col justify-center">
                <div class="w-full max-w-sm mx-auto">
                    <h2 class="font-['Space_Grotesk'] font-semibold text-2xl text-gray-900">Sign in</h2>
                    <p class="text-sm text-gray-500 mt-1 mb-7">Enter your credentials to open the till.</p>

                    <form action="" method="POST" class="flex flex-col">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" id="email" name="email" autocomplete="username"
                                class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                        </div>

                        <div class="mb-5">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <input type="password" id="password" name="password" autocomplete="current-password"
                                class="border border-gray-300 rounded-xl py-2.5 px-4 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent transition">
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label for="remember" class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="remember" name="remember"
                                    class="rounded h-4 w-4 text-[#1F4D3D] focus:ring-[#1F4D3D] border-gray-300">
                                <span class="ml-2 text-sm text-gray-600">Keep me signed in</span>
                            </label>
                            <a href="#" class="text-sm text-[#B8632E] hover:text-[#96502A] font-medium">Forgot password?</a>
                        </div>
                        <a href="{{ route('dashboard') }}"class="bg-[#1F4D3D] hover:bg-[#173B2F] text-white font-semibold py-3 px-4 rounded-xl w-full transition flex items-center justify-center">
                            Sign In
                        </a>

                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection