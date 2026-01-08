<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request) {
        $credentials = $request->validate([
            'name' => 'required', 
            'password' => 'required'
        ], [
            // Kustomisasi pesan error login (opsional)
            'name.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->intended('dashboard');
        }
        
        return back()->withErrors(['name' => 'Nama atau password salah.']);
    }

    public function register(Request $request) {
        // PERBAIKAN DISINI: Menambahkan parameter kedua untuk pesan bahasa Indonesia
        $request->validate([
            'name' => 'required|unique:users', 
            'password' => 'required|min:6'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama ini sudah digunakan, silakan pilih nama lain.', // Mengganti "The name has already been taken."
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.' // Mengganti "The password field must be at least 6 characters."
        ]);

        User::create([
            'name' => $request->name,
            'email' => strtolower(str_replace(' ', '', $request->name)) . '@system.com',
            'password' => Hash::make($request->password),
            'role' => 'organizer'
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