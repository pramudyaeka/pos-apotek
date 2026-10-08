<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){return view('owner.setting.user_management',['users'=>User::orderBy('name')->get()]);}

    public function password(){return view('account.password');}

    public function changePassword(Request $request){
        $data=$request->validate([
            'current_password'=>'required|string',
            'password'=>'required|string|min:8|confirmed',
        ],[
            'current_password.required'=>'Password saat ini wajib diisi.',
            'password.required'=>'Password baru wajib diisi.',
            'password.min'=>'Password baru minimal 8 karakter.',
            'password.confirmed'=>'Konfirmasi password baru tidak cocok.',
        ]);

        if(!Hash::check($data['current_password'],$request->user()->password)){
            return response()->json(['message'=>'Password saat ini tidak sesuai.'],422);
        }

        $request->user()->update(['password'=>Hash::make($data['password'])]);
        ActivityLog::record($request->user(), 'User', 'update', 'Mengubah password akun sendiri.', User::class, $request->user()->id);

        return response()->json(['message'=>'Password berhasil diubah.']);
    }

    public function resetPassword(Request $request, User $user){
        if($user->id === $request->user()->id){
            abort(422,'Untuk mengubah password sendiri, gunakan menu Ubah Password.');
        }

        $temporaryPassword=substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789'),0,10);
        $user->update(['password'=>Hash::make($temporaryPassword)]);

        ActivityLog::record($request->user(), 'User', 'update', 'Mereset password akun '.$user->name.'.', User::class, $user->id);

        return response()->json([
            'message'=>'Password berhasil direset.',
            'temporary_password'=>$temporaryPassword,
            'user_name'=>$user->name,
        ]);
    }

    public function store(Request $request){
        $data=$request->validate(['name'=>'required|string|max:255','email'=>'required|email|max:255|unique:users,email','password'=>'required|string|min:8','role'=>['required',Rule::in(['Owner','Cashier'])],'status'=>['required',Rule::in(['Active','Inactive'])]]);
        $created = User::create($data);
        ActivityLog::record($request->user(), 'User', 'create', 'Membuat akun '.$created->name.' dengan role '.$created->role.'.', User::class, $created->id);
        return response()->json($created,201);
    }
    public function update(Request $request, User $user){
        $data=$request->validate(['name'=>'required|string|max:255','email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user->id)],'password'=>'nullable|string|min:8','role'=>['required',Rule::in(['Owner','Cashier'])],'status'=>['required',Rule::in(['Active','Inactive'])]]);
        if($user->id===$request->user()->id && $data['status']!=='Active') abort(422,'Anda tidak dapat menonaktifkan akun sendiri.');
        if($user->role==='Owner' && $data['role']!=='Owner' && User::where('role','Owner')->count()<=1) abort(422,'Minimal harus ada satu Owner.');
        if($user->role==='Owner' && $data['status']!=='Active' && User::where('role','Owner')->where('status','Active')->count()<=1) abort(422,'Minimal harus ada satu Owner aktif.');
        if(empty($data['password'])) unset($data['password']); else $data['password']=Hash::make($data['password']);
        $user->update($data);
        ActivityLog::record($request->user(), 'User', 'update', 'Memperbarui akun '.$user->name.' ('.$user->role.', '.$user->status.').', User::class, $user->id);
        return response()->json($user);
    }
    public function destroy(Request $request, User $user){
        if($user->id===$request->user()->id) abort(422,'Anda tidak dapat menghapus akun sendiri.');
        if($user->role==='Owner' && User::where('role','Owner')->count()<=1) abort(422,'Minimal harus ada satu Owner.');
        $name = $user->name;
        $id = $user->id;
        $user->delete();
        ActivityLog::record($request->user(), 'User', 'delete', 'Menghapus akun '.$name.'.', User::class, $id);
        return response()->json(['message'=>'Pengguna berhasil dihapus.']);
    }
}