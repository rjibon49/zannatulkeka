{{-- resources/views/layouts/navigation.blade.php --}}
@php
    use App\Models\Setting;
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();
    $userRole = $user?->role;

    $setting = null;

    try {
        $setting = Setting::with('logo')->first();
    } catch (\Throwable $e) {
        $setting = null;
    }

    $siteName = $setting?->site_name ?: 'Zannatul Keka';
    $logoUrl = $setting?->logo?->url ?? null;

    $roleLabel = match($userRole) {
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'contributor' => 'Contributor',
        default => 'User',
    };

    $menuGroups = [
        [
            'title' => 'Overview',
            'items' => [
                [
                    'route' => 'dashboard',
                    'pattern' => 'dashboard',
                    'label' => 'Dashboard',
                    'icon' => 'fa-solid fa-gauge-high',
                    'roles' => ['super_admin', 'admin', 'contributor'],
                ],
            ],
        ],
        [
            'title' => 'Content',
            'items' => [
                [
                    'route' => 'articles.index',
                    'pattern' => 'articles.*',
                    'label' => 'Articles',
                    'icon' => 'fa-solid fa-newspaper',
                    'roles' => ['super_admin', 'admin', 'contributor'],
                ],
                [
                    'route' => 'media.index',
                    'pattern' => 'media.*',
                    'label' => 'Media Library',
                    'icon' => 'fa-solid fa-photo-film',
                    'roles' => ['super_admin', 'admin', 'contributor'],
                ],
                [
                    'route' => 'galleries.index',
                    'pattern' => 'galleries.*',
                    'label' => 'Gallery',
                    'icon' => 'fa-solid fa-images',
                    'roles' => ['super_admin', 'admin', 'contributor'],
                ],
                [
                    'route' => 'videos.index',
                    'pattern' => 'videos.*',
                    'label' => 'Videos',
                    'icon' => 'fa-brands fa-youtube',
                    'roles' => ['super_admin', 'admin', 'contributor'],
                ],
            ],
        ],
        [
            'title' => 'Taxonomy',
            'items' => [
                [
                    'route' => 'categories.index',
                    'pattern' => 'categories.*',
                    'label' => 'Categories',
                    'icon' => 'fa-solid fa-layer-group',
                    'roles' => ['super_admin', 'admin'],
                ],
                [
                    'route' => 'tags.index',
                    'pattern' => 'tags.*',
                    'label' => 'Tags',
                    'icon' => 'fa-solid fa-tags',
                    'roles' => ['super_admin', 'admin'],
                ],
            ],
        ],
        [
            'title' => 'Portfolio',
            'items' => [
                [
                    'route' => 'portfolio.edit',
                    'pattern' => 'portfolio.*',
                    'label' => 'Portfolio / CV',
                    'icon' => 'fa-solid fa-id-card-clip',
                    'roles' => ['super_admin', 'admin'],
                ],
            ],
        ],
        [
            'title' => 'System',
            'items' => [
                [
                    'route' => 'contact-messages.index',
                    'pattern' => 'contact-messages.*',
                    'label' => 'Contact Messages',
                    'icon' => 'fa-solid fa-envelope-open-text',
                    'roles' => ['super_admin', 'admin'],
                ],
                [
                    'route' => 'users.index',
                    'pattern' => 'users.*',
                    'label' => 'Users',
                    'icon' => 'fa-solid fa-users-gear',
                    'roles' => ['super_admin'],
                ],
                [
                    'route' => 'settings.index',
                    'pattern' => 'settings.*',
                    'label' => 'Settings',
                    'icon' => 'fa-solid fa-gear',
                    'roles' => ['super_admin', 'admin'],
                ],
            ],
        ],
    ];

    $filterItems = function ($items) use ($userRole) {
        return collect($items)
            ->filter(function ($item) use ($userRole) {
                return $userRole
                    && in_array($userRole, $item['roles'], true)
                    && Route::has($item['route']);
            })
            ->values();
    };

    $profileImageUrl = $user?->profilePicture?->url ?? null;
@endphp

{{-- Mobile Sidebar Overlay --}}
<div
    x-show="sidebarOpen"
    x-cloak
    class="fixed inset-0 z-50 flex md:hidden"
    role="dialog"
    aria-modal="true"
>
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"
        @click="sidebarOpen = false"
    ></div>

    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex w-full max-w-[320px] flex-1 flex-col bg-[#fbf7f1] shadow-2xl"
    >
        <button
            type="button"
            class="absolute right-[-52px] top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur transition hover:bg-white/20"
            @click="sidebarOpen = false"
        >
            <span class="sr-only">Close sidebar</span>
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <div class="flex h-20 shrink-0 items-center gap-3 border-b border-[#784828]/10 px-5">
            <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="flex min-w-0 items-center gap-3">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-12 w-12 rounded-2xl border border-white object-cover shadow-md">
                @else
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8b4a2f] to-[#c69a52] text-sm font-black tracking-tight text-white shadow-md">
                        ZK
                    </span>
                @endif

                <span class="min-w-0">
                    <span class="block truncate text-base font-black tracking-tight text-[#1f1712]">
                        {{ $siteName }}
                    </span>
                    <span class="block truncate text-xs font-semibold text-[#756b62]">
                        Portfolio CMS
                    </span>
                </span>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-5">
            <nav class="space-y-6">
                @foreach($menuGroups as $group)
                    @php
                        $items = $filterItems($group['items']);
                    @endphp

                    @if($items->isNotEmpty())
                        <div>
                            <p class="mb-2 px-3 text-[11px] font-black uppercase tracking-[0.18em] text-[#9a8c80]">
                                {{ $group['title'] }}
                            </p>

                            <div class="space-y-1">
                                @foreach($items as $item)
                                    @php
                                        $isActive = request()->routeIs($item['pattern']);
                                    @endphp

                                    <a
                                        href="{{ route($item['route']) }}"
                                        @click="sidebarOpen = false"
                                        class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-bold transition duration-200
                                            {{ $isActive
                                                ? 'bg-[#8b4a2f] text-white shadow-lg shadow-[#8b4a2f]/20'
                                                : 'text-[#5f5147] hover:bg-white hover:text-[#1f1712] hover:shadow-sm' }}"
                                    >
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl transition
                                            {{ $isActive
                                                ? 'bg-white/15 text-white'
                                                : 'bg-white text-[#8b4a2f] group-hover:bg-[#fff4e5]' }}">
                                            <i class="{{ $item['icon'] }}"></i>
                                        </span>

                                        <span class="truncate">
                                            {{ $item['label'] }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="border-t border-[#784828]/10 p-4">
            <div class="rounded-3xl bg-white p-3 shadow-sm ring-1 ring-[#784828]/10">
                <div class="flex items-center gap-3">
                    @if($profileImageUrl)
                        <img src="{{ $profileImageUrl }}" alt="{{ $user?->name }}" class="h-11 w-11 rounded-2xl object-cover">
                    @else
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff3df] text-sm font-black text-[#8b4a2f]">
                            {{ strtoupper(mb_substr($user?->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-black text-[#1f1712]">
                            {{ $user?->name }}
                        </p>
                        <p class="truncate text-[11px] font-bold uppercase tracking-wide text-[#8b4a2f]">
                            {{ $roleLabel }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-2 gap-2">
                    @if(Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="rounded-xl bg-[#f6f1eb] px-3 py-2 text-center text-xs font-black text-[#5f5147] transition hover:bg-[#eee3d8]">
                            Profile
                        </a>
                    @endif

                    @if(Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-red-50 px-3 py-2 text-center text-xs font-black text-red-600 transition hover:bg-red-100">
                                Logout
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- Desktop Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-[#784828]/10 bg-[#fbf7f1]/95 shadow-[12px_0_40px_rgba(49,33,20,0.05)] backdrop-blur-xl md:flex">
    <div class="flex h-20 shrink-0 items-center gap-3 border-b border-[#784828]/10 px-5">
        <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="flex min-w-0 items-center gap-3">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-12 w-12 rounded-2xl border border-white object-cover shadow-md">
            @else
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8b4a2f] to-[#c69a52] text-sm font-black tracking-tight text-white shadow-md">
                    ZK
                </span>
            @endif

            <span class="min-w-0">
                <span class="block truncate text-base font-black tracking-tight text-[#1f1712]">
                    {{ $siteName }}
                </span>
                <span class="block truncate text-xs font-semibold text-[#756b62]">
                    Admin Console
                </span>
            </span>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-5">
        <nav class="space-y-6">
            @foreach($menuGroups as $group)
                @php
                    $items = $filterItems($group['items']);
                @endphp

                @if($items->isNotEmpty())
                    <div>
                        <p class="mb-2 px-3 text-[11px] font-black uppercase tracking-[0.18em] text-[#9a8c80]">
                            {{ $group['title'] }}
                        </p>

                        <div class="space-y-1">
                            @foreach($items as $item)
                                @php
                                    $isActive = request()->routeIs($item['pattern']);
                                @endphp

                                <a
                                    href="{{ route($item['route']) }}"
                                    class="group flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-bold transition duration-200
                                        {{ $isActive
                                            ? 'bg-[#8b4a2f] text-white shadow-lg shadow-[#8b4a2f]/20'
                                            : 'text-[#5f5147] hover:bg-white hover:text-[#1f1712] hover:shadow-sm' }}"
                                >
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl text-[15px] transition
                                        {{ $isActive
                                            ? 'bg-white/15 text-white'
                                            : 'bg-white text-[#8b4a2f] group-hover:bg-[#fff4e5]' }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </span>

                                    <span class="truncate">
                                        {{ $item['label'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </nav>
    </div>

    <div x-data="{ userMenuOpen: false }" class="border-t border-[#784828]/10 p-4">
        <button
            type="button"
            @click="userMenuOpen = !userMenuOpen"
            class="flex w-full items-center gap-3 rounded-3xl bg-white p-3 text-left shadow-sm ring-1 ring-[#784828]/10 transition hover:shadow-md"
        >
            @if($profileImageUrl)
                <img src="{{ $profileImageUrl }}" alt="{{ $user?->name }}" class="h-11 w-11 rounded-2xl object-cover">
            @else
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff3df] text-sm font-black text-[#8b4a2f]">
                    {{ strtoupper(mb_substr($user?->name ?? 'A', 0, 1)) }}
                </div>
            @endif

            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-black text-[#1f1712]">
                    {{ $user?->name }}
                </span>
                <span class="block truncate text-[11px] font-bold uppercase tracking-wide text-[#8b4a2f]">
                    {{ $roleLabel }}
                </span>
            </span>

            <i class="fa-solid fa-chevron-up text-xs text-[#9a8c80] transition" :class="{ 'rotate-180': userMenuOpen }"></i>
        </button>

        <div
            x-show="userMenuOpen"
            x-cloak
            @click.away="userMenuOpen = false"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute bottom-24 left-4 right-4 overflow-hidden rounded-3xl border border-[#784828]/10 bg-white shadow-2xl"
        >
            @if(Route::has('profile.edit'))
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-[#5f5147] transition hover:bg-[#f6f1eb] hover:text-[#1f1712]">
                    <i class="fa-solid fa-user-gear w-5 text-[#8b4a2f]"></i>
                    Profile Settings
                </a>
            @endif

            @if(Route::has('home'))
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-[#5f5147] transition hover:bg-[#f6f1eb] hover:text-[#1f1712]">
                    <i class="fa-solid fa-globe w-5 text-[#8b4a2f]"></i>
                    View Website
                </a>
            @else
                <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-[#5f5147] transition hover:bg-[#f6f1eb] hover:text-[#1f1712]">
                    <i class="fa-solid fa-globe w-5 text-[#8b4a2f]"></i>
                    View Website
                </a>
            @endif

            @if(Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}" class="border-t border-[#784828]/10">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-black text-red-600 transition hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket w-5"></i>
                        Log Out
                    </button>
                </form>
            @endif
        </div>
    </div>
</aside>

{{-- Mobile Top Bar --}}
<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-[#784828]/10 bg-[#fbf7f1]/90 px-4 backdrop-blur-xl md:hidden">
    <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="flex min-w-0 items-center gap-2">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-9 w-9 rounded-xl object-cover">
        @else
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#8b4a2f] to-[#c69a52] text-xs font-black text-white">
                ZK
            </span>
        @endif

        <span class="truncate text-base font-black tracking-tight text-[#1f1712]">
            {{ $siteName }}
        </span>
    </a>

    <button
        type="button"
        @click="sidebarOpen = true"
        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#8b4a2f] shadow-sm ring-1 ring-[#784828]/10 transition hover:bg-[#fff4e5]"
    >
        <span class="sr-only">Open sidebar</span>
        <i class="fa-solid fa-bars"></i>
    </button>
</header>