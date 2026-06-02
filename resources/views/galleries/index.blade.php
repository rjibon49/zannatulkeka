{{-- resources/views/galleries/index.blade.php --}}
@php
    use Illuminate\Support\Str;

    $statusClasses = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'inactive' => 'bg-red-50 text-red-700 ring-red-200',
    ];

    $hasFilter = !empty($search) || !empty($status);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Gallery Albums
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Create and manage photo gallery albums using media library images.
                </p>
            </div>

            <a
                href="{{ route('galleries.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
            >
                <i class="fa-solid fa-plus"></i>
                Create Gallery
            </a>
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
            <form action="{{ route('galleries.index') }}" method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_220px_auto_auto]">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search gallery title or slug..."
                        class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-3 pl-11 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                    >
                </div>

                <select
                    name="status"
                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                >
                    <option value="">All Status</option>
                    <option value="active" @selected(($status ?? '') === 'active')>Active</option>
                    <option value="inactive" @selected(($status ?? '') === 'inactive')>Inactive</option>
                </select>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1f1712] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-black"
                >
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

                @if($hasFilter)
                    <a
                        href="{{ route('galleries.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                    >
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <section class="mt-5 overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
            <div class="flex flex-col gap-3 border-b border-[#784828]/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Gallery List
                    </h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">
                        Showing {{ $galleries->count() }} of {{ $galleries->total() }} gallery album(s).
                    </p>
                </div>
            </div>

            @if($galleries->count())
                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    @foreach($galleries as $gallery)
                        @php
                            $coverUrl = $gallery->coverImage?->url ?: $gallery->images->first()?->media?->url;
                            $imageCount = $gallery->images?->count() ?? 0;
                            $statusClass = $statusClasses[$gallery->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                        @endphp

                        <article class="group overflow-hidden rounded-[1.75rem] border border-[#784828]/10 bg-[#fbf7f1] shadow-sm transition hover:-translate-y-1 hover:bg-white hover:shadow-xl">
                            <div class="relative aspect-[16/10] overflow-hidden bg-[#fff3df]">
                                @if($coverUrl)
                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="{{ $gallery->title }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    >
                                @else
                                    <div class="flex h-full w-full flex-col items-center justify-center text-[#8b4a2f]">
                                        <i class="fa-solid fa-images text-4xl"></i>
                                        <span class="mt-3 text-xs font-black uppercase tracking-wide">
                                            No Image
                                        </span>
                                    </div>
                                @endif

                                <div class="absolute left-3 top-3">
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
                                        {{ $gallery->status }}
                                    </span>
                                </div>

                                <div class="absolute right-3 top-3">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/90 px-3 py-1 text-[11px] font-black text-[#1f1712] ring-1 ring-[#784828]/10">
                                        <i class="fa-solid fa-image text-[#8b4a2f]"></i>
                                        {{ $imageCount }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-5">
                                <h4 class="line-clamp-2 text-base font-black leading-6 text-[#1f1712]">
                                    {{ $gallery->title }}
                                </h4>

                                <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                    /{{ $gallery->slug }}
                                </p>

                                @if($gallery->description)
                                    <p class="mt-3 line-clamp-2 text-sm font-medium leading-6 text-[#756b62]">
                                        {{ Str::limit($gallery->description, 120) }}
                                    </p>
                                @endif

                                @if($gallery->images->count())
                                    <div class="mt-4 flex -space-x-2 overflow-hidden">
                                        @foreach($gallery->images->take(6) as $galleryImage)
                                            @if($galleryImage->media?->url)
                                                <img
                                                    src="{{ $galleryImage->media->url }}"
                                                    alt="{{ $gallery->title }}"
                                                    class="h-9 w-9 rounded-xl border-2 border-white object-cover"
                                                >
                                            @endif
                                        @endforeach

                                        @if($gallery->images->count() > 6)
                                            <span class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white bg-[#fff3df] text-[10px] font-black text-[#8b4a2f]">
                                                +{{ $gallery->images->count() - 6 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-5 flex items-center justify-end gap-2 border-t border-[#784828]/10 pt-4">
                                    <a
                                        href="{{ route('galleries.edit', $gallery) }}"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                        title="Edit gallery"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form
                                        action="{{ route('galleries.destroy', $gallery) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this gallery?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                            title="Delete gallery"
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
                        <i class="fa-solid fa-images text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-black text-[#1f1712]">
                        No gallery found
                    </h3>

                    <p class="mt-2 text-sm font-medium text-[#756b62]">
                        Create your first gallery album from media library images.
                    </p>

                    <a
                        href="{{ route('galleries.create') }}"
                        class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Create Gallery
                    </a>
                </div>
            @endif

            @if($galleries->hasPages())
                <div class="border-t border-[#784828]/10 px-5 py-4">
                    {{ $galleries->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>