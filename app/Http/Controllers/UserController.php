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
                     ->with('events') // Load relasi events
                     ->latest()
                     ->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() { 
        return view('admin.users.create'); 
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:users', 
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'permissions' => 'array' // Validasi array
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default user biasa
            'permissions' => $request->permissions ?? [], // Simpan permission
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

   public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        // Validasi permissions sebagai array
        $request->validate([
            'name' => 'required',
            'permissions' => 'nullable|array'
        ]);

        $data = [
            'name' => $request->name,
            // Jika tidak ada centang yang dipilih, simpan array kosong
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