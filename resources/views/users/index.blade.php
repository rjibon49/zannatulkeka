{{-- resources/views/users/index.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;

    $currentUser = auth()->user();

    $roleLabels = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'contributor' => 'Contributor',
    ];

    $roleClasses = [
        'super_admin' => 'bg-[#2f1b12] text-white ring-[#2f1b12]/10',
        'admin' => 'bg-amber-50 text-amber-800 ring-amber-200',
        'contributor' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
    ];

    $statusClasses = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'inactive' => 'bg-slate-50 text-slate-700 ring-slate-200',
        'blocked' => 'bg-red-50 text-red-700 ring-red-200',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    User Management
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Create, update and manage dashboard users.
                </p>
            </div>

            @if(Route::has('users.create'))
                <a
                    href="{{ route('users.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                >
                    <i class="fa-solid fa-user-plus"></i>
                    Add New User
                </a>
            @endif
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
            <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]">
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                        Search
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Search by name or email..."
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-3 pl-11 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                        Role
                    </label>
                    <select
                        name="role"
                        class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                    >
                        <option value="">All Roles</option>
                        @foreach(($roles ?? ['super_admin', 'admin', 'contributor']) as $roleOption)
                            <option value="{{ $roleOption }}" @selected(($role ?? '') === $roleOption)>
                                {{ $roleLabels[$roleOption] ?? ucfirst(str_replace('_', ' ', $roleOption)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                        Status
                    </label>
                    <select
                        name="status"
                        class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                    >
                        <option value="">All Status</option>
                        <option value="active" @selected(($status ?? '') === 'active')>Active</option>
                        <option value="inactive" @selected(($status ?? '') === 'inactive')>Inactive</option>
                        <option value="blocked" @selected(($status ?? '') === 'blocked')>Blocked</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="inline-flex h-[46px] items-center justify-center gap-2 rounded-2xl bg-[#1f1712] px-5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-black"
                    >
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    <a
                        href="{{ route('users.index') }}"
                        class="inline-flex h-[46px] items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-4 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-5 overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
            <div class="flex flex-col gap-3 border-b border-[#784828]/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Users
                    </h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">
                        Total {{ $users->total() }} user(s) found.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#784828]/10">
                    <thead class="bg-[#fbf7f1]">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">User</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Role</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Status</th>
                            <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wide text-[#756b62]">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#784828]/10 bg-white">
                        @forelse($users as $member)
                            <tr class="transition hover:bg-[#fbf7f1]">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#fff3df] text-sm font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                            @if($member->profilePicture?->url)
                                                <img src="{{ $member->profilePicture->url }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                            @else
                                                {{ strtoupper(mb_substr($member->name ?? 'U', 0, 1)) }}
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-black text-[#1f1712]">
                                                {{ $member->name }}
                                                @if($currentUser?->id === $member->id)
                                                    <span class="ml-1 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-black uppercase text-blue-700 ring-1 ring-blue-200">
                                                        You
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                                {{ $member->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $roleClasses[$member->role] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                        {{ $roleLabels[$member->role] ?? ucfirst(str_replace('_', ' ', $member->role)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClasses[$member->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                        {{ $member->status ?? 'active' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('users.edit', $member) }}"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                            title="Edit user"
                                        >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        @if($currentUser?->id !== $member->id)
                                            <form action="{{ route('users.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                    title="Delete user"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-14 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                        <i class="fa-solid fa-users text-xl"></i>
                                    </div>
                                    <p class="mt-3 text-sm font-bold text-[#756b62]">
                                        No users found.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="border-t border-[#784828]/10 px-5 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>