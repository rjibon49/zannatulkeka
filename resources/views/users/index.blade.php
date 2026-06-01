<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">User Management</h2>
            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-xl text-sm transition">
                + Add New User
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-24 right-6 z-50 flex items-center bg-white dark:bg-gray-800 border border-emerald-500/20 shadow-xl rounded-xl px-4 py-3 gap-3">
            <span class="bg-emerald-500/10 text-emerald-500 rounded-full p-1.5"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-24 right-6 z-50 flex items-center bg-white dark:bg-gray-800 border border-rose-500/20 shadow-xl rounded-xl px-4 py-3 gap-3">
            <span class="bg-rose-500/10 text-rose-500 rounded-full p-1.5"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ session('error') }}</span>
        </div>
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white dark:bg-gray-800 p-3 mb-6 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm">
            <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" class="w-full rounded-xl border-none bg-gray-50 dark:bg-gray-900/40 text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500/20" placeholder="Search users by name or email...">
                <button type="submit" class="bg-gray-900 dark:bg-gray-700 hover:bg-black text-white px-6 rounded-xl text-sm font-semibold transition">Search</button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50/70 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase">User Info</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase">Role</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->profilePicture)
                                            <img src="{{ asset($user->profilePicture->file_path) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover border-2 border-gray-100 dark:border-gray-700">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg border-2 border-transparent">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold tracking-wider rounded-full uppercase
                                        {{ $user->role == 'admin' ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20' : '' }}
                                        {{ $user->role == 'contributor' ? 'bg-blue-500/10 text-blue-500 border border-blue-500/20' : '' }}
                                        {{ $user->role == 'subscriber' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : '' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700 transition bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 p-2 rounded-lg" title="Edit">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 transition bg-rose-50 hover:bg-rose-100 dark:bg-gray-700 dark:hover:bg-gray-600 p-2 rounded-lg" title="Delete">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700/50">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>