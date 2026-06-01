<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight">
            {{ __('Category Management') }}
        </h2>
    </x-slot>

    <!-- ===================================================================
        ফ্লোটিং টোস্ট মেসেজ (Alpine.js) - অটোমেটিক ৩ সেকেন্ড পর গায়েব হবে
    =================================================================== -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-8"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-8"
             class="fixed top-24 right-6 z-50 flex items-center bg-white dark:bg-gray-800 border border-emerald-500/20 shadow-xl rounded-xl px-4 py-3 gap-3">
            
            <span class="flex items-center justify-center bg-emerald-500/10 text-emerald-500 rounded-full p-1.5 shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </span>
            <span class="text-sm font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                {{ session('success') }}
            </span>
            <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="py-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start w-full">
            
            <!-- [বাম পাশের কলাম] - অ্যাড ও এডিট ফর্ম -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm p-5 sticky top-24">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-5 pb-3 border-b border-gray-100 dark:border-gray-700/50">
                    {{ $category ? 'Edit Category' : 'Add/Edit Category' }}
                </h3>

                <form action="{{ $category ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                    @csrf
                    @if($category)
                        @method('PUT')
                    @endif

                    <!-- ইনপুট: Category Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Category Name</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $category ? $category->name : '') }}" 
                               class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500/20 text-sm p-2.5 transition duration-150" 
                               placeholder="e.g. Electronics, Apparel" required>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ইনপুট: Slug (ঐচ্ছিক) -->
                    <div class="mb-4">
                        <label for="slug" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Slug (Optional)</label>
                        <input type="text" name="slug" id="slug" 
                               value="{{ old('slug', $category ? $category->slug : '') }}" 
                               class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500/20 text-sm p-2.5 transition duration-150" 
                               placeholder="e.g. auto-generated-slug">
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5">Leave empty to auto-generate from category name.</p>
                        @error('slug')
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- সিলেকশন: Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" id="status" 
                                class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500/20 text-sm p-2.5 transition duration-150">
                            <option value="active" {{ old('status', $category ? $category->status : '') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category ? $category->status : '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- অ্যাকশন বাটন -->
                    <div class="flex flex-col gap-2.5">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition duration-150 shadow-sm shadow-blue-500/10">
                            {{ $category ? 'Update Category' : 'Save Category' }}
                        </button>
                        @if($category)
                            <a href="{{ route('categories.index') }}" class="w-full text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-2 rounded-xl text-xs uppercase tracking-wider hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Cancel Edit
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- [ডান পাশের কলাম] - সার্চবার, টেবিল -->
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white dark:bg-gray-800 p-3.5 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm">
                    <form action="{{ url()->current() }}" method="GET" class="flex gap-2.5">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ $search }}" class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500/20 text-sm placeholder-gray-500" placeholder="Search categories...">
                        </div>
                        <button type="submit" class="bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white font-bold py-2 px-5 rounded-xl text-sm transition">Search</button>
                        @if($search)
                            <a href="{{ route('categories.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 font-semibold py-2 px-4 rounded-xl text-sm transition flex items-center">Clear</a>
                        @endif
                    </form>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="bg-gray-50/70 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-5 py-3.5 text-xs font-bold tracking-wider text-gray-400 uppercase">Name</th>
                                    <th class="px-5 py-3.5 text-xs font-bold tracking-wider text-gray-400 uppercase">Slug</th>
                                    <th class="px-5 py-3.5 text-xs font-bold tracking-wider text-gray-400 uppercase">Status</th>
                                    <th class="px-5 py-3.5 text-xs font-bold tracking-wider text-gray-400 uppercase text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                @if($categories->isEmpty())
                                    <tr>
                                        <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-400 dark:text-gray-500 font-medium">No categories found.</td>
                                    </tr>
                                @else
                                    @foreach($categories as $item)
                                        <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition duration-100">
                                            <td class="px-5 py-4 whitespace-nowrap text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $item->name }}</td>
                                            <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $item->slug }}</td>
                                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $item->status == 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/10' : 'bg-rose-500/10 text-rose-400 border border-rose-500/10' }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $item->status == 'active' ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-right">
                                                <div class="flex justify-end items-center gap-3.5">
                                                    <a href="{{ route('categories.edit', $item->id) }}" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition" title="Edit">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('categories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition" title="Delete">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($categories->hasPages())
                        <div class="px-5 py-3.5 bg-gray-50/30 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700/50">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>