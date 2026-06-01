<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900">
        <!-- Alpine.js গ্লোবাল স্টেট ইন্টিগ্রেশন -->
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col">
            
            <!-- নেভিগেশন (সাইডবার এবং মোবাইল ড্রয়ার) -->
            @include('layouts.navigation')

            <!-- মেইন কন্টেন্ট এরিয়া (ডেস্কটপে সাইডবারের সাইজ অনুযায়ী বামে padding-left বা md:pl-64 দেওয়া হয়েছে) -->
            <div class="flex-1 flex flex-col min-w-0 md:pl-64">
                
                <!-- পেজ হেডিং -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- পেজ স্লট কন্টেন্ট -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>