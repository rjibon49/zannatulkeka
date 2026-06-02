{{-- resources/views/categories/index.blade.php --}}
@php
    $editing = isset($category) && $category;

    $statusClasses = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'inactive' => 'bg-red-50 text-red-700 ring-red-200',
    ];

    $formAction = $editing
        ? route('categories.update', $category)
        : route('categories.store');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Category Management
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Add, edit and organize article categories.
                </p>
            </div>

            @if($editing)
                <a
                    href="{{ route('categories.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
                >
                    <i class="fa-solid fa-plus"></i>
                    Add New Category
                </a>
            @endif
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[420px_minmax(0,1fr)]">
            {{-- Add / Edit Form --}}
            <section class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5 xl:sticky xl:top-24 xl:self-start">
                <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                        <i class="fa-solid {{ $editing ? 'fa-pen-to-square' : 'fa-folder-plus' }}"></i>
                    </span>

                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            {{ $editing ? 'Edit Category' : 'Add Category' }}
                        </h3>
                        <p class="text-sm font-medium text-[#756b62]">
                            {{ $editing ? 'Update selected category details.' : 'Create a new category for articles.' }}
                        </p>
                    </div>
                </div>

                <form action="{{ $formAction }}" method="POST" class="space-y-5">
                    @csrf

                    @if($editing)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="name" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Category Name <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $editing ? $category->name : '') }}"
                            required
                            placeholder="Example: News, Research, Blog"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >

                        @error('name')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug', $editing ? $category->slug : '') }}"
                            placeholder="Leave empty to auto-generate"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >

                        <p class="mt-2 text-xs font-semibold text-[#9a8c80]">
                            If empty, slug will be generated from category name.
                        </p>

                        @error('slug')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent_id" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Parent Category
                        </label>

                        <select
                            name="parent_id"
                            id="parent_id"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            <option value="">No Parent</option>

                            @foreach($parentCategories ?? [] as $parent)
                                <option
                                    value="{{ $parent->id }}"
                                    @selected((string) old('parent_id', $editing ? $category->parent_id : '') === (string) $parent->id)
                                >
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('parent_id')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            placeholder="Short category description..."
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >{{ old('description', $editing ? $category->description : '') }}</textarea>

                        @error('description')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="sort_order" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                id="sort_order"
                                min="0"
                                value="{{ old('sort_order', $editing ? $category->sort_order : 0) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >

                            @error('sort_order')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Status <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="status"
                                id="status"
                                required
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                                <option value="active" @selected(old('status', $editing ? $category->status : 'active') === 'active')>
                                    Active
                                </option>
                                <option value="inactive" @selected(old('status', $editing ? $category->status : '') === 'inactive')>
                                    Inactive
                                </option>
                            </select>

                            @error('status')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="meta_title" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            SEO Meta Title
                        </label>

                        <input
                            type="text"
                            name="meta_title"
                            id="meta_title"
                            value="{{ old('meta_title', $editing ? $category->meta_title : '') }}"
                            placeholder="Optional SEO title"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >

                        @error('meta_title')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            SEO Meta Description
                        </label>

                        <textarea
                            name="meta_description"
                            id="meta_description"
                            rows="3"
                            placeholder="Optional SEO description..."
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >{{ old('meta_description', $editing ? $category->meta_description : '') }}</textarea>

                        @error('meta_description')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 border-t border-[#784828]/10 pt-5 sm:flex-row sm:justify-end">
                        @if($editing)
                            <a
                                href="{{ route('categories.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                            >
                                Cancel
                            </a>
                        @endif

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ $editing ? 'Update Category' : 'Save Category' }}
                        </button>
                    </div>
                </form>
            </section>

            {{-- Category List --}}
            <section class="space-y-5">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
                    <form action="{{ route('categories.index') }}" method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_auto_auto]">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Search categories by name or slug..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-3 pl-11 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1f1712] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-black"
                        >
                            <i class="fa-solid fa-filter"></i>
                            Search
                        </button>

                        @if(!empty($search))
                            <a
                                href="{{ route('categories.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                            >
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                    <div class="flex flex-col gap-3 border-b border-[#784828]/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Categories
                            </h3>
                            <p class="mt-1 text-sm font-medium text-[#756b62]">
                                Total {{ $categories->total() }} category(s) found.
                            </p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#784828]/10">
                            <thead class="bg-[#fbf7f1]">
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                        Category
                                    </th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                        Parent
                                    </th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                        Sort
                                    </th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">
                                        Status
                                    </th>
                                    <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wide text-[#756b62]">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#784828]/10 bg-white">
                                @forelse($categories as $item)
                                    <tr class="transition hover:bg-[#fbf7f1]">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                    <i class="fa-solid fa-folder"></i>
                                                </div>

                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-black text-[#1f1712]">
                                                        {{ $item->name }}
                                                    </p>

                                                    <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                                        /{{ $item->slug }}
                                                    </p>

                                                    @if($item->description)
                                                        <p class="mt-1 line-clamp-1 text-xs font-medium text-[#9a8c80]">
                                                            {{ $item->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            @if($item->parent)
                                                <span class="inline-flex rounded-full bg-[#f6f1eb] px-3 py-1 text-xs font-black text-[#756b62] ring-1 ring-[#784828]/10">
                                                    {{ $item->parent->name }}
                                                </span>
                                            @else
                                                <span class="text-xs font-bold text-[#9a8c80]">
                                                    No Parent
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-xl bg-[#f6f1eb] px-3 text-xs font-black text-[#756b62] ring-1 ring-[#784828]/10">
                                                {{ $item->sort_order ?? 0 }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClasses[$item->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a
                                                    href="{{ route('categories.edit', $item) }}"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                                    title="Edit category"
                                                >
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>

                                                <form
                                                    action="{{ route('categories.destroy', $item) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this category?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                        title="Delete category"
                                                    >
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-14 text-center">
                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                                <i class="fa-solid fa-folder-open text-xl"></i>
                                            </div>
                                            <p class="mt-3 text-sm font-bold text-[#756b62]">
                                                No categories found.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="border-t border-[#784828]/10 px-5 py-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>