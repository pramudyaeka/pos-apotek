@extends('layout.sidebar')
@section('title', 'Ubah Password')
@section('content')
<div class="max-w-xl">
    <p class="text-sm text-gray-400 mb-2">
        Akun <span class="mx-1">&gt;</span> <span class="text-gray-900 font-medium">Ubah Password</span>
    </p>

    <div class="mb-6">
        <h1 class="font-['Space_Grotesk'] font-bold text-3xl text-gray-900">Ubah Password</h1>
        <p class="text-gray-500 mt-1">Perbarui password akun Anda untuk menjaga keamanan akun.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-7">
        <form id="passwordForm" class="space-y-5">
            @csrf
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Saat Ini</label>
                <div class="relative">
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-11 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent"
                        placeholder="Masukkan password saat ini">
                    <button type="button" onclick="togglePassword('current_password', this)" aria-label="Tampilkan password"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">Lihat</button>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                <input id="password" name="password" type="password" autocomplete="new-password"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent"
                    placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F4D3D] focus:border-transparent"
                    placeholder="Ulangi password baru">
            </div>

            <div class="rounded-xl bg-[#1F4D3D]/5 border border-[#1F4D3D]/10 px-4 py-3 text-sm text-gray-600">
                Password disimpan secara aman dalam bentuk terenkripsi dan tidak dapat dilihat kembali setelah disimpan.
            </div>

            <button type="submit" id="passwordSubmit"
                class="w-full py-3 rounded-xl font-semibold text-sm text-white bg-[#1F4D3D] hover:bg-[#173B2F] transition">
                Simpan Password Baru
            </button>
        </form>
    </div>
</div>

<script>
function togglePassword(id, button){
    const input=document.getElementById(id);
    const visible=input.type==='text';
    input.type=visible?'password':'text';
    button.textContent=visible?'Lihat':'Sembunyikan';
}
document.getElementById('passwordForm')?.addEventListener('submit',async e=>{
    e.preventDefault();
    const form=e.currentTarget, button=document.getElementById('passwordSubmit');
    const data=Object.fromEntries(new FormData(form).entries());
    button.disabled=true; button.textContent='Menyimpan...';
    try{
        const response=await fetch('{{ route('password.update') }}',{
            method:'PUT',
            headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body:JSON.stringify(data)
        });
        const result=await response.json();
        if(!response.ok){
            showToast(result.message||Object.values(result.errors||{}).flat().join(' ')||'Password gagal diubah.','error');
            return;
        }
        form.reset();
        showToast('Password berhasil diubah.','success');
    }catch(error){
        showToast('Tidak dapat terhubung ke server. Silakan coba lagi.','error');
    }finally{
        button.disabled=false; button.textContent='Simpan Password Baru';
    }
});
</script>
@endsection
