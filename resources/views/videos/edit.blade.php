{{-- resources/views/videos/edit.blade.php --}}
@php
    $imageMedia = ($media ?? collect())->where('type', 'image');
    $selectedThumb = $imageMedia->firstWhere('id', (int) old('thumbnail_media_id', $video->thumbnail_media_id)) ?: $video->thumbnail;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Edit Video
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Update YouTube video information, thumbnail and SEO data.
                </p>
            </div>

            <a
                href="{{ route('videos.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Videos
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            showThumbModal: false,
            youtubeUrl: @js(old('video_url', $video->video_url)),
            selectedThumbId: @js(old('thumbnail_media_id', $video->thumbnail_media_id)),
            selectedThumbUrl: @js($selectedThumb?->url),
            selectedThumbName: @js($selectedThumb?->file_name),

            youtubeId() {
                let url = this.youtubeUrl || '';

                let match = url.match(/[?&]v=([^&]+)/);
                if (match) return match[1];

                match = url.match(/youtu\.be\/([^?&]+)/);
                if (match) return match[1];

                match = url.match(/youtube\.com\/embed\/([^?&]+)/);
                if (match) return match[1];

                return @js($video->youtube_video_id ?? '');
            },

            chooseThumb(image) {
                this.selectedThumbId = image.id;
                this.selectedThumbUrl = image.url;
                this.selectedThumbName = image.name;
                this.showThumbModal = false;
            }
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <form action="{{ route('videos.update', $video) }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_420px]">
            @csrf
            @method('PUT')

            <input type="hidden" name="thumbnail_media_id" :value="selectedThumbId">

            <section class="space-y-5">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-700">
                            <i class="fa-brands fa-youtube"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Video Information
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Update main YouTube video information.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Video Title <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $video->title) }}"
                                required
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                YouTube URL <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="url"
                                name="video_url"
                                x-model="youtubeUrl"
                                required
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('video_url')
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
                                value="{{ old('slug', $video->slug) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                min="0"
                                value="{{ old('sort_order', $video->sort_order ?? 0) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="6"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{{ old('description', $video->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <i class="fa-solid fa-magnifying-glass-chart"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                SEO Settings
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Optional SEO title and description.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Meta Title
                            </label>
                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title', $video->meta_title) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Meta Description
                            </label>
                            <textarea
                                name="meta_description"
                                rows="4"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{{ old('meta_description', $video->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Video Preview
                    </h3>

                    <div class="mt-5 aspect-video overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10">
                        <template x-if="youtubeId()">
                            <iframe
                                class="h-full w-full"
                                :src="'https://www.youtube.com/embed/' + youtubeId()"
                                allowfullscreen
                            ></iframe>
                        </template>

                        <template x-if="!youtubeId()">
                            <div class="flex h-full w-full flex-col items-center justify-center text-[#8b4a2f]">
                                <i class="fa-brands fa-youtube text-4xl"></i>
                                <span class="mt-3 text-xs font-black uppercase tracking-wide">
                                    Enter YouTube URL
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Custom Thumbnail
                    </h3>

                    <button
                        type="button"
                        @click="showThumbModal = true"
                        class="mt-5 flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="selectedThumbUrl">
                            <img :src="selectedThumbUrl" :alt="selectedThumbName || 'Thumbnail'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!selectedThumbUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-image text-3xl"></i>
                                <span class="text-xs font-black uppercase">Select Thumbnail</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="showThumbModal = true" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button type="button" x-show="selectedThumbId" @click="selectedThumbId = ''; selectedThumbUrl = ''; selectedThumbName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Publish
                    </h3>

                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            <option value="active" @selected(old('status', $video->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $video->status) === 'inactive')>Inactive</option>
                            <option value="draft" @selected(old('status', $video->status) === 'draft')>Draft</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Update Video
                    </button>
                </div>
            </aside>
        </form>

        {{-- Thumbnail Modal --}}
        <div
            x-show="showThumbModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showThumbModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Thumbnail
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose thumbnail image from media library.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="showThumbModal = false"
                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                        @forelse($imageMedia as $img)
                            <button
                                type="button"
                                @click="chooseThumb({
                                    id: '{{ $img->id }}',
                                    url: '{{ $img->url }}',
                                    name: @js($img->file_name)
                                })"
                                class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-2 ring-transparent transition hover:-translate-y-1 hover:ring-[#8b4a2f]"
                            >
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?: $img->file_name }}" class="h-full w-full object-cover">
                            </button>
                        @empty
                            <div class="col-span-full py-14 text-center">
                                <p class="text-sm font-bold text-[#756b62]">
                                    No image found. Upload images from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>