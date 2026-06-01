<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">Add Gallery Item</h2>
    </x-slot>

    <div x-data="{ 
            showImageModal: false, 
            selectedImages: [] 
        }" 
        class="py-6 max-w-2xl mx-auto px-4">

        <form action="{{ route('galleries.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            @csrf
            
            {{-- মাল্টিপল আইডি সাবমিট করার জন্য হিডেন ফিল্ড --}}
            <template x-for="image in selectedImages" :key="image.id">
                <input type="hidden" name="media_library_ids[]" :value="image.id">
            </template>
            
            <input type="hidden" name="status" value="active">

            <div @click="showImageModal = true" class="cursor-pointer min-h-[160px] border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-wrap gap-2 p-2 rounded-xl mb-4 bg-gray-50 dark:bg-gray-900/50 hover:border-blue-500 transition">
                <template x-for="image in selectedImages" :key="image.id">
                    <img :src="image.path" class="h-20 w-20 object-cover rounded-lg">
                </template>
                <div x-show="selectedImages.length === 0" class="w-full flex items-center justify-center text-gray-400">Click to select multiple images</div>
            </div>

            <input type="text" name="title" placeholder="Image Title" class="w-full mb-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
            
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">Save All to Gallery</button>
        </form>

        {{-- Media Selection Modal --}}
        <div x-show="showImageModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showImageModal = false" class="bg-gray-800 w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col h-[85vh] border border-gray-700 overflow-hidden">
                
                <div class="p-5 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="font-bold text-lg text-white">Select Images</h3>
                    <button type="button" @click="showImageModal = false" class="text-gray-400 hover:text-white font-bold">Close</button>
                </div>
                
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach(\App\Models\MediaLibrary::latest()->get() as $img)
                            <div @click="
                                    let imgId = '{{ $img->id }}';
                                    let imgPath = '{{ asset($img->file_path) }}';
                                    let index = selectedImages.findIndex(i => i.id == imgId);
                                    if(index > -1) {
                                        selectedImages.splice(index, 1);
                                    } else {
                                        selectedImages.push({id: imgId, path: imgPath});
                                    }
                                " 
                                class="cursor-pointer border-4 rounded-xl overflow-hidden aspect-square transition relative"
                                :class="selectedImages.find(i => i.id == '{{ $img->id }}') ? 'border-blue-500' : 'border-transparent'">
                                
                                <img src="{{ asset($img->file_path) }}" class="object-cover w-full h-full">
                                
                                <div x-show="selectedImages.find(i => i.id == '{{ $img->id }}')" class="absolute inset-0 bg-blue-500/30 flex items-center justify-center">
                                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-5 border-t border-gray-700 bg-gray-900 text-right">
                    <button type="button" @click="showImageModal = false" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl transition">
                        Add Selected (<span x-text="selectedImages.length">0</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>