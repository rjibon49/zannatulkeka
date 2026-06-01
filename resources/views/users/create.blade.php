<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">Add New User</h2>
            <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800">← Back</a>
        </div>
    </x-slot>

    <div x-data="{ 
            showImageModal: false, 
            selectedImageId: '{{ old('profile_picture_id') }}', 
            selectedImagePath: '' 
        }" 
        class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm text-center">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-4">Profile Picture</label>
                        
                        <input type="hidden" name="profile_picture_id" :value="selectedImageId">
                        
                        <div @click="showImageModal = true" class="relative group mx-auto w-32 h-32 rounded-full border-4 border-gray-100 dark:border-gray-700 overflow-hidden cursor-pointer bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                            
                            <img x-show="selectedImageId" :src="selectedImagePath" class="w-full h-full object-cover" style="display: none;">
                            
                            <svg x-show="!selectedImageId" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <span class="text-white text-[10px] font-bold uppercase tracking-wide">Change</span>
                            </div>
                        </div>
                        
                        <p class="text-[10px] text-gray-400 mt-4 leading-relaxed">Click the icon to select an image from your Media Library.</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 focus:ring-blue-500/20">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Role</label>
                                <select name="role" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 text-sm">
                                    <option value="contributor" {{ (isset($user) && $user->role == 'contributor') ? 'selected' : '' }}>Contributor</option>
                                    <option value="admin" {{ (isset($user) && $user->role == 'admin') ? 'selected' : '' }}>Admin</option>
                                    
                                    {{-- শুধুমাত্র Super Admin-রাই ড্রপডাউনে "Super Admin" অপশনটি দেখতে পাবে --}}
                                    @if(auth()->user()->isSuperAdmin())
                                        <option value="super_admin" {{ (isset($user) && $user->role == 'super_admin') ? 'selected' : '' }}>Super Admin</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 focus:ring-blue-500/20">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password</label>
                                <input type="password" name="password" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 focus:ring-blue-500/20">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 focus:ring-blue-500/20">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-md">Create User Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div x-show="showImageModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="showImageModal = false" class="bg-white dark:bg-gray-800 w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col h-[85vh] border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">Select Profile Picture</h3>
                    <button type="button" @click="showImageModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-200 dark:bg-gray-700 p-1.5 rounded-full transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @forelse($media as $img)
                            <div @click="selectedImageId = '{{ $img->id }}'; selectedImagePath = '{{ asset($img->file_path) }}'; showImageModal = false;" 
                                 class="cursor-pointer border-2 border-transparent hover:border-blue-500 rounded-xl overflow-hidden aspect-square bg-gray-100 dark:bg-gray-900 transition group relative">
                                <img src="{{ asset($img->file_path) }}" class="object-cover w-full h-full">
                                <div class="absolute inset-0 bg-blue-500/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                    <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded">Select</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 text-center">
                                <p class="text-gray-500 dark:text-gray-400">No media available. Upload in Media Library first.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>