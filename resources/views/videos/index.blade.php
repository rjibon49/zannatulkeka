{{-- resources/views/videos/index.blade.php --}}
@php
    use Illuminate\Support\Str;

    $statusClasses = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'inactive' => 'bg-red-50 text-red-700 ring-red-200',
        'draft' => 'bg-amber-50 text-amber-700 ring-amber-200',
    ];

    $hasFilter = !empty($search) || !empty($status);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Videos
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Manage YouTube videos, thumbnails, SEO information and display order.
                </p>
            </div>

            <a
                href="{{ route('videos.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
            >
                <i class="fa-brands fa-youtube"></i>
                Add Video
            </a>
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
            <form action="{{ route('videos.index') }}" method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_220px_auto_auto]">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search video title, slug or description..."
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
                    <option value="draft" @selected(($status ?? '') === 'draft')>Draft</option>
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
                        href="{{ route('videos.index') }}"
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
                        Video List
                    </h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">
                        Showing {{ $videos->count() }} of {{ $videos->total() }} video(s).
                    </p>
                </div>
            </div>

            @if($videos->count())
                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full divide-y divide-[#784828]/10">
                        <thead class="bg-[#fbf7f1]">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Video</th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">YouTube</th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Status</th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Sort</th>
                                <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wide text-[#756b62]">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#784828]/10 bg-white">
                            @foreach($videos as $video)
                                @php
                                    $statusClass = $statusClasses[$video->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                                    $thumbUrl = $video->thumbnail?->url ?? null;
                                    $youtubeId = $video->youtube_video_id ?? null;
                                    $youtubeWatchUrl = $youtubeId ? 'https://www.youtube.com/watch?v=' . $youtubeId : ($video->video_url ?? '#');
                                @endphp

                                <tr class="transition hover:bg-[#fbf7f1]">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex h-20 w-32 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#fff3df] text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                @if($thumbUrl)
                                                    <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" class="h-full w-full object-cover">
                                                @elseif($youtubeId)
                                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg" alt="{{ $video->title }}" class="h-full w-full object-cover">
                                                @else
                                                    <i class="fa-brands fa-youtube text-3xl"></i>
                                                @endif

                                                <span class="absolute inset-0 flex items-center justify-center bg-black/20">
                                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-red-600 shadow">
                                                        <i class="fa-solid fa-play text-xs"></i>
                                                    </span>
                                                </span>
                                            </div>

                                            <div class="min-w-0">
                                                <h4 class="line-clamp-2 text-sm font-black leading-6 text-[#1f1712]">
                                                    {{ $video->title }}
                                                </h4>

                                                <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                                    /{{ $video->slug }}
                                                </p>

                                                @if($video->description)
                                                    <p class="mt-1 line-clamp-1 text-xs font-medium text-[#9a8c80]">
                                                        {{ Str::limit($video->description, 90) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($youtubeId || $video->video_url)
                                            <a
                                                href="{{ $youtubeWatchUrl }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                            >
                                                <i class="fa-brands fa-youtube"></i>
                                                Watch
                                            </a>
                                        @else
                                            <span class="text-xs font-bold text-[#9a8c80]">No URL</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
                                            {{ $video->status }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-xl bg-[#f6f1eb] px-3 text-xs font-black text-[#756b62] ring-1 ring-[#784828]/10">
                                            {{ $video->sort_order ?? 0 }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ route('videos.edit', $video) }}"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                                title="Edit video"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form
                                                action="{{ route('videos.destroy', $video) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this video?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                    title="Delete video"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:hidden">
                    @foreach($videos as $video)
                        @php
                            $statusClass = $statusClasses[$video->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                            $thumbUrl = $video->thumbnail?->url ?? null;
                            $youtubeId = $video->youtube_video_id ?? null;
                        @endphp

                        <article class="overflow-hidden rounded-[1.5rem] border border-[#784828]/10 bg-[#fbf7f1] shadow-sm">
                            <div class="relative aspect-video bg-[#fff3df]">
                                @if($thumbUrl)
                                    <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" class="h-full w-full object-cover">
                                @elseif($youtubeId)
                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg" alt="{{ $video->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[#8b4a2f]">
                                        <i class="fa-brands fa-youtube text-4xl"></i>
                                    </div>
                                @endif

                                <span class="absolute left-3 top-3 rounded-full px-3 py-1 text-[10px] font-black uppercase ring-1 {{ $statusClass }}">
                                    {{ $video->status }}
                                </span>
                            </div>

                            <div class="p-4">
                                <h4 class="line-clamp-2 text-sm font-black text-[#1f1712]">
                                    {{ $video->title }}
                                </h4>

                                <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                    Sort: {{ $video->sort_order ?? 0 }}
                                </p>

                                <div class="mt-4 flex justify-end gap-2 border-t border-[#784828]/10 pt-3">
                                    <a href="{{ route('videos.edit', $video) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100">
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
                        <i class="fa-brands fa-youtube text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-black text-[#1f1712]">
                        No videos found
                    </h3>

                    <p class="mt-2 text-sm font-medium text-[#756b62]">
                        Add your first YouTube video from the button above.
                    </p>
                </div>
            @endif

            @if($videos->hasPages())
                <div class="border-t border-[#784828]/10 px-5 py-4">
                    {{ $videos->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>