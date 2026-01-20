<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // <--- PENTING: Panggil Model User
use Illuminate\Support\Facades\Hash; // <--- PENTING: Untuk enkripsi password

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi login hanya nama & password
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect ke dashboard (logika admin/user diatur di EventController)
            return redirect()->intended(route('dashboard')); 
        }

        return back()->withErrors([
            'name' => 'Nama atau password salah.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request) {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|unique:users', 
            'password' => 'required|min:6'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama ini sudah digunakan, silakan pilih nama lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.'
        ]);

        // 2. SIMPAN KE DATABASE (BAGIAN YANG HILANG SEBELUMNYA)
        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => 'user', // Set default role sebagai user biasa
            'permissions' => [], // Set default permission kosong
            // Email kita biarkan null (karena database akan kita ubah agar boleh null)
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}