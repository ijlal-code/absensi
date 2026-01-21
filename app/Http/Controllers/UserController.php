<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        // Ambil user biasa beserta agenda yang mereka buat
        $users = User::where('role', '!=', 'admin')
                     ->with('events')
                     ->latest()
                     ->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() { 
        // Pastikan Anda sudah membuat file view ini
        return view('admin.users.create'); 
    }

    public function store(Request $request) {
        // 1. VALIDASI: Hapus validasi email
        $request->validate([
            'name' => 'required|unique:users', 
            // 'email' => 'required|email|unique:users', // <--- HAPUS BARIS INI
            'password' => 'required',
            'permissions' => 'array'
        ]);

        // 2. SIMPAN: Hapus field email
        User::create([
            'name' => $request->name,
            // 'email' => $request->email, // <--- HAPUS BARIS INI
            'password' => Hash::make($request->password),
            'role' => 'user',
            'permissions' => $request->permissions ?? [],
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|unique:users,name,'.$user->id, // Tambahkan ignore id agar tidak error saat update diri sendiri
            'permissions' => 'nullable|array'
        ]);

        $data = [
            'name' => $request->name,
            'permissions' => $request->permissions ?? [], 
        ];

        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Hak akses user diperbarui!');
    }

    public function destroy(User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}