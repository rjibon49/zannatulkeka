{{-- resources/views/articles/index.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;

    $currentUser = auth()->user();

    $statusLabels = [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ];

    $statusClasses = [
        'draft' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'published' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'archived' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];

    $hasFilter = !empty($search) || !empty($categoryId) || !empty($tagId) || !empty($status) || !empty($month);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Articles
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Manage SEO-ready articles, categories, tags, images and YouTube embeds.
                </p>
            </div>

            @if(Route::has('articles.create'))
                <a
                    href="{{ route('articles.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                >
                    <i class="fa-solid fa-pen-nib"></i>
                    Write New Article
                </a>
            @endif
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        {{-- Filter --}}
        <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
            <form action="{{ route('articles.index') }}" method="GET" class="grid grid-cols-1 gap-3 xl:grid-cols-[minmax(0,1fr)_220px_200px_180px_170px_auto_auto]">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search article title, subtitle or excerpt..."
                        class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-3 pl-11 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                    >
                </div>

                <select
                    name="category_id"
                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                >
                    <option value="">All Categories</option>
                    @foreach(($categories ?? collect()) as $cat)
                        <option value="{{ $cat->id }}" @selected((string)($categoryId ?? '') === (string)$cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <select
                    name="tag_id"
                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                >
                    <option value="">All Tags</option>
                    @foreach(($tags ?? collect()) as $tag)
                        <option value="{{ $tag->id }}" @selected((string)($tagId ?? '') === (string)$tag->id)>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>

                <select
                    name="status"
                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                >
                    <option value="">All Status</option>
                    @foreach($statusLabels as $statusKey => $statusName)
                        <option value="{{ $statusKey }}" @selected(($status ?? '') === $statusKey)>
                            {{ $statusName }}
                        </option>
                    @endforeach
                </select>

                <input
                    type="month"
                    name="month"
                    value="{{ $month ?? '' }}"
                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                >

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1f1712] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-black"
                >
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

                @if($hasFilter)
                    <a
                        href="{{ route('articles.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                    >
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Summary --}}
        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100">
                        <i class="fa-solid fa-newspaper"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black leading-none tracking-tight text-[#1f1712]">
                            {{ $articles->total() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Total Results
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                        <i class="fa-solid fa-circle-check"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black leading-none tracking-tight text-[#1f1712]">
                            {{ $articles->where('status', 'published')->count() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Published on this page
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                        <i class="fa-solid fa-file-pen"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black leading-none tracking-tight text-[#1f1712]">
                            {{ $articles->where('status', 'draft')->count() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Drafts on this page
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Articles --}}
        <section class="mt-5 overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
            <div class="flex flex-col gap-3 border-b border-[#784828]/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Article List
                    </h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">
                        Showing {{ $articles->count() }} of {{ $articles->total() }} article(s).
                    </p>
                </div>

                @if(Route::has('articles.create'))
                    <a
                        href="{{ route('articles.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-plus"></i>
                        New Article
                    </a>
                @endif
            </div>

            @if($articles->count())
                {{-- Desktop Table --}}
                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full divide-y divide-[#784828]/10">
                        <thead class="bg-[#fbf7f1]">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Article
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Category / Tag
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Status
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Date
                                </th>
                                <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#784828]/10 bg-white">
                            @foreach($articles as $article)
                                @php
                                    $articleStatus = $article->status ?: 'draft';
                                    $statusClass = $statusClasses[$articleStatus] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                                    $canDelete = $currentUser && in_array($currentUser->role, ['super_admin', 'admin'], true);
                                @endphp

                                <tr class="transition hover:bg-[#fbf7f1]">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-20 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#fff3df] text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                @if($article->featuredImage?->url)
                                                    <img
                                                        src="{{ $article->featuredImage->url }}"
                                                        alt="{{ $article->title }}"
                                                        class="h-full w-full object-cover"
                                                    >
                                                @else
                                                    <i class="fa-solid fa-newspaper text-xl"></i>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <h4 class="line-clamp-2 text-sm font-black leading-6 text-[#1f1712]">
                                                    {{ $article->title }}
                                                </h4>

                                                @if($article->subtitle)
                                                    <p class="mt-1 line-clamp-1 text-xs font-semibold text-[#756b62]">
                                                        {{ $article->subtitle }}
                                                    </p>
                                                @endif

                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-[11px] font-bold text-[#756b62] ring-1 ring-[#784828]/10">
                                                        <i class="fa-solid fa-user text-[#8b4a2f]"></i>
                                                        {{ $article->author?->name ?? 'Unknown' }}
                                                    </span>

                                                    @if($article->youtube_video_id)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-[11px] font-black text-red-700 ring-1 ring-red-100">
                                                            <i class="fa-brands fa-youtube"></i>
                                                            Video
                                                        </span>
                                                    @endif

                                                    @if($article->is_featured)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-[#fff3df] px-3 py-1 text-[11px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                            <i class="fa-solid fa-star"></i>
                                                            Featured
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="max-w-xs space-y-2">
                                            <div class="flex flex-wrap gap-1.5">
                                                @forelse($article->categories->take(2) as $cat)
                                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-[11px] font-black text-blue-700 ring-1 ring-blue-100">
                                                        {{ $cat->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs font-bold text-[#9a8c80]">
                                                        No category
                                                    </span>
                                                @endforelse

                                                @if($article->categories->count() > 2)
                                                    <span class="inline-flex rounded-full bg-slate-50 px-3 py-1 text-[11px] font-black text-slate-600 ring-1 ring-slate-100">
                                                        +{{ $article->categories->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex flex-wrap gap-1.5">
                                                @forelse($article->tags->take(2) as $tag)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-[#fff3df] px-3 py-1 text-[11px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                        <i class="fa-solid fa-tag"></i>
                                                        {{ $tag->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs font-bold text-[#9a8c80]">
                                                        No tag
                                                    </span>
                                                @endforelse

                                                @if($article->tags->count() > 2)
                                                    <span class="inline-flex rounded-full bg-slate-50 px-3 py-1 text-[11px] font-black text-slate-600 ring-1 ring-slate-100">
                                                        +{{ $article->tags->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
                                            {{ $statusLabels[$articleStatus] ?? ucfirst($articleStatus) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="text-sm font-black text-[#1f1712]">
                                            {{ $article->published_at?->format('M d, Y') ?: $article->created_at?->format('M d, Y') }}
                                        </div>
                                        <div class="mt-1 text-xs font-semibold text-[#756b62]">
                                            {{ $article->created_at?->diffForHumans() }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            @if(Route::has('articles.edit'))
                                                <a
                                                    href="{{ route('articles.edit', $article) }}"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                                    title="Edit article"
                                                >
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            @endif

                                            @if($canDelete && Route::has('articles.destroy'))
                                                <form
                                                    action="{{ route('articles.destroy', $article) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this article?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                        title="Delete article"
                                                    >
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile / Tablet Compact Cards --}}
                <div class="grid grid-cols-1 gap-4 p-4 lg:hidden">
                    @foreach($articles as $article)
                        @php
                            $articleStatus = $article->status ?: 'draft';
                            $statusClass = $statusClasses[$articleStatus] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                            $canDelete = $currentUser && in_array($currentUser->role, ['super_admin', 'admin'], true);
                        @endphp

                        <article class="rounded-[1.5rem] border border-[#784828]/10 bg-[#fbf7f1] p-3 shadow-sm">
                            <div class="flex gap-3">
                                <div class="flex h-24 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#fff3df] text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                    @if($article->featuredImage?->url)
                                        <img
                                            src="{{ $article->featuredImage->url }}"
                                            alt="{{ $article->title }}"
                                            class="h-full w-full object-cover"
                                        >
                                    @else
                                        <i class="fa-solid fa-newspaper text-xl"></i>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="mb-2 flex flex-wrap gap-1.5">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-black uppercase ring-1 {{ $statusClass }}">
                                            {{ $statusLabels[$articleStatus] ?? ucfirst($articleStatus) }}
                                        </span>

                                        @if($article->is_featured)
                                            <span class="inline-flex rounded-full bg-[#fff3df] px-2.5 py-1 text-[10px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                Featured
                                            </span>
                                        @endif
                                    </div>

                                    <h4 class="line-clamp-2 text-sm font-black leading-5 text-[#1f1712]">
                                        {{ $article->title }}
                                    </h4>

                                    <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                        {{ $article->author?->name ?? 'Unknown' }} ·
                                        {{ $article->created_at?->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach($article->categories->take(2) as $cat)
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-black text-blue-700 ring-1 ring-blue-100">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach

                                @foreach($article->tags->take(2) as $tag)
                                    <span class="inline-flex rounded-full bg-[#fff3df] px-2.5 py-1 text-[10px] font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="mt-3 flex justify-end gap-2 border-t border-[#784828]/10 pt-3">
                                @if(Route::has('articles.edit'))
                                    <a
                                        href="{{ route('articles.edit', $article) }}"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endif

                                @if($canDelete && Route::has('articles.destroy'))
                                    <form
                                        action="{{ route('articles.destroy', $article) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this article?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100"
                                        >
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[2rem] bg-[#fff3df] text-[#8b4a2f]">
                        <i class="fa-solid fa-newspaper text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-black text-[#1f1712]">
                        No articles found
                    </h3>

                    <p class="mt-2 text-sm font-medium text-[#756b62]">
                        Start by writing your first article or adjust the current filters.
                    </p>

                    @if(Route::has('articles.create'))
                        <a
                            href="{{ route('articles.create') }}"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Create Article
                        </a>
                    @endif
                </div>
            @endif

            @if($articles->hasPages())
                <div class="border-t border-[#784828]/10 px-5 py-4">
                    {{ $articles->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>