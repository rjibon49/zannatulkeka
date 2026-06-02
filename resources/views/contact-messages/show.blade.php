{{-- resources/views/contact-messages/show.blade.php --}}
@php
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

    $messageStatus = $contactMessage->status ?: 'new';
    $statusClass = $statusClasses[$messageStatus] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Message Details
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    View contact message and update its status.
                </p>
            </div>

            <a
                href="{{ route('contact-messages.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Messages
            </a>
        </div>
    </x-slot>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_420px]">
            {{-- Message Body --}}
            <section class="space-y-5">
                <div class="overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                    <div class="border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
                                    {{ $statusLabels[$messageStatus] ?? ucfirst($messageStatus) }}
                                </span>

                                <h3 class="mt-4 text-2xl font-black tracking-tight text-[#1f1712]">
                                    {{ $contactMessage->subject ?: 'No Subject' }}
                                </h3>

                                <p class="mt-2 text-sm font-medium text-[#756b62]">
                                    Received {{ $contactMessage->created_at?->format('M d, Y h:i A') }}
                                    @if($contactMessage->created_at)
                                        · {{ $contactMessage->created_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="rounded-[2rem] bg-white p-6 ring-1 ring-[#784828]/10">
                            <p class="whitespace-pre-line text-base font-medium leading-8 text-[#1f1712]">
                                {{ $contactMessage->message }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Sender Information --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                            <i class="fa-solid fa-user"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Sender Information
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Contact details submitted with this message.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-3xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                            <p class="text-xs font-black uppercase tracking-wide text-[#756b62]">Name</p>
                            <p class="mt-2 text-sm font-black text-[#1f1712]">
                                {{ $contactMessage->name ?? 'Not provided' }}
                            </p>
                        </div>

                        <div class="rounded-3xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                            <p class="text-xs font-black uppercase tracking-wide text-[#756b62]">Email</p>
                            <p class="mt-2 break-all text-sm font-black text-[#1f1712]">
                                {{ $contactMessage->email ?? 'Not provided' }}
                            </p>
                        </div>

                        <div class="rounded-3xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                            <p class="text-xs font-black uppercase tracking-wide text-[#756b62]">Phone</p>
                            <p class="mt-2 text-sm font-black text-[#1f1712]">
                                {{ $contactMessage->phone ?? 'Not provided' }}
                            </p>
                        </div>

                        <div class="rounded-3xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                            <p class="text-xs font-black uppercase tracking-wide text-[#756b62]">IP Address</p>
                            <p class="mt-2 text-sm font-black text-[#1f1712]">
                                {{ $contactMessage->ip_address ?? 'Not available' }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Sidebar --}}
            <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Update Status
                    </h3>

                    <form action="{{ route('contact-messages.update', $contactMessage) }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Message Status
                            </label>

                            <select
                                name="status"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                                @foreach($statusLabels as $statusKey => $statusName)
                                    <option value="{{ $statusKey }}" @selected($messageStatus === $statusKey)>
                                        {{ $statusName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update Status
                        </button>
                    </form>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Quick Actions
                    </h3>

                    <div class="mt-5 space-y-3">
                        @if(!empty($contactMessage->email))
                            <a
                                href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-50 px-5 py-3 text-sm font-black text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100"
                            >
                                <i class="fa-solid fa-reply"></i>
                                Reply by Email
                            </a>
                        @endif

                        @if(!empty($contactMessage->phone))
                            <a
                                href="tel:{{ $contactMessage->phone }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-50 px-5 py-3 text-sm font-black text-emerald-700 ring-1 ring-emerald-100 transition hover:bg-emerald-100"
                            >
                                <i class="fa-solid fa-phone"></i>
                                Call Sender
                            </a>
                        @endif

                        <form
                            action="{{ route('contact-messages.destroy', $contactMessage) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this message?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-red-50 px-5 py-3 text-sm font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                Delete Message
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>