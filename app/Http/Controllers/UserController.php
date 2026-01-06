<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        // Hanya ambil organizer, admin tidak ditampilkan
        $users = User::where('role', 'organizer')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() { return view('admin.users.create'); }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:users', 'password' => 'required']);
        User::create([
            'name' => $request->name,
            'email' => uniqid() . '@system.com',
            'password' => Hash::make($request->password),
            'role' => 'organizer'
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User penyelenggara ditambahkan!');
    }

    public function destroy(User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}