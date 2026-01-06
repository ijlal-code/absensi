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
        $credentials = $request->validate(['name' => 'required', 'password' => 'required']);
        // Login menggunakan 'name' bukan email
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['name' => 'Nama atau password salah.']);
    }

    public function register(Request $request) {
        $request->validate(['name' => 'required|unique:users', 'password' => 'required|min:6']);
        User::create([
            'name' => $request->name,
            'email' => strtolower(str_replace(' ', '', $request->name)) . '@system.com', // Email dummy otomatis
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