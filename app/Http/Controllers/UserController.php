<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::with('profilePicture')->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when(!auth()->user()->isSuperAdmin(), fn($query) => $query->where('role', '!=', 'super_admin'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        $media = MediaLibrary::latest()->get();
        return view('users.create', compact('media'));
    }

    public function store(Request $request)
    {
        $allowedRoles = auth()->user()->isSuperAdmin() ? 'super_admin,admin,contributor' : 'admin,contributor';

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:' . $allowedRoles,
            'profile_picture_id' => 'nullable|exists:media_libraries,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_picture_id' => $request->profile_picture_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'You do not have permission to edit a Super Admin.');
        }

        $media = MediaLibrary::latest()->get();
        return view('users.edit', compact('user', 'media'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'You do not have permission to update a Super Admin.');
        }

        $allowedRoles = auth()->user()->isSuperAdmin() ? 'super_admin,admin,contributor' : 'admin,contributor';

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:' . $allowedRoles,
            'profile_picture_id' => 'nullable|exists:media_libraries,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->profile_picture_id = $request->profile_picture_id;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself!');
        }
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'You do not have permission to delete a Super Admin.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}