{{-- resources/views/articles/create.blade.php --}}
@php
    use Illuminate\Support\Str;

    $oldCategories = collect(old('categories', []))->map(fn ($id) => (string) $id)->toArray();
    $oldTags = collect(old('tags', []))->map(fn ($id) => (string) $id)->toArray();

    $imageMedia = ($media ?? collect())->where('type', 'image');

    $selectedFeatured = $imageMedia->firstWhere('id', (int) old('featured_media_id'));
    $selectedOg = $imageMedia->firstWhere('id', (int) old('og_media_id'));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Create Article
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Write a new SEO-ready article with categories, tags, images and video support.
                </p>
            </div>

            <a
                href="{{ route('articles.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Articles
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            title: @js(old('title', '')),
            slug: @js(old('slug', '')),
            metaTitle: @js(old('meta_title', '')),
            metaDescription: @js(old('meta_description', '')),
            status: @js(old('status', 'draft')),

            categorySearch: '',
            tagSearch: '',

            showFeaturedModal: false,
            selectedFeaturedId: @js(old('featured_media_id')),
            selectedFeaturedUrl: @js($selectedFeatured?->url),
            selectedFeaturedName: @js($selectedFeatured?->file_name),

            showOgModal: false,
            selectedOgId: @js(old('og_media_id')),
            selectedOgUrl: @js($selectedOg?->url),
            selectedOgName: @js($selectedOg?->file_name),

            makeSlug(value) {
                return value
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            },

            updateSeoFromTitle() {
                if (!this.metaTitle) {
                    this.metaTitle = this.title;
                }

                if (!this.slug) {
                    this.slug = this.makeSlug(this.title);
                }
            }
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <form action="{{ route('articles.store') }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_420px]">
            @csrf

            {{-- Main Content --}}
            <section class="space-y-5">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                            <i class="fa-solid fa-pen-nib"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Article Content
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Add title, subtitle, excerpt and main body.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="title" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Title <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                x-model="title"
                                @input="updateSeoFromTitle"
                                value="{{ old('title') }}"
                                required
                                placeholder="Enter article title..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-base font-black text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('title')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subtitle" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Subtitle
                            </label>

                            <input
                                type="text"
                                name="subtitle"
                                id="subtitle"
                                value="{{ old('subtitle') }}"
                                placeholder="Optional subtitle..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('subtitle')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="excerpt" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Excerpt / Short Summary
                            </label>

                            <textarea
                                name="excerpt"
                                id="excerpt"
                                rows="4"
                                placeholder="Short summary for article card and SEO fallback..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{{ old('excerpt') }}</textarea>

                            @error('excerpt')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="editor" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Main Content <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                name="description"
                                id="editor"
                                rows="16"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{!! old('description') !!}</textarea>

                            @error('description')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
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
                                Configure search engine and social sharing metadata.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="slug" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                id="slug"
                                x-model="slug"
                                placeholder="auto-generated-from-title"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            <p class="mt-2 text-xs font-semibold text-[#9a8c80]">
                                Leave empty to generate automatically.
                            </p>

                            @error('slug')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="canonical_url" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Canonical URL
                            </label>

                            <input
                                type="url"
                                name="canonical_url"
                                id="canonical_url"
                                value="{{ old('canonical_url') }}"
                                placeholder="https://example.com/article-url"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('canonical_url')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_title" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Meta Title
                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                id="meta_title"
                                x-model="metaTitle"
                                placeholder="SEO title..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('meta_title')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="video_url" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                YouTube Video URL
                            </label>

                            <input
                                type="url"
                                name="video_url"
                                id="video_url"
                                value="{{ old('video_url') }}"
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('video_url')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="meta_description" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Meta Description
                            </label>

                            <textarea
                                name="meta_description"
                                id="meta_description"
                                rows="4"
                                x-model="metaDescription"
                                placeholder="SEO description within 150-160 characters..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >{{ old('meta_description') }}</textarea>

                            @error('meta_description')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Right Sidebar --}}
            <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                {{-- Publish --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-5 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                            <i class="fa-solid fa-paper-plane"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Publish
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Set article status and schedule.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="status" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Status <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="status"
                                id="status"
                                x-model="status"
                                required
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>

                            @error('status')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="published_at" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Publish Date / Time
                            </label>

                            <input
                                type="datetime-local"
                                name="published_at"
                                id="published_at"
                                value="{{ old('published_at') }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            <p class="mt-2 text-xs font-semibold text-[#9a8c80]">
                                Optional. If published and empty, current time will be used.
                            </p>

                            @error('published_at')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                @checked(old('is_featured'))
                                class="rounded border-[#784828]/20 text-[#8b4a2f] focus:ring-[#8b4a2f]/30"
                            >
                            <span>
                                <span class="block text-sm font-black text-[#1f1712]">
                                    Featured Article
                                </span>
                                <span class="mt-1 block text-xs font-semibold text-[#756b62]">
                                    Highlight this article on frontend later.
                                </span>
                            </span>
                        </label>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Article
                        </button>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Categories
                            </h3>
                            <p class="mt-1 text-xs font-semibold text-[#756b62]">
                                Select one or more categories.
                            </p>
                        </div>

                        <span class="rounded-full bg-[#fff3df] px-3 py-1 text-[11px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                            {{ $categories->count() }} items
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[#9a8c80]"></i>

                            <input
                                type="text"
                                x-model="categorySearch"
                                placeholder="Search categories..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-2.5 pl-10 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>
                    </div>

                    <div class="max-h-[280px] space-y-1 overflow-y-auto overscroll-contain rounded-2xl bg-[#fbf7f1] p-3 pr-2 ring-1 ring-[#784828]/10">
                        @forelse($categories as $cat)
                            <label
                                x-show="@js(Str::lower($cat->name)) .includes(categorySearch.toLowerCase())"
                                class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-white"
                            >
                                <input
                                    type="checkbox"
                                    name="categories[]"
                                    value="{{ $cat->id }}"
                                    @checked(in_array((string) $cat->id, $oldCategories, true))
                                    class="rounded border-[#784828]/20 text-[#8b4a2f] focus:ring-[#8b4a2f]/30"
                                >

                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-bold text-[#1f1712]">
                                        {{ $cat->name }}
                                    </span>

                                    @if($cat->parent)
                                        <span class="block truncate text-[11px] font-semibold text-[#756b62]">
                                            Parent: {{ $cat->parent->name }}
                                        </span>
                                    @endif
                                </span>
                            </label>
                        @empty
                            <p class="py-6 text-center text-sm font-bold text-[#756b62]">
                                No categories found.
                            </p>
                        @endforelse
                    </div>

                    <p class="mt-2 text-xs font-semibold text-[#9a8c80]">
                        Scroll inside the box if the category list is long.
                    </p>

                    @error('categories')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Tags
                            </h3>
                            <p class="mt-1 text-xs font-semibold text-[#756b62]">
                                Select article tags for SEO and filtering.
                            </p>
                        </div>

                        <span class="rounded-full bg-[#fff3df] px-3 py-1 text-[11px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                            {{ $tags->count() }} items
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[#9a8c80]"></i>

                            <input
                                type="text"
                                x-model="tagSearch"
                                placeholder="Search tags..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-2.5 pl-10 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>
                    </div>

                    <div class="max-h-[280px] space-y-1 overflow-y-auto overscroll-contain rounded-2xl bg-[#fbf7f1] p-3 pr-2 ring-1 ring-[#784828]/10">
                        @forelse($tags as $tag)
                            <label
                                x-show="@js(Str::lower($tag->name)) .includes(tagSearch.toLowerCase())"
                                class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-white"
                            >
                                <input
                                    type="checkbox"
                                    name="tags[]"
                                    value="{{ $tag->id }}"
                                    @checked(in_array((string) $tag->id, $oldTags, true))
                                    class="rounded border-[#784828]/20 text-[#8b4a2f] focus:ring-[#8b4a2f]/30"
                                >

                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-bold text-[#1f1712]">
                                        {{ $tag->name }}
                                    </span>

                                    <!-- <span class="block truncate text-[11px] font-semibold text-[#756b62]">
                                        /{{ $tag->slug }}
                                    </span> -->
                                </span>
                            </label>
                        @empty
                            <p class="py-6 text-center text-sm font-bold text-[#756b62]">
                                No tags found.
                            </p>
                        @endforelse
                    </div>

                    <p class="mt-2 text-xs font-semibold text-[#9a8c80]">
                        Scroll inside the box if the tag list is long.
                    </p>

                    @error('tags')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Featured Image --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="mb-4 text-lg font-black tracking-tight text-[#1f1712]">
                        Featured Image
                    </h3>

                    <input type="hidden" name="featured_media_id" :value="selectedFeaturedId">

                    <button
                        type="button"
                        @click="showFeaturedModal = true"
                        class="relative flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="selectedFeaturedUrl">
                            <img :src="selectedFeaturedUrl" :alt="selectedFeaturedName || 'Featured image'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!selectedFeaturedUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-image text-3xl"></i>
                                <span class="text-xs font-black uppercase tracking-wide">
                                    Select Featured Image
                                </span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button
                            type="button"
                            @click="showFeaturedModal = true"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button
                            type="button"
                            x-show="selectedFeaturedId"
                            @click="selectedFeaturedId = ''; selectedFeaturedUrl = ''; selectedFeaturedName = ''"
                            class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                        >
                            Remove
                        </button>
                    </div>

                    @error('featured_media_id')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- OG Image --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="mb-4 text-lg font-black tracking-tight text-[#1f1712]">
                        Social Share Image
                    </h3>

                    <input type="hidden" name="og_media_id" :value="selectedOgId">

                    <button
                        type="button"
                        @click="showOgModal = true"
                        class="relative flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="selectedOgUrl">
                            <img :src="selectedOgUrl" :alt="selectedOgName || 'OG image'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!selectedOgUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-share-nodes text-3xl"></i>
                                <span class="text-xs font-black uppercase tracking-wide">
                                    Select OG Image
                                </span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button
                            type="button"
                            @click="showOgModal = true"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button
                            type="button"
                            x-show="selectedOgId"
                            @click="selectedOgId = ''; selectedOgUrl = ''; selectedOgName = ''"
                            class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                        >
                            Remove
                        </button>
                    </div>

                    @error('og_media_id')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </aside>
        </form>

        {{-- Featured Image Modal --}}
        <div
            x-show="showFeaturedModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showFeaturedModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Featured Image
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose an image from media library.
                        </p>
                    </div>

                    <button type="button" @click="showFeaturedModal = false" class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                        @forelse($imageMedia as $img)
                            <button
                                type="button"
                                @click="
                                    selectedFeaturedId = '{{ $img->id }}';
                                    selectedFeaturedUrl = '{{ $img->url }}';
                                    selectedFeaturedName = '{{ $img->file_name }}';
                                    showFeaturedModal = false;
                                "
                                class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-2 ring-transparent transition hover:-translate-y-1 hover:ring-[#8b4a2f]"
                            >
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?: $img->file_name }}" class="h-full w-full object-cover">

                                <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/35">
                                    <span class="scale-90 rounded-full bg-white px-3 py-1 text-xs font-black text-[#8b4a2f] opacity-0 transition group-hover:scale-100 group-hover:opacity-100">
                                        Select
                                    </span>
                                </div>
                            </button>
                        @empty
                            <div class="col-span-full py-14 text-center">
                                <p class="text-sm font-bold text-[#756b62]">
                                    No image found. Upload image from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- OG Image Modal --}}
        <div
            x-show="showOgModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showOgModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Social Share Image
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose an Open Graph image.
                        </p>
                    </div>

                    <button type="button" @click="showOgModal = false" class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                        @forelse($imageMedia as $img)
                            <button
                                type="button"
                                @click="
                                    selectedOgId = '{{ $img->id }}';
                                    selectedOgUrl = '{{ $img->url }}';
                                    selectedOgName = '{{ $img->file_name }}';
                                    showOgModal = false;
                                "
                                class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-2 ring-transparent transition hover:-translate-y-1 hover:ring-[#8b4a2f]"
                            >
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?: $img->file_name }}" class="h-full w-full object-cover">

                                <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/35">
                                    <span class="scale-90 rounded-full bg-white px-3 py-1 text-xs font-black text-[#8b4a2f] opacity-0 transition group-hover:scale-100 group-hover:opacity-100">
                                        Select
                                    </span>
                                </div>
                            </button>
                        @empty
                            <div class="col-span-full py-14 text-center">
                                <p class="text-sm font-bold text-[#756b62]">
                                    No image found. Upload image from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof tinymce === 'undefined') {
                    return;
                }

                tinymce.init({
                    selector: '#editor',
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | preview code fullscreen',
                    menubar: false,
                    height: 520,
                    branding: false,
                    promotion: false,
                    content_style: 'body { font-family: Arial, sans-serif; font-size: 16px; line-height: 1.7; }',
                    setup: function (editor) {
                        editor.on('change keyup', function () {
                            editor.save();

                            const metaDescription = document.getElementById('meta_description');

                            if (!metaDescription || metaDescription.dataset.manual === 'true') {
                                return;
                            }

                            const plainText = editor.getContent({ format: 'text' }).trim();

                            if (plainText.length > 0) {
                                metaDescription.value = plainText.substring(0, 160);
                                metaDescription.dispatchEvent(new Event('input'));
                            }
                        });
                    }
                });

                const metaDescription = document.getElementById('meta_description');

                if (metaDescription) {
                    metaDescription.addEventListener('input', function () {
                        this.dataset.manual = 'true';
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>