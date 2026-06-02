{{-- resources/views/media/index.blade.php --}}
@php
    $typeLabels = [
        'image' => 'Image',
        'document' => 'Document',
        'video' => 'Video',
        'audio' => 'Audio',
        'other' => 'Other',
    ];

    $typeIcons = [
        'image' => 'fa-solid fa-image',
        'document' => 'fa-solid fa-file-lines',
        'video' => 'fa-solid fa-file-video',
        'audio' => 'fa-solid fa-file-audio',
        'other' => 'fa-solid fa-file',
    ];

    $typeColors = [
        'image' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'document' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'video' => 'bg-red-50 text-red-700 ring-red-100',
        'audio' => 'bg-purple-50 text-purple-700 ring-purple-100',
        'other' => 'bg-slate-50 text-slate-700 ring-slate-100',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Media Library
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Upload, organize and reuse images, documents, videos and audio files.
                </p>
            </div>

            <a
                href="#upload-media"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
            >
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Upload Files
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            editModalOpen: false,
            editAction: '',
            editMedia: {
                file_name: '',
                alt_text: '',
                caption: '',
                description: '',
            },
            uploadFileNames: [],
            updateFileNames(event) {
                this.uploadFileNames = Array.from(event.target.files || []).map(file => file.name);
            }
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[420px_minmax(0,1fr)]">
            {{-- Upload Form --}}
            <section id="upload-media" class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5 xl:sticky xl:top-24 xl:self-start">
                <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </span>

                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Upload Media
                        </h3>
                        <p class="text-sm font-medium text-[#756b62]">
                            Add files to the media library.
                        </p>
                    </div>
                </div>

                <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Select Files <span class="text-red-500">*</span>
                        </label>

                        <label class="flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-[2rem] border-2 border-dashed border-[#8b4a2f]/25 bg-[#fbf7f1] px-5 py-8 text-center transition hover:border-[#8b4a2f]/60 hover:bg-[#fff7ed]">
                            <input
                                type="file"
                                name="files[]"
                                multiple
                                accept=".jpeg,.jpg,.png,.webp,.pdf,.doc,.docx,.mp4,.mp3,.wav"
                                class="hidden"
                                @change="updateFileNames($event)"
                            >

                            <span class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                <i class="fa-solid fa-file-arrow-up text-xl"></i>
                            </span>

                            <span class="mt-4 block text-sm font-black text-[#1f1712]">
                                Click to choose files
                            </span>

                            <span class="mt-2 block text-xs font-semibold leading-5 text-[#756b62]">
                                Allowed: JPG, PNG, WEBP, PDF, DOC, DOCX, MP4, MP3, WAV. Max 10MB each.
                            </span>
                        </label>

                        <template x-if="uploadFileNames.length">
                            <div class="mt-3 rounded-2xl bg-[#fff7ed] p-3">
                                <p class="mb-2 text-xs font-black uppercase tracking-wide text-[#8b4a2f]">
                                    Selected Files
                                </p>

                                <template x-for="fileName in uploadFileNames" :key="fileName">
                                    <div class="flex items-center gap-2 py-1 text-xs font-bold text-[#756b62]">
                                        <i class="fa-solid fa-paperclip text-[#8b4a2f]"></i>
                                        <span x-text="fileName" class="truncate"></span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        @error('files')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror

                        @error('files.*')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alt_text" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Default Alt Text
                        </label>

                        <input
                            type="text"
                            name="alt_text"
                            id="alt_text"
                            value="{{ old('alt_text') }}"
                            placeholder="Optional image alt text"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label for="caption" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Default Caption
                        </label>

                        <input
                            type="text"
                            name="caption"
                            id="caption"
                            value="{{ old('caption') }}"
                            placeholder="Optional caption"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            placeholder="Optional file description..."
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >{{ old('description') }}</textarea>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Upload to Library
                    </button>
                </form>
            </section>

            {{-- Media List --}}
            <section class="space-y-5">
                {{-- Filter --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
                    <form action="{{ route('media.index') }}" method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1fr)_220px_auto_auto]">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Search by file name, original name or alt text..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-3 pl-11 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <select
                            name="type"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            <option value="">All Types</option>
                            @foreach($typeLabels as $typeKey => $typeName)
                                <option value="{{ $typeKey }}" @selected(($type ?? '') === $typeKey)>
                                    {{ $typeName }}
                                </option>
                            @endforeach
                        </select>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1f1712] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-black"
                        >
                            <i class="fa-solid fa-filter"></i>
                            Filter
                        </button>

                        @if(!empty($search) || !empty($type))
                            <a
                                href="{{ route('media.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                            >
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Grid --}}
                <div class="overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                    <div class="flex flex-col gap-3 border-b border-[#784828]/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Library Files
                            </h3>
                            <p class="mt-1 text-sm font-medium text-[#756b62]">
                                Total {{ $media->total() }} file(s) found.
                            </p>
                        </div>
                    </div>

                    @if($media->count())
                        <div class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                            @foreach($media as $item)
                                @php
                                    $itemType = $item->type ?: 'other';
                                    $icon = $typeIcons[$itemType] ?? $typeIcons['other'];
                                    $badgeClass = $typeColors[$itemType] ?? $typeColors['other'];
                                @endphp

                                <article class="group overflow-hidden rounded-[1.4rem] border border-[#784828]/10 bg-[#fbf7f1] shadow-sm transition hover:-translate-y-1 hover:bg-white hover:shadow-lg">
                                    <div class="relative aspect-square overflow-hidden bg-[#fff3df]">
                                        @if($itemType === 'image' && $item->url)
                                            <img
                                                src="{{ $item->url }}"
                                                alt="{{ $item->alt_text ?: $item->file_name }}"
                                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            >
                                        @else
                                            <div class="flex h-full w-full flex-col items-center justify-center text-[#8b4a2f]">
                                                <i class="{{ $icon }} text-3xl"></i>
                                                <span class="mt-2 text-[10px] font-black uppercase tracking-wide">
                                                    {{ $typeLabels[$itemType] ?? 'File' }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="absolute left-2 top-2">
                                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[10px] font-black uppercase ring-1 {{ $badgeClass }}">
                                                <i class="{{ $icon }}"></i>
                                                {{ $typeLabels[$itemType] ?? 'File' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-3">
                                        <h4 class="truncate text-xs font-black text-[#1f1712]" title="{{ $item->file_name }}">
                                            {{ $item->file_name }}
                                        </h4>

                                        <p class="mt-1 truncate text-[11px] font-semibold text-[#756b62]" title="{{ $item->original_name }}">
                                            {{ $item->original_name ?: 'No original name' }}
                                        </p>

                                        <div class="mt-2 flex flex-wrap gap-1.5 text-[10px] font-black text-[#756b62]">
                                            <span class="rounded-full bg-white px-2 py-1 ring-1 ring-[#784828]/10">
                                                {{ strtoupper($item->extension ?? 'N/A') }}
                                            </span>

                                            <span class="rounded-full bg-white px-2 py-1 ring-1 ring-[#784828]/10">
                                                {{ $item->readable_size ?? '0 KB' }}
                                            </span>
                                        </div>

                                        @if($item->alt_text || $item->caption)
                                            <div class="mt-2 rounded-xl bg-white p-2 ring-1 ring-[#784828]/10">
                                                @if($item->alt_text)
                                                    <p class="truncate text-[10px] font-bold text-[#1f1712]">
                                                        Alt: {{ $item->alt_text }}
                                                    </p>
                                                @endif

                                                @if($item->caption)
                                                    <p class="mt-1 truncate text-[10px] font-semibold text-[#756b62]">
                                                        {{ $item->caption }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="mt-3 grid grid-cols-3 gap-1.5">
                                            <a
                                                href="{{ $item->url }}"
                                                target="_blank"
                                                class="inline-flex items-center justify-center rounded-xl bg-[#fff3df] px-2 py-2 text-[11px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10 transition hover:bg-[#ffe8bd]"
                                                title="View file"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <button
                                                type="button"
                                                @click="
                                                    editAction = '{{ route('media.update', $item) }}';
                                                    editMedia = {
                                                        file_name: @js($item->file_name),
                                                        alt_text: @js($item->alt_text),
                                                        caption: @js($item->caption),
                                                        description: @js($item->description),
                                                    };
                                                    editModalOpen = true;
                                                "
                                                class="inline-flex items-center justify-center rounded-xl bg-blue-50 px-2 py-2 text-[11px] font-black text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                                title="Edit media"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <form
                                                action="{{ route('media.destroy', $item) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this media file?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex w-full items-center justify-center rounded-xl bg-red-50 px-2 py-2 text-[11px] font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                    title="Delete media"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-16 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[2rem] bg-[#fff3df] text-[#8b4a2f]">
                                <i class="fa-solid fa-photo-film text-2xl"></i>
                            </div>

                            <h3 class="mt-4 text-lg font-black text-[#1f1712]">
                                No media files found
                            </h3>

                            <p class="mt-2 text-sm font-medium text-[#756b62]">
                                Upload your first image or file using the upload form.
                            </p>
                        </div>
                    @endif

                    @if($media->hasPages())
                        <div class="border-t border-[#784828]/10 px-5 py-4">
                            {{ $media->links() }}
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Edit Modal --}}
        <div
            x-show="editModalOpen"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="editModalOpen = false"
                class="w-full max-w-2xl overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Edit Media Information
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Update file name, alt text, caption and description.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="editModalOpen = false"
                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form :action="editAction" method="POST" class="space-y-5 p-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            File Name <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="file_name"
                            x-model="editMedia.file_name"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Alt Text
                        </label>

                        <input
                            type="text"
                            name="alt_text"
                            x-model="editMedia.alt_text"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Caption
                        </label>

                        <input
                            type="text"
                            name="caption"
                            x-model="editMedia.caption"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            x-model="editMedia.description"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        ></textarea>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-[#784828]/10 pt-5 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            @click="editModalOpen = false"
                            class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update Media
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>