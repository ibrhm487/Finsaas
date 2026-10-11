<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('company_id', auth()->user()->company_id)
            ->with('role');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'company_id' => auth()->user()->company_id,
            'status' => $request->status,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Create',
            'module' => 'User Management',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if ($user->company_id !== auth()->user()->company_id) abort(403);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->company_id !== auth()->user()->company_id) abort(403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        $data = $request->only(['name', 'email', 'role_id', 'status']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update',
            'module' => 'User Management',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->company_id !== auth()->user()->company_id) abort(403);
        $user->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'Delete',
            'module' => 'User Management',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
