@extends('layout.sidebar')
@section('title', 'Profil Akun')
@section('content')
<div class="max-w-xl">
    <p class="text-sm text-gray-400 mb-2">
        Akun <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Profil Akun</span>
    </p>

    <div class="mb-6">
        <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Profil Akun</h1>
        <p class="text-gray-500 mt-1">Perbarui nama yang ditampilkan di dalam sistem.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-7">
        <form id="profileForm" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                <input id="name" name="name" type="text" value="{{ auth()->user()->name }}" maxlength="255" autocomplete="name"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent"
                    placeholder="Masukkan nama Anda">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                <input type="text" value="{{ auth()->user()->username }}" disabled
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 text-gray-500 px-4 py-3 text-sm cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1.5">Username digunakan untuk masuk dan tidak dapat diubah dari menu ini.</p>
            </div>

            <div class="rounded-xl bg-[#1F4D3D]/5 border border-[#1F4D3D]/10 px-4 py-3 text-sm text-gray-600">
                Perubahan nama akan tercatat di Aktivitas Sistem.
            </div>

            <button type="submit" id="profileSubmit"
                class="w-full py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('profileForm')?.addEventListener('submit',async e=>{
    e.preventDefault();
    const form=e.currentTarget, button=document.getElementById('profileSubmit');
    const data=Object.fromEntries(new FormData(form).entries());
    button.disabled=true; button.textContent='Menyimpan...';
    try{
        const response=await fetch('{{ route('profile.update') }}',{
            method:'PUT',
            headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body:JSON.stringify(data)
        });
        const result=await response.json();
        if(!response.ok){
            showToast(result.message||Object.values(result.errors||{}).flat().join(' ')||'Nama gagal diperbarui.','error');
            return;
        }
        showToast('Nama berhasil diperbarui.','success');
        setTimeout(()=>window.location.reload(),500);
    }catch(error){
        showToast('Tidak dapat terhubung ke server. Silakan coba lagi.','error');
    }finally{
        button.disabled=false; button.textContent='Simpan Perubahan';
    }
});
</script>
@endsection
