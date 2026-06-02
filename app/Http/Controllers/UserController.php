<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::with('profilePicture')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = User::roles();

        return view('users.index', compact('users', 'search', 'role', 'status', 'roles'));
    }

    public function create()
    {
        $media = MediaLibrary::where('type', 'image')
            ->latest()
            ->get();

        $roles = User::roles();

        return view('users.create', compact('media', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(User::roles())],
            'profile_picture_id' => ['nullable', 'exists:media_libraries,id'],
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'profile_picture_id' => $validated['profile_picture_id'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $media = MediaLibrary::where('type', 'image')
            ->latest()
            ->get();

        $roles = User::roles();

        return view('users.edit', compact('user', 'media', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && !$this->canModifySuperAdmin($user)) {
            abort(403, 'You cannot modify this Super Admin account.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(User::roles())],
            'profile_picture_id' => ['nullable', 'exists:media_libraries,id'],
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
        ]);

        if ($user->isSuperAdmin() && $validated['role'] !== User::ROLE_SUPER_ADMIN) {
            if (User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1) {
                return redirect()
                    ->back()
                    ->with('error', 'At least one Super Admin account is required.');
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->profile_picture_id = $validated['profile_picture_id'] ?? null;
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ((int) auth()->id() === (int) $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin()) {
            if (User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1) {
                return redirect()
                    ->route('users.index')
                    ->with('error', 'At least one Super Admin account is required.');
            }
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function canModifySuperAdmin(User $user): bool
    {
        return auth()->user()?->isSuperAdmin() && (int) auth()->id() === (int) $user->id
            || auth()->user()?->isSuperAdmin();
    }
}