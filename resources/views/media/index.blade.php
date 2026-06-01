<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight tracking-tight">
            {{ __('Media Library') }}
        </h2>
    </x-slot>

    @if(session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-8" class="fixed top-24 right-6 z-[100] flex items-center bg-white dark:bg-gray-800 border {{ session('success') ? 'border-emerald-500/20' : 'border-rose-500/20' }} shadow-2xl rounded-xl px-4 py-3 gap-3">
            <span class="flex items-center justify-center {{ session('success') ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }} rounded-full p-1.5 shrink-0">
                @if(session('success'))
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                @else
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                @endif
            </span>
            <span class="text-sm font-semibold tracking-wide text-gray-800 dark:text-gray-200">{{ session('success') ?? session('error') }}</span>
            <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
    @endif

    <div x-data="{ 
        lightboxOpen: false, 
        lightboxImg: '', 
        renameModalOpen: false,
        renameUrl: '',
        renameFileName: '',
        fileCount: 0
    }" class="py-6">
        
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
                
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm p-5 sticky top-24">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Upload Media</h3>
                    
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                        @csrf
                        
                        <div class="relative w-full">
                            <input type="file" name="file[]" id="file" multiple accept="image/*" 
                                   @change="fileCount = $event.target.files.length"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                   
                            <div class="w-full h-48 bg-gray-50 dark:bg-gray-900/40 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl text-center flex flex-col items-center justify-center transition hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:border-blue-500">
                                <svg x-show="fileCount === 0" class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                
                                <div x-show="fileCount === 0">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Drag & drop files here</p>
                                    <p class="text-xs text-blue-500 mt-1 font-medium">or Browse files</p>
                                </div>

                                <div x-show="fileCount > 0" style="display: none;" class="flex flex-col items-center">
                                    <span class="bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400 px-3 py-1 rounded-full text-sm font-bold shadow-sm" x-text="fileCount + ' files selected'"></span>
                                    <p class="text-[10px] text-gray-400 mt-2">Click to change selection</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 border border-gray-200 dark:border-gray-700 rounded-xl p-1 bg-gray-50 dark:bg-gray-900/50">
                            <span class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold px-3 py-1.5 rounded-lg shrink-0">Choose Files</span>
                            <span class="text-xs text-gray-500 truncate" x-text="fileCount > 0 ? fileCount + ' files selected' : 'No file chosen...'"></span>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition duration-150 shadow-sm shadow-blue-500/10">
                            Start Upload
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-3 space-y-4">
                    
                    <div class="bg-white dark:bg-gray-800 p-2.5 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm">
                        <form action="{{ url()->current() }}" method="GET" class="flex gap-2.5">
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input type="text" name="search" value="{{ $search }}" class="w-full pl-9 pr-4 py-2 rounded-xl border-none bg-gray-50 dark:bg-gray-900/40 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500/20 text-sm placeholder-gray-400" placeholder="Search media by name...">
                            </div>
                            <button type="submit" class="hidden md:block bg-gray-900 dark:bg-gray-700 hover:bg-black text-white font-semibold py-2 px-6 rounded-xl text-sm transition">Search</button>
                            @if($search)
                                <a href="{{ route('media.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold py-2 px-4 rounded-xl text-sm transition flex items-center">Clear</a>
                            @endif
                        </form>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 shadow-sm p-4">
                        @if($media->isEmpty())
                            <div class="text-center py-16">
                                <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">No media files found.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($media as $item)
                                    <div class="group relative bg-gray-100 dark:bg-gray-900/50 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition">
                                        
                                        <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200 dark:bg-gray-800 cursor-pointer"
                                             @click="lightboxOpen = true; lightboxImg = '{{ asset($item->file_path) }}'">
                                            <img src="{{ asset($item->file_path) }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                        </div>
                                        
                                        <div class="absolute inset-0 bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-end p-2 pointer-events-none">
                                            
                                            <p class="text-white text-[10px] font-semibold truncate drop-shadow-md mb-2 px-1">
                                                {{ $item->file_name }}
                                            </p>

                                            <div class="flex items-center justify-between pointer-events-auto">
                                                <div class="flex gap-1.5" x-data="{ copied: false }">
                                                    <button @click="navigator.clipboard.writeText('{{ url($item->file_path) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="p-1.5 bg-white/20 hover:bg-blue-500 rounded-lg text-white backdrop-blur-sm transition tooltip" title="Copy URL">
                                                        <svg x-show="!copied" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                                        <svg x-show="copied" x-cloak class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                                    </button>
                                                    
                                                    <button @click="renameModalOpen = true; renameUrl = '{{ route('media.update', $item->id) }}'; renameFileName = '{{ $item->file_name }}'" class="p-1.5 bg-white/20 hover:bg-emerald-500 rounded-lg text-white backdrop-blur-sm transition tooltip" title="Rename File">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    </button>
                                                </div>

                                                <form action="{{ route('media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');" class="pointer-events-auto">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-rose-500/80 hover:bg-rose-600 rounded-lg text-white backdrop-blur-sm transition" title="Delete">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($media->hasPages())
                            <div class="mt-6 border-t border-gray-100 dark:border-gray-700/50 pt-4 flex justify-center">
                                {{ $media->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4">
            <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/50 hover:text-white bg-black/50 hover:bg-gray-800 rounded-full p-2 transition">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <img :src="lightboxImg" @click.away="lightboxOpen = false" class="max-w-full max-h-full rounded-lg shadow-2xl object-contain border border-gray-800">
        </div>

        <div x-show="renameModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="renameModalOpen = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            
            <div x-show="renameModalOpen" x-transition class="relative bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Rename File</h3>
                <form :action="renameUrl" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Display Name</label>
                        <input type="text" name="file_name" x-model="renameFileName" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500/20 text-sm">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="renameModalOpen = false" class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-sm hover:bg-gray-200 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 shadow-sm transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</x-app-layout>