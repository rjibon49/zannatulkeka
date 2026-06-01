<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Articles') }}
            </h2>
            <a href="{{ route('articles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-xl text-sm transition shadow-sm">
                + Write New Article
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-24 right-6 z-50 flex items-center bg-white dark:bg-gray-800 border border-emerald-500/20 shadow-xl rounded-xl px-4 py-3 gap-3">
            <span class="bg-emerald-500/10 text-emerald-500 rounded-full p-1.5"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ session('success') }}</span>
        </div>
    @endif

    <div class="py-6 max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white dark:bg-gray-800 p-4 mb-4 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center">
                
                <div class="w-full md:flex-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500/20" placeholder="Search by title...">
                </div>

                <div class="w-full md:w-48">
                    <select name="category_id" class="w-full py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-blue-500/20 cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-40">
                    <input type="month" name="month" value="{{ $month }}" class="w-full py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-blue-500/20 cursor-pointer">
                </div>

                <div class="w-full md:w-auto flex gap-2">
                    <button type="submit" class="flex-1 md:flex-none bg-gray-900 dark:bg-gray-700 hover:bg-black text-white px-6 py-2 rounded-xl text-sm font-semibold transition shadow-sm">Filter</button>
                    
                    @if($search || $categoryId || $month)
                        <a href="{{ route('articles.index') }}" class="flex-1 md:flex-none text-center bg-rose-50 dark:bg-rose-500/10 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-500/20 px-4 py-2 rounded-xl text-sm font-semibold transition">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="mb-4 flex items-center gap-2 px-1">
            <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Found <span class="text-blue-600 dark:text-blue-400 text-sm">{{ $articles->total() }}</span> articles</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50/70 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Image</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Title</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Categories</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($articles as $item)
                            <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition">
                                <td class="px-5 py-4 whitespace-nowrap w-24">
                                    <div class="h-12 w-20 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                        @if($item->featuredImage)
                                            <img src="{{ asset($item->featuredImage->file_path) }}" class="h-full w-full object-cover">
                                        @else
                                            <span class="flex items-center justify-center h-full text-[10px] font-bold text-gray-400">NO IMG</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-gray-800 dark:text-gray-200 w-1/3">
                                    <p class="truncate text-base">{{ $item->title }}</p>
                                    <p class="text-[11px] text-gray-400 font-normal mt-1 flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ $item->published_at ? $item->published_at->format('d M, Y - h:i A') : 'Not Published' }}
                                    </p>
                                </td>
                                <td class="px-5 py-4">
                                    @if($item->categories->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($item->categories as $category)
                                                <span class="bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-1 rounded text-[10px] font-bold tracking-wide border border-blue-100 dark:border-blue-800/50">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-xs font-medium">Uncategorized</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold tracking-wider rounded-full uppercase
                                        {{ $item->status == 'published' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : '' }}
                                        {{ $item->status == 'draft' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : '' }}
                                        {{ $item->status == 'schedule' ? 'bg-purple-500/10 text-purple-500 border border-purple-500/20' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('articles.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700 transition bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 p-2 rounded-lg" title="Edit">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        <form action="{{ route('articles.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 transition bg-rose-50 hover:bg-rose-100 dark:bg-gray-700 dark:hover:bg-gray-600 p-2 rounded-lg" title="Delete">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-16 text-gray-500 dark:text-gray-400 font-medium text-sm">No articles found matching your filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($articles->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700/50">{{ $articles->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>