{{-- resources/views/dashboard.blade.php --}}
@php
    use App\Models\Article;
    use App\Models\Category;
    use App\Models\ContactMessage;
    use App\Models\Gallery;
    use App\Models\MediaLibrary;
    use App\Models\Portfolio;
    use App\Models\PortfolioItem;
    use App\Models\Tag;
    use App\Models\User;
    use App\Models\Video;
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();

    try {
        $articleQuery = Article::query();
        $mediaQuery = MediaLibrary::query();

        if ($user?->isContributor()) {
            $articleQuery->where('user_id', $user->id);
            $mediaQuery->where('user_id', $user->id);
        }

        $stats = [
            'articles' => (clone $articleQuery)->count(),
            'published_articles' => (clone $articleQuery)->where('status', 'published')->count(),
            'draft_articles' => (clone $articleQuery)->where('status', 'draft')->count(),
            'media' => (clone $mediaQuery)->count(),
            'categories' => Category::count(),
            'tags' => Tag::count(),
            'galleries' => Gallery::count(),
            'videos' => Video::count(),
            'messages' => ContactMessage::where('status', 'new')->count(),
            'users' => User::count(),
            'portfolio_items' => PortfolioItem::count(),
        ];

        $recentArticles = Article::with(['author', 'featuredImage'])
            ->when($user?->isContributor(), fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = ContactMessage::latest()
            ->take(5)
            ->get();

        $portfolio = Portfolio::with('profilePicture')->first();
    } catch (\Throwable $e) {
        $stats = [
            'articles' => 0,
            'published_articles' => 0,
            'draft_articles' => 0,
            'media' => 0,
            'categories' => 0,
            'tags' => 0,
            'galleries' => 0,
            'videos' => 0,
            'messages' => 0,
            'users' => 0,
            'portfolio_items' => 0,
        ];

        $recentArticles = collect();
        $recentMessages = collect();
        $portfolio = null;
    }

    $roleLabel = match($user?->role) {
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'contributor' => 'Contributor',
        default => 'User',
    };

    $roleBadgeClass = match($user?->role) {
        'super_admin' => 'bg-[#2f1b12] text-white ring-[#2f1b12]/10',
        'admin' => 'bg-amber-50 text-amber-800 ring-amber-200',
        'contributor' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
        default => 'bg-slate-50 text-slate-700 ring-slate-200',
    };

    $quickLinks = [
        [
            'title' => 'Create Article',
            'subtitle' => 'Write and publish a new article',
            'icon' => 'fa-solid fa-pen-nib',
            'route' => 'articles.create',
            'roles' => ['super_admin', 'admin', 'contributor'],
        ],
        [
            'title' => 'Media Library',
            'subtitle' => 'Upload images and files',
            'icon' => 'fa-solid fa-photo-film',
            'route' => 'media.index',
            'roles' => ['super_admin', 'admin', 'contributor'],
        ],
        [
            'title' => 'Portfolio / CV',
            'subtitle' => 'Update personal information',
            'icon' => 'fa-solid fa-id-card-clip',
            'route' => 'portfolio.edit',
            'roles' => ['super_admin', 'admin'],
        ],
        [
            'title' => 'Gallery',
            'subtitle' => 'Manage photo albums',
            'icon' => 'fa-solid fa-images',
            'route' => 'galleries.index',
            'roles' => ['super_admin', 'admin', 'contributor'],
        ],
        [
            'title' => 'Videos',
            'subtitle' => 'Manage YouTube videos',
            'icon' => 'fa-brands fa-youtube',
            'route' => 'videos.index',
            'roles' => ['super_admin', 'admin', 'contributor'],
        ],
        [
            'title' => 'Settings',
            'subtitle' => 'Logo, SEO and contact info',
            'icon' => 'fa-solid fa-gear',
            'route' => 'settings.index',
            'roles' => ['super_admin', 'admin'],
        ],
    ];

    $statCards = [
        [
            'label' => 'Total Articles',
            'value' => $stats['articles'],
            'meta' => $stats['published_articles'] . ' published',
            'icon' => 'fa-solid fa-newspaper',
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-700',
            'ring' => 'ring-blue-100',
        ],
        [
            'label' => 'Draft Articles',
            'value' => $stats['draft_articles'],
            'meta' => 'Pending content',
            'icon' => 'fa-solid fa-file-pen',
            'bg' => 'bg-amber-50',
            'text' => 'text-amber-700',
            'ring' => 'ring-amber-100',
        ],
        [
            'label' => 'Media Files',
            'value' => $stats['media'],
            'meta' => 'Library items',
            'icon' => 'fa-solid fa-photo-film',
            'bg' => 'bg-purple-50',
            'text' => 'text-purple-700',
            'ring' => 'ring-purple-100',
        ],
        [
            'label' => 'Galleries',
            'value' => $stats['galleries'],
            'meta' => 'Photo albums',
            'icon' => 'fa-solid fa-images',
            'bg' => 'bg-pink-50',
            'text' => 'text-pink-700',
            'ring' => 'ring-pink-100',
        ],
        [
            'label' => 'Videos',
            'value' => $stats['videos'],
            'meta' => 'YouTube archive',
            'icon' => 'fa-brands fa-youtube',
            'bg' => 'bg-red-50',
            'text' => 'text-red-700',
            'ring' => 'ring-red-100',
        ],
        [
            'label' => 'Categories',
            'value' => $stats['categories'],
            'meta' => $stats['tags'] . ' tags',
            'icon' => 'fa-solid fa-layer-group',
            'bg' => 'bg-teal-50',
            'text' => 'text-teal-700',
            'ring' => 'ring-teal-100',
        ],
        [
            'label' => 'Portfolio Items',
            'value' => $stats['portfolio_items'],
            'meta' => 'CV sections',
            'icon' => 'fa-solid fa-id-card',
            'bg' => 'bg-orange-50',
            'text' => 'text-orange-700',
            'ring' => 'ring-orange-100',
        ],
        [
            'label' => 'New Messages',
            'value' => $stats['messages'],
            'meta' => 'Unread contact',
            'icon' => 'fa-solid fa-envelope-open-text',
            'bg' => 'bg-emerald-50',
            'text' => 'text-emerald-700',
            'ring' => 'ring-emerald-100',
        ],
    ];

    $canAccess = function (array $roles) use ($user) {
        return $user && in_array($user->role, $roles, true);
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Dashboard
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Welcome back, {{ $user?->name ?? 'Admin' }}. Manage Zannatul Keka website content from here.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-black ring-1 {{ $roleBadgeClass }}">
                    <i class="fa-solid fa-shield-halved"></i>
                    {{ $roleLabel }}
                </span>

                <a
                    href="{{ Route::has('home') ? route('home') : url('/') }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 rounded-full bg-[#8b4a2f] px-4 py-2 text-xs font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    View Website
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_380px]">
            <section class="overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/80 p-6 shadow-xl shadow-[#312114]/5 backdrop-blur md:p-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-black uppercase tracking-wide text-[#8b4a2f]">
                    <i class="fa-solid fa-star"></i>
                    Zannatul Keka CMS
                </div>

                <h1 class="mt-5 max-w-5xl text-4xl font-black leading-none tracking-[-0.07em] text-[#1f1712] md:text-6xl">
                    Manage portfolio, articles, gallery and video archive from one place.
                </h1>

                <p class="mt-5 max-w-3xl text-base font-medium leading-8 text-[#756b62]">
                    Use this dashboard to control personal CV information, media files, SEO-ready articles,
                    categories, tags, photo albums, YouTube videos, users and website settings.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    @if(Route::has('articles.create') && $canAccess(['super_admin', 'admin', 'contributor']))
                        <a href="{{ route('articles.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]">
                            <i class="fa-solid fa-plus"></i>
                            New Article
                        </a>
                    @endif

                    @if(Route::has('media.index') && $canAccess(['super_admin', 'admin', 'contributor']))
                        <a href="{{ route('media.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#1f1712] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#fff7ed]">
                            <i class="fa-solid fa-cloud-arrow-up text-[#8b4a2f]"></i>
                            Upload Media
                        </a>
                    @endif

                    @if(Route::has('portfolio.edit') && $canAccess(['super_admin', 'admin']))
                        <a href="{{ route('portfolio.edit') }}" class="inline-flex items-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#1f1712] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#fff7ed]">
                            <i class="fa-solid fa-id-card text-[#8b4a2f]"></i>
                            Update CV
                        </a>
                    @endif
                </div>
            </section>

            <aside class="rounded-[2rem] bg-gradient-to-br from-[#211610] to-[#7b3d27] p-6 text-white shadow-xl shadow-[#312114]/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-3xl border border-white/20 bg-white/10 text-2xl font-black">
                        @if($user?->profilePicture?->url)
                            <img src="{{ $user->profilePicture->url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(mb_substr($user?->name ?? 'A', 0, 1)) }}
                        @endif
                    </div>

                    <div class="min-w-0">
                        <h3 class="truncate text-lg font-black">
                            {{ $user?->name ?? 'Administrator' }}
                        </h3>
                        <p class="mt-1 text-sm font-semibold text-white/70">
                            {{ $roleLabel }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-white/10 bg-white/10 p-4">
                    <p class="text-sm font-medium leading-7 text-white/75">
                        Your access level controls what you can manage. Super Admin has complete system control,
                        Admin manages content and settings, and Contributor manages assigned content.
                    </p>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <div class="text-2xl font-black">{{ $stats['articles'] }}</div>
                        <div class="mt-1 text-xs font-bold uppercase tracking-wide text-white/60">Articles</div>
                    </div>

                    <div class="rounded-2xl bg-white/10 p-4">
                        <div class="text-2xl font-black">{{ $stats['media'] }}</div>
                        <div class="mt-1 text-xs font-bold uppercase tracking-wide text-white/60">Media</div>
                    </div>
                </div>
            </aside>
        </div>

        <section class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($statCards as $card)
                <div class="group rounded-[1.75rem] border border-[#784828]/10 bg-white/85 p-5 shadow-lg shadow-[#312114]/5 transition duration-200 hover:-translate-y-1 hover:bg-white hover:shadow-xl">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl ring-1 {{ $card['bg'] }} {{ $card['text'] }} {{ $card['ring'] }}">
                            <i class="{{ $card['icon'] }} text-lg"></i>
                        </div>

                        <span class="rounded-full bg-[#f6f1eb] px-3 py-1 text-xs font-black text-[#756b62]">
                            {{ $card['meta'] }}
                        </span>
                    </div>

                    <div class="mt-5 text-4xl font-black tracking-[-0.06em] text-[#1f1712]">
                        {{ $card['value'] }}
                    </div>

                    <div class="mt-2 text-sm font-bold text-[#756b62]">
                        {{ $card['label'] }}
                    </div>
                </div>
            @endforeach
        </section>

        <section class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_420px]">
            <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                <div class="flex items-center justify-between border-b border-[#784828]/10 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">Quick Actions</h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">Common dashboard shortcuts</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 2xl:grid-cols-3">
                    @foreach($quickLinks as $link)
                        @if($canAccess($link['roles']) && Route::has($link['route']))
                            <a
                                href="{{ route($link['route']) }}"
                                class="group flex items-start gap-4 rounded-3xl border border-[#784828]/10 bg-[#fbf7f1] p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-lg"
                            >
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8b4a2f] to-[#c69a52] text-white shadow-lg shadow-[#8b4a2f]/20">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>

                                <span>
                                    <strong class="block text-sm font-black text-[#1f1712]">
                                        {{ $link['title'] }}
                                    </strong>
                                    <span class="mt-1 block text-sm font-medium leading-6 text-[#756b62]">
                                        {{ $link['subtitle'] }}
                                    </span>
                                </span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                <div class="flex items-center justify-between border-b border-[#784828]/10 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">Recent Messages</h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">Latest contact form entries</p>
                    </div>

                    @if(Route::has('contact-messages.index') && $canAccess(['super_admin', 'admin']))
                        <a href="{{ route('contact-messages.index') }}" class="text-sm font-black text-[#8b4a2f] hover:text-[#62311f]">
                            View all
                        </a>
                    @endif
                </div>

                <div class="divide-y divide-[#784828]/10 px-5">
                    @forelse($recentMessages as $message)
                        <div class="flex items-center gap-4 py-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#fff3df] text-sm font-black text-[#8b4a2f]">
                                {{ strtoupper(mb_substr($message->name ?? 'M', 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-black text-[#1f1712]">
                                    {{ $message->name }}
                                </p>
                                <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                    {{ $message->subject ?: $message->email ?: 'No subject' }}
                                </p>
                            </div>

                            <span class="rounded-full px-3 py-1 text-[11px] font-black uppercase
                                {{ $message->status === 'new'
                                    ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'
                                    : 'bg-slate-50 text-slate-600 ring-1 ring-slate-200' }}">
                                {{ $message->status }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                <i class="fa-solid fa-inbox text-xl"></i>
                            </div>
                            <p class="mt-3 text-sm font-bold text-[#756b62]">
                                No recent messages found.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="mt-5 rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
            <div class="flex items-center justify-between border-b border-[#784828]/10 px-6 py-5">
                <div>
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">Recent Articles</h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">Latest article activity</p>
                </div>

                @if(Route::has('articles.index'))
                    <a href="{{ route('articles.index') }}" class="text-sm font-black text-[#8b4a2f] hover:text-[#62311f]">
                        View all
                    </a>
                @endif
            </div>

            <div class="divide-y divide-[#784828]/10 px-5">
                @forelse($recentArticles as $article)
                    <div class="flex items-center gap-4 py-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#fff3df] text-sm font-black text-[#8b4a2f]">
                            @if($article->featuredImage?->url)
                                <img src="{{ $article->featuredImage->url }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
                            @else
                                {{ strtoupper(mb_substr($article->title ?? 'A', 0, 1)) }}
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-black text-[#1f1712]">
                                {{ $article->title }}
                            </p>
                            <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                {{ $article->author?->name ?? 'Unknown Author' }}
                                @if($article->created_at)
                                    · {{ $article->created_at->format('M d, Y') }}
                                @endif
                            </p>
                        </div>

                        <span class="rounded-full px-3 py-1 text-[11px] font-black uppercase
                            @if($article->status === 'published')
                                bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                            @elseif($article->status === 'draft')
                                bg-amber-50 text-amber-700 ring-1 ring-amber-200
                            @else
                                bg-slate-50 text-slate-600 ring-1 ring-slate-200
                            @endif
                        ">
                            {{ $article->status }}
                        </span>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                            <i class="fa-solid fa-newspaper text-xl"></i>
                        </div>
                        <p class="mt-3 text-sm font-bold text-[#756b62]">
                            No articles created yet.
                        </p>

                        @if(Route::has('articles.create') && $canAccess(['super_admin', 'admin', 'contributor']))
                            <a href="{{ route('articles.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-[#8b4a2f] px-5 py-3 text-sm font-black text-white transition hover:bg-[#62311f]">
                                <i class="fa-solid fa-plus"></i>
                                Create First Article
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>