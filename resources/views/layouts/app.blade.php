{{-- resources/views/layouts/app.blade.php --}}
@php
    use App\Models\Setting;

    $globalSetting = null;

    try {
        $globalSetting = Setting::with(['favicon', 'logo'])->first();
    } catch (\Throwable $e) {
        $globalSetting = null;
    }

    $siteName = $globalSetting?->site_name ?: 'Zannatul Keka';
    $siteTitle = $globalSetting?->site_title ?: $siteName . ' CMS';
    $faviconUrl = $globalSetting?->favicon?->url ?? null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteTitle }}</title>

    @if($globalSetting?->site_description)
        <meta name="description" content="{{ $globalSetting->site_description }}">
    @endif

    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 bg-[#f6f1eb]">
    <div
        x-data="{ sidebarOpen: false }"
        x-cloak
        class="min-h-screen bg-[#f6f1eb] bg-[radial-gradient(circle_at_top_left,rgba(139,74,47,0.08),transparent_34rem),radial-gradient(circle_at_top_right,rgba(198,154,82,0.10),transparent_32rem)]"
    >
        {{-- Sidebar / Navigation --}}
        @include('layouts.navigation')

        {{-- Main Content Wrapper --}}
        <div class="min-h-screen transition-all duration-300 md:pl-64">
            @isset($header)
                <header class="sticky top-0 z-30 border-b border-[#784828]/10 bg-white/80 backdrop-blur-xl">
                    <div class="w-full px-4 py-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Global Flash Messages --}}
            @if(session('success') || session('error') || session('status') || $errors->any())
                <div class="w-full px-4 pt-4 sm:px-6 lg:px-8">
                    <div class="space-y-3">
                        @if(session('success'))
                            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 shadow-sm">
                                <i class="fa-solid fa-circle-check mt-0.5"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800 shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800 shadow-sm">
                                <i class="fa-solid fa-circle-info mt-0.5"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800 shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                                <div>
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mt-2 list-disc space-y-1 pl-5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="w-full animate-[fadeIn_0.25s_ease-in-out]">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>