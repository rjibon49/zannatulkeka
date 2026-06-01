<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">Manage Portfolio / Resume</h2>
    </x-slot>

    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-4 px-4">
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl relative" role="alert">
                <span class="block sm:inline font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- ==========================================
         SECTION 1: PERSONAL INFORMATION
    ========================================== -->
    <div x-data="{ 
            showImageModal: false, 
            selectedImageId: '{{ $portfolio->profile_picture_id ?? '' }}', 
            selectedImagePath: '{{ $portfolio->profilePicture ? asset($portfolio->profilePicture->file_path) : '' }}' 
        }" 
        class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <form action="{{ route('portfolio.update') }}" method="POST" class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            @csrf

            <input type="hidden" name="profile_picture_id" :value="selectedImageId">

            <div class="flex flex-col md:flex-row gap-8">
                <div class="md:w-1/3 flex flex-col items-center">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-4">Profile Photo</label>
                    <div @click="showImageModal = true" class="relative group w-40 h-40 rounded-full border-4 border-gray-100 dark:border-gray-700 overflow-hidden cursor-pointer bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                        <img x-show="selectedImageId" :src="selectedImagePath" class="w-full h-full object-cover" style="display: none;">
                        <svg x-show="!selectedImageId" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-white text-[10px] font-bold uppercase tracking-wide">Change Photo</span>
                        </div>
                    </div>
                    <button type="button" @click="selectedImageId = ''; selectedImagePath = ''" x-show="selectedImageId" style="display: none;" class="mt-3 text-[10px] font-bold text-rose-500 hover:underline uppercase tracking-wider">Remove Photo</button>
                </div>

                <div class="md:w-2/3 space-y-5">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">Personal Information</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $portfolio->name) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Designation / Profession</label>
                        <input type="text" name="designation" value="{{ old('designation', $portfolio->designation) }}" placeholder="e.g. Full Stack Developer" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $portfolio->phone) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $portfolio->email) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Address</label>
                        <input type="text" name="address" value="{{ old('address', $portfolio->address) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Short Bio / About Me</label>
                        <textarea name="bio" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">{{ old('bio', $portfolio->bio) }}</textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-md">Save Information</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Profile Picture Selection Modal -->
        <div x-show="showImageModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showImageModal = false" class="bg-gray-800 w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col h-[85vh] border border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="font-bold text-lg text-white">Select Profile Picture</h3>
                    <button type="button" @click="showImageModal = false" class="text-gray-400 hover:text-white font-bold">Close</button>
                </div>
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                        @forelse($media as $img)
                            <div @click="selectedImageId = '{{ $img->id }}'; selectedImagePath = '{{ asset($img->file_path) }}'; showImageModal = false;" 
                                 class="cursor-pointer border-4 rounded-xl overflow-hidden aspect-square transition relative"
                                 :class="selectedImageId == '{{ $img->id }}' ? 'border-blue-500 scale-95' : 'border-transparent'">
                                <img src="{{ asset($img->file_path) }}" class="object-cover w-full h-full">
                                <div x-show="selectedImageId == '{{ $img->id }}'" class="absolute inset-0 bg-blue-500/30 flex items-center justify-center" style="display: none;">
                                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-400 py-10">No images found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         SECTION 2: DYNAMIC PORTFOLIO ITEMS 
    ========================================== -->
    <div x-data="{ 
            showItemModal: false, 
            modalType: '', 
            modalTitleLabel: '',
            showUrlField: false,
            
            openItemModal(type, label, showUrl) {
                this.modalType = type;
                this.modalTitleLabel = label;
                this.showUrlField = showUrl;
                this.showItemModal = true;
            }
        }" 
        class="pb-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Professional Details</h2>

        @php
            $sections = [
                ['type' => 'work_identity', 'title' => 'কাজের পরিচিতি (Work Identity)', 'label' => 'Project/Work Name', 'url' => false],
                ['type' => 'experience', 'title' => 'দেশ-বিদেশে কাজের অভিজ্ঞতা (Experience)', 'label' => 'Designation', 'url' => false],
                ['type' => 'achievement', 'title' => 'কাজের সম্মাননা (Achievements)', 'label' => 'Award Name', 'url' => false],
                ['type' => 'book', 'title' => 'প্রকাশিত বই (Published Books)', 'label' => 'Book Title', 'url' => true],
                ['type' => 'publication', 'title' => 'প্রকাশিত কলাম / লিঙ্ক (Publications)', 'label' => 'Article Title', 'url' => true],
            ];
        @endphp

        <div class="space-y-6">
            @foreach($sections as $sec)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $sec['title'] }}</h3>
                        <button @click="openItemModal('{{ $sec['type'] }}', '{{ $sec['label'] }}', {{ $sec['url'] ? 'true' : 'false' }})" class="bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1 rounded-lg text-sm font-bold transition">
                            + Add New
                        </button>
                    </div>

                    <div class="space-y-3">
                        @if(isset($items[$sec['type']]) && $items[$sec['type']]->count() > 0)
                            @foreach($items[$sec['type']] as $item)
                                <div class="flex justify-between items-start bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-gray-200">{{ $item->title }}</h4>
                                        @if($item->subtitle) <p class="text-sm text-gray-500">{{ $item->subtitle }} @if($item->period) | {{ $item->period }} @endif</p> @endif
                                        @if($item->url) <a href="{{ $item->url }}" target="_blank" class="text-xs text-blue-500 hover:underline mt-1 block">{{ $item->url }}</a> @endif
                                        @if($item->description) <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $item->description }}</p> @endif
                                    </div>
                                    <form action="{{ route('portfolio-items.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')" class="text-rose-500 hover:bg-rose-100 p-2 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-400 text-center py-4">No items added yet.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Dynamic Add Item Modal -->
        <div x-show="showItemModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div @click.away="showItemModal = false" class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl flex flex-col border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900 rounded-t-2xl">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">Add New Item</h3>
                    <button type="button" @click="showItemModal = false" class="text-gray-400 hover:text-rose-500 font-bold">Close</button>
                </div>
                
                <form action="{{ route('portfolio-items.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="type" :value="modalType">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2" x-text="modalTitleLabel"></label>
                        <input type="text" name="title" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Subtitle / Company / Publisher</label>
                        <input type="text" name="subtitle" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Period / Year (Optional)</label>
                        <input type="text" name="period" placeholder="e.g. 2021 - Present" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>

                    <div x-show="showUrlField">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Link / URL</label>
                        <input type="url" name="url" placeholder="https://..." class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description / Details</label>
                        <textarea name="description" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition mt-4">Save Item</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>