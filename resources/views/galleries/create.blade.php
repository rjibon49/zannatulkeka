{{-- resources/views/galleries/create.blade.php --}}
@php
    $imageMedia = ($media ?? collect())->where('type', 'image');

    $oldSelectedImages = $imageMedia
        ->whereIn('id', collect(old('media_library_ids', []))->map(fn ($id) => (int) $id)->toArray())
        ->map(fn ($img) => [
            'id' => (string) $img->id,
            'url' => $img->url,
            'name' => $img->file_name,
            'search' => strtolower(($img->file_name ?? '') . ' ' . ($img->original_name ?? '') . ' ' . ($img->alt_text ?? '')),
        ])
        ->values();

    $oldCover = $imageMedia->firstWhere('id', (int) old('cover_media_id'));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Create Gallery
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Create a gallery album and select multiple images from media library.
                </p>
            </div>

            <a
                href="{{ route('galleries.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Gallery
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            showImageModal: false,
            imageSearch: '',
            selectedImages: @js($oldSelectedImages),
            coverMediaId: @js(old('cover_media_id', $oldCover?->id ? (string) $oldCover->id : '')),
            coverUrl: @js($oldCover?->url ?? ''),
            coverName: @js($oldCover?->file_name ?? ''),

            toggleImage(image) {
                const index = this.selectedImages.findIndex(item => item.id == image.id);

                if (index > -1) {
                    this.selectedImages.splice(index, 1);

                    if (this.coverMediaId == image.id) {
                        if (this.selectedImages.length > 0) {
                            this.setCover(this.selectedImages[0]);
                        } else {
                            this.coverMediaId = '';
                            this.coverUrl = '';
                            this.coverName = '';
                        }
                    }
                } else {
                    this.selectedImages.push(image);

                    if (!this.coverMediaId) {
                        this.setCover(image);
                    }
                }
            },

            isSelected(id) {
                return this.selectedImages.some(item => item.id == id);
            },

            setCover(image) {
                this.coverMediaId = image.id;
                this.coverUrl = image.url;
                this.coverName = image.name;
            },

            removeImage(id) {
                const image = this.selectedImages.find(item => item.id == id);
                if (image) {
                    this.toggleImage(image);
                }
            }
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <form action="{{ route('galleries.store') }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_420px]">
            @csrf

            <template x-for="image in selectedImages" :key="image.id">
                <input type="hidden" name="media_library_ids[]" :value="image.id">
            </template>

            <input type="hidden" name="cover_media_id" :value="coverMediaId">

            <section class="space-y-5">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                            <i class="fa-solid fa-images"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Gallery Information
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Add title, description, SEO and sorting information.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Gallery Title <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                required
                                placeholder="Example: Events, Awards, Personal Gallery"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('title')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                value="{{ old('slug') }}"
                                placeholder="Leave empty to auto-generate"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('slug')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                placeholder="Short gallery description..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                min="0"
                                value="{{ old('sort_order', 0) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('sort_order')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
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
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>

                            @error('status')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                SEO Meta Title
                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title') }}"
                                placeholder="Optional SEO title"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('meta_title')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                SEO Meta Description
                            </label>

                            <textarea
                                name="meta_description"
                                rows="3"
                                placeholder="Optional SEO description..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{{ old('meta_description') }}</textarea>

                            @error('meta_description')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-5 flex flex-col gap-3 border-b border-[#784828]/10 pb-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Selected Images
                            </h3>
                            <p class="mt-1 text-sm font-medium text-[#756b62]">
                                Select multiple images from your media library.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="showImageModal = true"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Select Images
                        </button>
                    </div>

                    <template x-if="selectedImages.length === 0">
                        <button
                            type="button"
                            @click="showImageModal = true"
                            class="flex min-h-56 w-full cursor-pointer flex-col items-center justify-center rounded-[2rem] border-2 border-dashed border-[#8b4a2f]/25 bg-[#fbf7f1] px-5 py-8 text-center transition hover:border-[#8b4a2f]/60 hover:bg-[#fff7ed]"
                        >
                            <span class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                <i class="fa-solid fa-images text-xl"></i>
                            </span>

                            <span class="mt-4 block text-sm font-black text-[#1f1712]">
                                Click to select gallery images
                            </span>

                            <span class="mt-2 block text-xs font-semibold text-[#756b62]">
                                You can select multiple images.
                            </span>
                        </button>
                    </template>

                    <template x-if="selectedImages.length > 0">
                        <div>
                            <div class="mb-4 rounded-2xl bg-[#fff3df] px-4 py-3 text-sm font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                <i class="fa-solid fa-check-circle mr-2"></i>
                                <span x-text="selectedImages.length"></span> image(s) selected
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                                <template x-for="image in selectedImages" :key="image.id">
                                    <div class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-1 ring-[#784828]/10">
                                        <img :src="image.url" :alt="image.name" class="h-full w-full object-cover">

                                        <div
                                            x-show="coverMediaId == image.id"
                                            class="absolute left-2 top-2 rounded-full bg-[#8b4a2f] px-2 py-1 text-[10px] font-black uppercase text-white"
                                        >
                                            Cover
                                        </div>

                                        <div class="absolute inset-x-2 bottom-2 flex gap-1.5">
                                            <button
                                                type="button"
                                                @click="setCover(image)"
                                                class="flex-1 rounded-xl bg-white/90 px-2 py-1.5 text-[10px] font-black text-[#8b4a2f] shadow"
                                            >
                                                Cover
                                            </button>

                                            <button
                                                type="button"
                                                @click="removeImage(image.id)"
                                                class="rounded-xl bg-red-50 px-2 py-1.5 text-[10px] font-black text-red-700 shadow"
                                            >
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    @error('media_library_ids')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Cover Preview
                    </h3>

                    <div class="mt-5 aspect-video overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10">
                        <template x-if="coverUrl">
                            <img :src="coverUrl" :alt="coverName" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!coverUrl">
                            <div class="flex h-full w-full flex-col items-center justify-center text-[#8b4a2f]">
                                <i class="fa-solid fa-image text-3xl"></i>
                                <span class="mt-2 text-xs font-black uppercase tracking-wide">
                                    No Cover Selected
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Save Gallery
                    </h3>

                    <p class="mt-1 text-sm font-medium leading-6 text-[#756b62]">
                        Save this album after selecting images and cover image.
                    </p>

                    <button
                        type="submit"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Gallery
                    </button>
                </div>
            </aside>
        </form>

        <div
            x-show="showImageModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showImageModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex flex-col gap-4 border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Images
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose multiple images from media library.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[#9a8c80]"></i>
                            <input
                                type="text"
                                x-model="imageSearch"
                                placeholder="Search images..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-white py-2.5 pl-10 pr-4 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20 sm:w-72"
                            >
                        </div>

                        <button
                            type="button"
                            @click="showImageModal = false"
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                        @forelse($imageMedia as $img)
                            <button
                                type="button"
                                x-show="@js(strtolower(($img->file_name ?? '') . ' ' . ($img->original_name ?? '') . ' ' . ($img->alt_text ?? ''))) .includes(imageSearch.toLowerCase())"
                                @click="toggleImage({
                                    id: '{{ $img->id }}',
                                    url: '{{ $img->url }}',
                                    name: @js($img->file_name),
                                    search: @js(strtolower(($img->file_name ?? '') . ' ' . ($img->original_name ?? '') . ' ' . ($img->alt_text ?? '')))
                                })"
                                class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-2 ring-transparent transition hover:-translate-y-1 hover:ring-[#8b4a2f]"
                                :class="isSelected('{{ $img->id }}') ? 'ring-[#8b4a2f]' : 'ring-transparent'"
                            >
                                <img
                                    src="{{ $img->url }}"
                                    alt="{{ $img->alt_text ?: $img->file_name }}"
                                    class="h-full w-full object-cover"
                                >

                                <div
                                    x-show="isSelected('{{ $img->id }}')"
                                    class="absolute inset-0 flex items-center justify-center bg-[#8b4a2f]/35"
                                >
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#8b4a2f] shadow-lg">
                                        <i class="fa-solid fa-check text-xl"></i>
                                    </span>
                                </div>
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

                <div class="flex items-center justify-between border-t border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                    <div class="text-sm font-black text-[#756b62]">
                        Selected: <span x-text="selectedImages.length"></span>
                    </div>

                    <button
                        type="button"
                        @click="showImageModal = false"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-check"></i>
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>