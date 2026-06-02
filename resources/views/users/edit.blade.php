{{-- resources/views/users/edit.blade.php --}}
@php
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'contributor' => 'Contributor',
    ];

    $availableRoles = $roles ?? ['super_admin', 'admin', 'contributor'];
    $selectedImage = $user->profilePicture;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Edit User
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Update user account, role and access status.
                </p>
            </div>

            <a
                href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            showMediaModal: false,
            selectedImageId: @js(old('profile_picture_id', $user->profile_picture_id)),
            selectedImageUrl: @js($selectedImage?->url),
            selectedImageName: @js($selectedImage?->file_name),
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <form action="{{ route('users.update', $user) }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[360px_minmax(0,1fr)]">
            @csrf
            @method('PUT')

            <aside class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                <div class="text-center">
                    <p class="text-xs font-black uppercase tracking-wide text-[#756b62]">
                        Profile Picture
                    </p>

                    <input type="hidden" name="profile_picture_id" :value="selectedImageId">

                    <button
                        type="button"
                        @click="showMediaModal = true"
                        class="group mx-auto mt-5 flex h-40 w-40 items-center justify-center overflow-hidden rounded-[2rem] bg-[#fff3df] text-[#8b4a2f] ring-4 ring-white shadow-lg transition hover:scale-[1.02]"
                    >
                        <template x-if="selectedImageUrl">
                            <img :src="selectedImageUrl" :alt="selectedImageName || 'Selected image'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!selectedImageUrl">
                            <span class="flex flex-col items-center gap-2">
                                <i class="fa-solid fa-user text-3xl"></i>
                                <span class="text-xs font-black uppercase tracking-wide">Select Image</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-5 flex justify-center gap-2">
                        <button
                            type="button"
                            @click="showMediaModal = true"
                            class="inline-flex items-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2 text-xs font-black text-white transition hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button
                            type="button"
                            x-show="selectedImageId"
                            @click="selectedImageId = ''; selectedImageUrl = ''; selectedImageName = ''"
                            class="inline-flex items-center gap-2 rounded-2xl bg-red-50 px-4 py-2 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                        >
                            <i class="fa-solid fa-xmark"></i>
                            Remove
                        </button>
                    </div>

                    <div class="mt-6 rounded-3xl bg-[#fbf7f1] p-4 text-left">
                        <p class="text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Current Account
                        </p>
                        <p class="mt-2 truncate text-sm font-black text-[#1f1712]">
                            {{ $user->name }}
                        </p>
                        <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </aside>

            <section class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                        <i class="fa-solid fa-user-pen"></i>
                    </span>
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Account Information
                        </h3>
                        <p class="text-sm font-medium text-[#756b62]">
                            Leave password blank if you do not want to change it.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="role"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            @foreach($availableRoles as $roleOption)
                                <option value="{{ $roleOption }}" @selected(old('role', $user->role) === $roleOption)>
                                    {{ $roleLabels[$roleOption] ?? ucfirst(str_replace('_', ' ', $roleOption)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                            <option value="blocked" @selected(old('status', $user->status) === 'blocked')>Blocked</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            New Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="Leave blank to keep current"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="Confirm new password"
                        >
                    </div>
                </div>

                <div class="mt-7 flex flex-col gap-3 border-t border-[#784828]/10 pt-5 sm:flex-row sm:justify-between">
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-red-50 px-5 py-3 text-sm font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                Delete User
                            </button>
                        </form>
                    @else
                        <div></div>
                    @endif

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route('users.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update User
                        </button>
                    </div>
                </div>
            </section>
        </form>

        {{-- Media Modal --}}
        <div
            x-show="showMediaModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showMediaModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Profile Picture
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose an image from your media library.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="showMediaModal = false"
                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                        @forelse($media as $img)
                            <button
                                type="button"
                                @click="
                                    selectedImageId = '{{ $img->id }}';
                                    selectedImageUrl = '{{ $img->url }}';
                                    selectedImageName = '{{ $img->file_name }}';
                                    showMediaModal = false;
                                "
                                class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-2 ring-transparent transition hover:-translate-y-1 hover:ring-[#8b4a2f]"
                            >
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?: $img->file_name }}" class="h-full w-full object-cover">

                                <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/35">
                                    <span class="scale-90 rounded-full bg-white px-3 py-1 text-xs font-black text-[#8b4a2f] opacity-0 transition group-hover:scale-100 group-hover:opacity-100">
                                        Select
                                    </span>
                                </div>

                                <div
                                    x-show="selectedImageId == '{{ $img->id }}'"
                                    class="absolute inset-0 rounded-3xl border-4 border-[#8b4a2f]"
                                ></div>
                            </button>
                        @empty
                            <div class="col-span-full py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                    <i class="fa-solid fa-image text-xl"></i>
                                </div>
                                <p class="mt-3 text-sm font-bold text-[#756b62]">
                                    No images found. Upload images from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>