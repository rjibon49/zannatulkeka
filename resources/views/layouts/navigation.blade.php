@php
    /*
    ===========================================================================
    ১. গ্লোবাল মেনু লিংক এবং পারমিশন সেটআপ (RBAC CONFIGURATION)
    ===========================================================================
    */
    $userRole = auth()->check() ? auth()->user()->role : null;

    $allLinks = [
        [
            'route' => 'dashboard', 
            'label' => 'Dashboard', 
            'icon' => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z',
            'roles' => ['super_admin', 'admin', 'contributor']
        ],
        [
            'route' => 'articles.index', 
            'pattern' => 'articles.*', 
            'label' => 'Articles', 
            'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25',
            'roles' => ['super_admin', 'admin', 'contributor']
        ],
        [
            'route' => 'galleries.index', 
            'pattern' => 'galleries.*', 
            'label' => 'Gallery', 
            'icon' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
            'roles' => ['super_admin', 'admin', 'contributor'] // আপনার প্রয়োজন অনুযায়ী রোল সেট করুন
        ],
        [
            'route' => 'media.index', 
            'pattern' => 'media.*', 
            'label' => 'Media Library', 
            'icon' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
            'roles' => ['super_admin', 'admin', 'contributor']
        ],
        [
            'route' => 'categories.index', 
            'pattern' => 'categories.*', 
            'label' => 'Categories', 
            'icon' => 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l7.399 7.399a2.25 2.25 0 0 0 3.182 0l4.319-4.319a2.25 2.25 0 0 0 0-3.182L11.16 3.659A2.25 2.25 0 0 0 9.568 3Z M6 7.5h.008v.008H6V7.5Z',
            'roles' => ['super_admin', 'admin']
        ],
        [
            'route' => 'portfolio.edit', 
            'pattern' => 'portfolio.*', 
            'label' => 'My Portfolio Info', 
            'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z',
            'roles' => ['super_admin', 'admin']
        ],
        [
            'route' => 'users.index', 
            'pattern' => 'users.*', 
            'label' => 'Users', 
            'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
            'roles' => ['super_admin', 'admin']
        ],
        [
            'route' => 'settings.index', 
            'pattern' => 'settings.*', 
            'label' => 'Settings', 
            'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.767a1.123 1.123 0 0 0-.417 1.03c.004.074.006.148.006.222 0 .074-.002.148-.006.222a1.123 1.123 0 0 0 .417 1.03l1.003.767a1.125 1.125 0 0 1 .26 1.43l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.216-.456a1.125 1.125 0 0 0-1.076.124c-.072.044-.146.087-.22.128-.332.183-.582.495-.645.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.646-.87c-.074-.04-.148-.083-.22-.127a1.124 1.124 0 0 0-1.075-.124l-1.217.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .26-1.43l1.003-.767a1.122 1.122 0 0 0 .417-1.03c-.004-.074-.006-.148-.006-.222 0-.074.002-.148.006-.222a1.122 1.122 0 0 0-.417-1.03l-1.003-.767a1.125 1.125 0 0 1-.26-1.43l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.213-1.28ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z',
            'roles' => ['super_admin', 'admin']
        ]
    ];

    $links = array_filter($allLinks, function($link) use ($userRole) {
        return in_array($userRole, $link['roles']);
    });
@endphp

<div x-show="sidebarOpen" class="fixed inset-0 z-50 flex md:hidden" x-ref="dialog" aria-modal="true" style="display: none;">
    
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" 
         @click="sidebarOpen = false"></div>

    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="relative flex flex-col flex-1 w-full max-w-xs bg-white dark:bg-gray-800 pt-5 pb-4">
        
        <div class="absolute top-0 right-0 pt-2 -me-12">
            <button type="button" class="ms-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none" @click="sidebarOpen = false">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex items-center shrink-0 px-4 mb-4">
            <a href="{{ route('dashboard') }}" class="text-xl font-extrabold tracking-wide text-gray-900 dark:text-white">
                Zannatul Keka
            </a>
        </div>

        <div class="flex-1 h-0 overflow-y-auto px-3 space-y-1">
            @foreach($links as $link)
                @php $isActive = request()->routeIs($link['pattern'] ?? $link['route']); @endphp
                <a href="{{ route($link['route']) }}" 
                   class="{{ $isActive ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-100' }} group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-150">
                    <svg class="{{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} shrink-0 me-4 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                    </svg>
                    {{ __($link['label']) }}
                </a>
            @endforeach
        </div>

        <div class="mt-auto px-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3 py-2 px-1">
                @if(Auth::user()->profilePicture)
                    <img src="{{ asset(Auth::user()->profilePicture->file_path) }}" alt="Profile" class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600 shrink-0">
                @else
                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0 border border-transparent">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1 truncate">
                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-3">
                <a href="{{ route('profile.edit') }}" class="text-center py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-lg transition">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-center py-2 bg-rose-50 dark:bg-rose-500/10 hover:bg-rose-100 dark:hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-lg transition">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-40 justify-between">
    
    <div class="flex flex-col flex-1 h-0">
        <div class="flex items-center h-16 shrink-0 px-6 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="text-xl font-extrabold tracking-wide text-gray-900 dark:text-white hover:opacity-90 transition">
                Zannatul Keka
            </a>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar">
            @foreach($links as $link)
                @php $isActive = request()->routeIs($link['pattern'] ?? $link['route']); @endphp
                <a href="{{ route($link['route']) }}" 
                   class="{{ $isActive ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-bold border-l-4 border-indigo-600 dark:border-indigo-400 pl-2.5' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/20 hover:text-gray-900 dark:hover:text-gray-100 pl-3.5' }} group flex items-center py-2.5 text-sm font-medium rounded-r-xl transition-all duration-150">
                    <svg class="{{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }} shrink-0 me-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                    </svg>
                    {{ __($link['label']) }}
                </a>
            @endforeach
        </div>
    </div>

    <div x-data="{ userMenuOpen: false }" class="border-t border-gray-200 dark:border-gray-700 p-4 relative bg-gray-50/50 dark:bg-gray-800">
        <button @click="userMenuOpen = !userMenuOpen" class="flex w-full items-center gap-3 p-2 rounded-xl hover:bg-white dark:hover:bg-gray-700 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition-all shadow-sm">
            @if(Auth::user()->profilePicture)
                <img src="{{ asset(Auth::user()->profilePicture->file_path) }}" alt="Profile" class="h-9 w-9 rounded-full object-cover border border-gray-200 dark:border-gray-600 shrink-0">
            @else
                <div class="h-9 w-9 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0 border border-transparent">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
            <div class="flex-1 text-left truncate">
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
            </div>
            <svg class="h-5 w-5 text-gray-400 shrink-0 transform transition-transform duration-200" :class="{'rotate-180': userMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>

        <div x-show="userMenuOpen" 
             @click.away="userMenuOpen = false"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="absolute bottom-full left-4 right-4 mb-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden z-50" style="display: none;">
            
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Profile Settings
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 dark:border-gray-800">
                @csrf
                <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>

<div class="sticky top-0 z-30 flex md:hidden h-16 shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 w-full justify-between items-center px-4 sm:px-6">
    <a href="{{ route('dashboard') }}" class="text-xl font-extrabold tracking-wide text-gray-900 dark:text-white">
        Zannatul Keka
    </a>
    <button type="button" @click="sidebarOpen = true" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>
</div>