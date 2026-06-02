{{-- resources/views/contact-messages/index.blade.php --}}
@php
    $items = $contactMessages ?? $messages ?? collect();

    $statusLabels = [
        'new' => 'New',
        'read' => 'Read',
        'replied' => 'Replied',
        'archived' => 'Archived',
    ];

    $statusClasses = [
        'new' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'read' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'replied' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'archived' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];

    $hasFilter = !empty($search) || !empty($status);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Contact Messages
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    View, filter, update and manage messages submitted from the website contact form.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        {{-- Filter --}}
        <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-5 shadow-xl shadow-[#312114]/5">
            <form action="{{ route('contact-messages.index') }}" method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_220px_auto_auto]">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#9a8c80]"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search name, email, phone, subject or message..."
                        class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] py-3 pl-11 pr-4 text-sm font-semibold text-[#1f1712] placeholder:text-[#9a8c80] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                    >
                </div>

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

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1f1712] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-black"
                >
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

                @if($hasFilter)
                    <a
                        href="{{ route('contact-messages.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-[#784828]/10 bg-white px-5 py-3 text-sm font-black text-[#756b62] transition hover:bg-[#fff7ed] hover:text-[#1f1712]"
                    >
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-[#1f1712]">
                            {{ method_exists($items, 'total') ? $items->total() : $items->count() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Total Messages
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                        <i class="fa-solid fa-bell"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-[#1f1712]">
                            {{ $items->where('status', 'new')->count() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            New on Page
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                        <i class="fa-solid fa-reply"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-[#1f1712]">
                            {{ $items->where('status', 'replied')->count() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Replied on Page
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-[#784828]/10 bg-white/80 p-5 shadow-lg shadow-[#312114]/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 ring-1 ring-slate-200">
                        <i class="fa-solid fa-box-archive"></i>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-[#1f1712]">
                            {{ $items->where('status', 'archived')->count() }}
                        </p>
                        <p class="mt-1 text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Archived on Page
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Message List --}}
        <section class="mt-5 overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
            <div class="flex flex-col gap-3 border-b border-[#784828]/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Message List
                    </h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">
                        Showing {{ $items->count() }} message(s).
                    </p>
                </div>
            </div>

            @if($items->count())
                {{-- Desktop Table --}}
                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full divide-y divide-[#784828]/10">
                        <thead class="bg-[#fbf7f1]">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Sender</th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Subject / Message</th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Status</th>
                                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wide text-[#756b62]">Date</th>
                                <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wide text-[#756b62]">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#784828]/10 bg-white">
                            @foreach($items as $message)
                                @php
                                    $messageStatus = $message->status ?: 'new';
                                    $statusClass = $statusClasses[$messageStatus] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                                @endphp

                                <tr class="transition hover:bg-[#fbf7f1]">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#fff3df] text-sm font-black text-[#8b4a2f] ring-1 ring-[#784828]/10">
                                                {{ strtoupper(mb_substr($message->name ?? 'M', 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-black text-[#1f1712]">
                                                    {{ $message->name ?? 'Unknown Sender' }}
                                                </p>
                                                <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                                    {{ $message->email ?? 'No email' }}
                                                </p>

                                                @if(!empty($message->phone))
                                                    <p class="mt-1 truncate text-xs font-semibold text-[#9a8c80]">
                                                        {{ $message->phone }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="max-w-xl">
                                            <p class="line-clamp-1 text-sm font-black text-[#1f1712]">
                                                {{ $message->subject ?: 'No subject' }}
                                            </p>
                                            <p class="mt-1 line-clamp-2 text-xs font-medium leading-5 text-[#756b62]">
                                                {{ $message->message }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
                                            {{ $statusLabels[$messageStatus] ?? ucfirst($messageStatus) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <p class="text-sm font-black text-[#1f1712]">
                                            {{ $message->created_at?->format('M d, Y') }}
                                        </p>
                                        <p class="mt-1 text-xs font-semibold text-[#756b62]">
                                            {{ $message->created_at?->diffForHumans() }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ route('contact-messages.show', $message) }}"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                                                title="View message"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <form
                                                action="{{ route('contact-messages.destroy', $message) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this message?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                    title="Delete message"
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

                {{-- Mobile Cards --}}
                <div class="grid grid-cols-1 gap-4 p-4 lg:hidden">
                    @foreach($items as $message)
                        @php
                            $messageStatus = $message->status ?: 'new';
                            $statusClass = $statusClasses[$messageStatus] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                        @endphp

                        <article class="rounded-[1.5rem] border border-[#784828]/10 bg-[#fbf7f1] p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-[#1f1712]">
                                        {{ $message->name ?? 'Unknown Sender' }}
                                    </p>
                                    <p class="mt-1 truncate text-xs font-semibold text-[#756b62]">
                                        {{ $message->email ?? 'No email' }}
                                    </p>
                                </div>

                                <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-[10px] font-black uppercase ring-1 {{ $statusClass }}">
                                    {{ $statusLabels[$messageStatus] ?? ucfirst($messageStatus) }}
                                </span>
                            </div>

                            <div class="mt-4">
                                <p class="line-clamp-1 text-sm font-black text-[#1f1712]">
                                    {{ $message->subject ?: 'No subject' }}
                                </p>
                                <p class="mt-1 line-clamp-3 text-xs font-medium leading-5 text-[#756b62]">
                                    {{ $message->message }}
                                </p>
                            </div>

                            <div class="mt-4 flex items-center justify-between border-t border-[#784828]/10 pt-3">
                                <p class="text-xs font-bold text-[#9a8c80]">
                                    {{ $message->created_at?->diffForHumans() }}
                                </p>

                                <div class="flex gap-2">
                                    <a
                                        href="{{ route('contact-messages.show', $message) }}"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 ring-1 ring-blue-100"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <form action="{{ route('contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
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
                        <i class="fa-solid fa-envelope-open-text text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-black text-[#1f1712]">
                        No messages found
                    </h3>

                    <p class="mt-2 text-sm font-medium text-[#756b62]">
                        Contact form messages will appear here.
                    </p>
                </div>
            @endif

            @if(method_exists($items, 'hasPages') && $items->hasPages())
                <div class="border-t border-[#784828]/10 px-5 py-4">
                    {{ $items->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>