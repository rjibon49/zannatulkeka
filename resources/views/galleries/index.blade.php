<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">Gallery</h2>
            <a href="{{ route('galleries.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold">+ Add Image</a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($galleries as $gallery)
                <div class="relative group bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset($gallery->media->file_path) }}" class="rounded-lg w-full h-32 object-cover">
                    <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" class="absolute top-4 right-4">
                        @csrf @method('DELETE')
                        <button class="bg-rose-500 text-white p-1 rounded-full"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>