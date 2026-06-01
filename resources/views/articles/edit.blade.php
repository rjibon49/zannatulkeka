<x-app-layout>
    <div x-data="{ 
            title: `{{ old('title', addslashes($article->title)) }}`, 
            metaTitle: `{{ old('meta_title', addslashes($article->meta_title)) }}`, 
            metaDesc: `{{ old('meta_description', addslashes($article->meta_description)) }}`,
            status: '{{ old('status', $article->status) }}',
            
            showImageModal: false,
            selectedImageId: '{{ old('featured_media_id', $article->featured_media_id) }}',
            selectedImagePath: '{{ $article->featuredImage ? asset($article->featuredImage->file_path) : '' }}',
            
            autoUpdateSEO() {
                this.metaTitle = this.title;
            }
        }" 
        class="py-6 max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <form action="{{ route('articles.update', $article->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100">Edit Post</h2>
                <div class="flex gap-3">
                    <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800 py-2">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition shadow-sm">
                        Update Post
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                
                <div class="xl:col-span-2 space-y-6">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm">
                        
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Article Title *</label>
                            <input type="text" name="title" x-model="title" @input="autoUpdateSEO" required 
                                   class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-lg font-bold py-3 text-gray-800 dark:text-gray-100 focus:ring-blue-500/20" 
                                   placeholder="Enter the main title...">
                            @error('title') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Subtitle (Optional)</label>
                                <input type="text" name="subtitle" value="{{ old('subtitle', $article->subtitle) }}" 
                                       class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm font-medium py-2 text-gray-600 dark:text-gray-300 focus:ring-blue-500/20">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Slug (Optional)</label>
                                <input type="text" name="slug" value="{{ old('slug', $article->slug) }}" 
                                       class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm font-medium py-2 text-gray-600 dark:text-gray-300 focus:ring-blue-500/20">
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Article Content *</label>
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                <textarea name="description" id="editor">{!! old('description', $article->description) !!}</textarea>
                            </div>
                            @error('description') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-5 text-lg">SEO Settings</h3>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">SEO Title</label>
                            <input type="text" name="meta_title" x-model="metaTitle" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">SEO Description</label>
                            <textarea id="meta_description" name="meta_description" x-model="metaDesc" rows="4" data-manual="{{ $article->meta_description ? 'true' : 'false' }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500/20"></textarea>
                            <p class="text-[10px] text-gray-400 mt-1.5">Auto-generates from content if left empty.</p>
                        </div>
                    </div>

                </div>

                <div class="xl:col-span-1 space-y-6">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4 text-base">Post Settings</h3>
                        
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Visibility</label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="status" name="status" value="published" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Public</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="status" name="status" value="draft" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Draft</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="status" name="status" value="schedule" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Schedule</span>
                            </label>
                        </div>

                        <div x-show="status === 'schedule'" x-transition class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Publish Date & Time *</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" 
                                   class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/40 text-sm text-gray-700 dark:text-gray-300">
                            @error('published_at') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4 text-base">Categories *</h3>
                        
                        <div class="h-48 overflow-y-auto custom-scrollbar p-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50/50 dark:bg-gray-900/20">
                            @php 
                                // ডাটাবেস থেকে এই আর্টিকেলের আগে সিলেক্ট করা ক্যাটাগরিগুলো বের করা হলো
                                $articleCategoryIds = $article->categories->pluck('id')->toArray(); 
                            @endphp
                            
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2.5 mb-3 cursor-pointer group last:mb-0">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                                           {{ in_array($cat->id, old('categories', $articleCategoryIds)) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4 w-4">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                            @error('categories') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/70 p-6 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Header Image</label>
                        
                        <input type="hidden" name="featured_media_id" :value="selectedImageId">
                        
                        <div x-show="selectedImageId" style="display: none;" class="w-full aspect-w-16 aspect-h-9 rounded-xl overflow-hidden shadow-sm relative group border border-gray-200 dark:border-gray-700">
                            <img :src="selectedImagePath" class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition duration-200 flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                <button type="button" @click="showImageModal = true" class="bg-white text-gray-900 hover:bg-gray-200 text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition">
                                    Change
                                </button>
                                <button type="button" @click="selectedImageId = ''; selectedImagePath = ''" class="bg-rose-500 text-white hover:bg-rose-600 text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <div x-show="!selectedImageId" @click="showImageModal = true" class="w-full aspect-w-16 aspect-h-9 bg-gray-50 dark:bg-gray-900/40 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/50 transition flex flex-col items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Click to Select Image</p>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <div x-show="showImageModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="showImageModal = false" class="bg-white dark:bg-gray-800 w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col h-[85vh] border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">Select Media</h3>
                    <button type="button" @click="showImageModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-full p-1.5 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                    @if($media->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-500">No media available. Upload in Media Library first.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($media as $img)
                                <div @click="selectedImageId = '{{ $img->id }}'; selectedImagePath = '{{ asset($img->file_path) }}'; showImageModal = false;" 
                                     class="cursor-pointer border-2 border-transparent hover:border-blue-500 rounded-xl overflow-hidden aspect-w-1 aspect-h-1 bg-gray-100 transition relative group">
                                    <img src="{{ asset($img->file_path) }}" class="object-cover w-full h-full">
                                    <div x-show="selectedImageId == '{{ $img->id }}'" class="absolute inset-0 border-4 border-blue-500 rounded-xl pointer-events-none"></div>
                                    
                                    <div class="absolute inset-0 bg-blue-500/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Select</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            tinymce.init({
                selector: '#editor',
                plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template help',
                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview code fullscreen',
                menubar: false,
                height: 500,
                skin: isDarkMode ? 'oxide-dark' : 'oxide',
                content_css: isDarkMode ? 'dark' : 'default',
                setup: function(editor) {
                    editor.on('change keyup', function() {
                        editor.save();
                        
                        const plainText = editor.getContent({format: 'text'});
                        const metaInput = document.getElementById('meta_description');
                        
                        // Edit পেজে ইউজার যদি আগে থেকে কিছু লিখে থাকে (data-manual='true'), তবে অটোমেটিক আপডেট হবে না
                        if (metaInput.dataset.manual === 'false') {
                            metaInput.value = plainText.substring(0, 160).trim(); 
                            metaInput.dispatchEvent(new Event('input'));
                        }
                    });
                }
            });

            document.getElementById('meta_description').addEventListener('input', function() {
                this.dataset.manual = 'true';
            });
        });
    </script>

    <style>
        .tox-tinymce { border: none !important; border-radius: 0 0 0.75rem 0.75rem !important; }
        .dark .tox-tinymce { border-top-color: #374151 !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; }
    </style>
</x-app-layout>