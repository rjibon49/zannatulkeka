{{-- resources/views/portfolio/edit.blade.php --}}
@php
    use Illuminate\Support\Str;

    $portfolio = $portfolio ?? null;
    $media = $media ?? collect();
    $portfolioItems = $portfolioItems ?? collect();

    $imageMedia = $media->where('type', 'image');
    $documentMedia = $media->where('type', 'document');

    $selectedProfile = $portfolio?->profilePicture;
    $selectedCover = $portfolio?->coverImage;
    $selectedResume = $portfolio?->resumePdf;

    $itemTypes = [
        'education' => 'Education',
        'experience' => 'Experience',
        'skill' => 'Skill',
        'service' => 'Service',
        'project' => 'Project',
        'achievement' => 'Achievement',
        'award' => 'Award',
        'book' => 'Book',
        'publication' => 'Publication',
        'certificate' => 'Certificate',
        'social_link' => 'Social Link',
    ];

    $itemIcons = [
        'education' => 'fa-solid fa-graduation-cap',
        'experience' => 'fa-solid fa-briefcase',
        'skill' => 'fa-solid fa-code',
        'service' => 'fa-solid fa-handshake-angle',
        'project' => 'fa-solid fa-diagram-project',
        'achievement' => 'fa-solid fa-trophy',
        'award' => 'fa-solid fa-award',
        'book' => 'fa-solid fa-book',
        'publication' => 'fa-solid fa-file-lines',
        'certificate' => 'fa-solid fa-certificate',
        'social_link' => 'fa-solid fa-link',
    ];

    $statusClasses = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'inactive' => 'bg-red-50 text-red-700 ring-red-200',
        'draft' => 'bg-amber-50 text-amber-700 ring-amber-200',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Portfolio / CV
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Manage personal profile, CV information, resume, social links and portfolio sections.
                </p>
            </div>

            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#784828]/10 bg-white px-4 py-2.5 text-sm font-black text-[#1f1712] shadow-sm transition hover:bg-[#fff7ed]"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            showProfileModal: false,
            showCoverModal: false,
            showResumeModal: false,
            imageSearch: '',
            documentSearch: '',

            selectedProfileId: @js(old('profile_picture_id', $portfolio?->profile_picture_id)),
            selectedProfileUrl: @js($selectedProfile?->url),
            selectedProfileName: @js($selectedProfile?->file_name),

            selectedCoverId: @js(old('cover_media_id', $portfolio?->cover_media_id)),
            selectedCoverUrl: @js($selectedCover?->url),
            selectedCoverName: @js($selectedCover?->file_name),

            selectedResumeId: @js(old('resume_pdf_id', $portfolio?->resume_pdf_id)),
            selectedResumeName: @js($selectedResume?->file_name),

            chooseProfile(image) {
                this.selectedProfileId = image.id;
                this.selectedProfileUrl = image.url;
                this.selectedProfileName = image.name;
                this.showProfileModal = false;
            },

            chooseCover(image) {
                this.selectedCoverId = image.id;
                this.selectedCoverUrl = image.url;
                this.selectedCoverName = image.name;
                this.showCoverModal = false;
            },

            chooseResume(file) {
                this.selectedResumeId = file.id;
                this.selectedResumeName = file.name;
                this.showResumeModal = false;
            }
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <form action="{{ route('portfolio.update') }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_430px]">
            @csrf

            <input type="hidden" name="profile_picture_id" :value="selectedProfileId">
            <input type="hidden" name="cover_media_id" :value="selectedCoverId">
            <input type="hidden" name="resume_pdf_id" :value="selectedResumeId">

            {{-- Main Profile Information --}}
            <section class="space-y-5">
                <div class="overflow-hidden rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                    <div class="relative min-h-64 bg-gradient-to-br from-[#211610] to-[#8b4a2f]">
                        <template x-if="selectedCoverUrl">
                            <img :src="selectedCoverUrl" :alt="selectedCoverName || 'Cover image'" class="absolute inset-0 h-full w-full object-cover opacity-80">
                        </template>

                        <div class="absolute inset-0 bg-gradient-to-t from-[#1f1712]/80 via-[#1f1712]/20 to-transparent"></div>

                        <button
                            type="button"
                            @click="showCoverModal = true"
                            class="absolute right-5 top-5 inline-flex items-center gap-2 rounded-2xl bg-white/90 px-4 py-2 text-xs font-black text-[#1f1712] shadow-lg transition hover:bg-white"
                        >
                            <i class="fa-solid fa-image text-[#8b4a2f]"></i>
                            Change Cover
                        </button>

                        <div class="absolute bottom-6 left-6 right-6 flex flex-col gap-4 sm:flex-row sm:items-end">
                            <button
                                type="button"
                                @click="showProfileModal = true"
                                class="group flex h-32 w-32 shrink-0 items-center justify-center overflow-hidden rounded-[2rem] border-4 border-white bg-[#fff3df] text-[#8b4a2f] shadow-2xl transition hover:scale-[1.02]"
                            >
                                <template x-if="selectedProfileUrl">
                                    <img :src="selectedProfileUrl" :alt="selectedProfileName || 'Profile picture'" class="h-full w-full object-cover">
                                </template>

                                <template x-if="!selectedProfileUrl">
                                    <span class="flex flex-col items-center gap-2">
                                        <i class="fa-solid fa-user text-3xl"></i>
                                        <span class="text-[10px] font-black uppercase">Photo</span>
                                    </span>
                                </template>
                            </button>

                            <div class="min-w-0 text-white">
                                <p class="text-xs font-black uppercase tracking-[0.25em] text-white/70">
                                    Portfolio Profile
                                </p>
                                <h3 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">
                                    {{ old('name', $portfolio?->name ?: 'Zannatul Keka') }}
                                </h3>
                                <p class="mt-2 text-sm font-semibold text-white/75">
                                    {{ old('designation', $portfolio?->designation ?: 'Professional Portfolio & CV') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                                <i class="fa-solid fa-id-card-clip"></i>
                            </span>

                            <div>
                                <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                    Basic Information
                                </h3>
                                <p class="text-sm font-medium text-[#756b62]">
                                    Main personal and professional profile information.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $portfolio?->name) }}"
                                    required
                                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                    placeholder="Zannatul Keka"
                                >
                                @error('name')
                                    <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Designation
                                </label>
                                <input
                                    type="text"
                                    name="designation"
                                    value="{{ old('designation', $portfolio?->designation) }}"
                                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                    placeholder="Writer, Researcher, Professional"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Headline
                                </label>
                                <input
                                    type="text"
                                    name="headline"
                                    value="{{ old('headline', $portfolio?->headline) }}"
                                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                    placeholder="Personal Portfolio, Articles, Gallery and Creative Works"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Short Intro
                                </label>
                                <textarea
                                    name="short_intro"
                                    rows="4"
                                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                    placeholder="Short introduction for homepage hero section..."
                                >{{ old('short_intro', $portfolio?->short_intro) }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    Biography / About
                                </label>
                                <textarea
                                    name="biography"
                                    rows="8"
                                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                    placeholder="Write detailed biography or professional profile..."
                                >{{ old('biography', $portfolio?->biography) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <i class="fa-solid fa-address-book"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Contact Information
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Email, phone, address and website information.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $portfolio?->email) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="email@example.com"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Phone
                            </label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $portfolio?->phone) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="+880..."
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Website
                            </label>
                            <input
                                type="url"
                                name="website_url"
                                value="{{ old('website_url', $portfolio?->website_url) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="https://example.com"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Location
                            </label>
                            <input
                                type="text"
                                name="location"
                                value="{{ old('location', $portfolio?->location) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Dhaka, Bangladesh"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Address
                            </label>
                            <textarea
                                name="address"
                                rows="3"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Full address..."
                            >{{ old('address', $portfolio?->address) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Social Links --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-700">
                            <i class="fa-solid fa-share-nodes"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Social Links
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Add public social media and professional profile links.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Facebook
                            </label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $portfolio?->facebook_url) }}" class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20" placeholder="https://facebook.com/...">
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                LinkedIn
                            </label>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $portfolio?->linkedin_url) }}" class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20" placeholder="https://linkedin.com/in/...">
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                YouTube
                            </label>
                            <input type="url" name="youtube_url" value="{{ old('youtube_url', $portfolio?->youtube_url) }}" class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20" placeholder="https://youtube.com/...">
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                GitHub
                            </label>
                            <input type="url" name="github_url" value="{{ old('github_url', $portfolio?->github_url) }}" class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20" placeholder="https://github.com/...">
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Twitter / X
                            </label>
                            <input type="url" name="twitter_url" value="{{ old('twitter_url', $portfolio?->twitter_url) }}" class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20" placeholder="https://x.com/...">
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Instagram
                            </label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $portfolio?->instagram_url) }}" class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
                            <i class="fa-solid fa-magnifying-glass-chart"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                SEO Settings
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Search engine title and description for portfolio homepage.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Meta Title
                            </label>
                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title', $portfolio?->meta_title) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="SEO title..."
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Meta Description
                            </label>
                            <textarea
                                name="meta_description"
                                rows="4"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="SEO description..."
                            >{{ old('meta_description', $portfolio?->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Sidebar --}}
            <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Profile Picture
                    </h3>

                    <button
                        type="button"
                        @click="showProfileModal = true"
                        class="mt-5 flex aspect-square w-full items-center justify-center overflow-hidden rounded-[2rem] bg-[#fff3df] text-[#8b4a2f] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="selectedProfileUrl">
                            <img :src="selectedProfileUrl" :alt="selectedProfileName || 'Profile picture'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!selectedProfileUrl">
                            <span class="flex flex-col items-center gap-2">
                                <i class="fa-solid fa-user text-4xl"></i>
                                <span class="text-xs font-black uppercase">Select Photo</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="showProfileModal = true" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button type="button" x-show="selectedProfileId" @click="selectedProfileId = ''; selectedProfileUrl = ''; selectedProfileName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Resume PDF
                    </h3>

                    <div class="mt-5 rounded-3xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                        <div class="flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-700">
                                <i class="fa-solid fa-file-pdf"></i>
                            </span>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-black text-[#1f1712]" x-text="selectedResumeName || 'No resume selected'"></p>
                                <p class="mt-1 text-xs font-semibold text-[#756b62]">PDF or document from media library</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="showResumeModal = true" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-file"></i>
                            Choose
                        </button>

                        <button type="button" x-show="selectedResumeId" @click="selectedResumeId = ''; selectedResumeName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Publish Status
                    </h3>

                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            <option value="active" @selected(old('status', $portfolio?->status ?? 'active') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $portfolio?->status) === 'inactive')>Inactive</option>
                            <option value="draft" @selected(old('status', $portfolio?->status) === 'draft')>Draft</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Portfolio
                    </button>
                </div>
            </aside>
        </form>

        {{-- Portfolio Items --}}
        <section class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-[430px_minmax(0,1fr)]">
            {{-- Add Item --}}
            <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5 xl:sticky xl:top-24 xl:self-start">
                <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                        <i class="fa-solid fa-plus"></i>
                    </span>

                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Add CV Item
                        </h3>
                        <p class="text-sm font-medium text-[#756b62]">
                            Add education, experience, skills, projects and more.
                        </p>
                    </div>
                </div>

                <form action="{{ route('portfolio-items.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Type <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="type"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                        >
                            @foreach($itemTypes as $typeKey => $typeName)
                                <option value="{{ $typeKey }}" @selected(old('type') === $typeKey)>
                                    {{ $typeName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Title <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="Degree, job title, skill name, project title..."
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Subtitle / Organization
                        </label>

                        <input
                            type="text"
                            name="subtitle"
                            value="{{ old('subtitle') }}"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="University, company, publisher, platform..."
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Link
                        </label>

                        <input
                            type="url"
                            name="url"
                            value="{{ old('url') }}"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="https://..."
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Start Date
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                End Date
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>
                    </div>

                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl bg-[#fbf7f1] p-4 ring-1 ring-[#784828]/10">
                        <input
                            type="checkbox"
                            name="is_current"
                            value="1"
                            @checked(old('is_current'))
                            class="rounded border-[#784828]/20 text-[#8b4a2f] focus:ring-[#8b4a2f]/30"
                        >
                        <span>
                            <span class="block text-sm font-black text-[#1f1712]">
                                Currently Active
                            </span>
                            <span class="mt-1 block text-xs font-semibold text-[#756b62]">
                                For current job, study or active project.
                            </span>
                        </span>
                    </label>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="Details, responsibilities, achievements..."
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                value="{{ old('sort_order', 0) }}"
                                min="0"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Status
                            </label>

                            <select
                                name="status"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            >
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Add Item
                    </button>
                </form>
            </div>

            {{-- Item List --}}
            <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 shadow-xl shadow-[#312114]/5">
                <div class="border-b border-[#784828]/10 px-6 py-5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        CV / Portfolio Items
                    </h3>
                    <p class="mt-1 text-sm font-medium text-[#756b62]">
                        Manage all resume and portfolio sections from here.
                    </p>
                </div>

                <div class="divide-y divide-[#784828]/10">
                    @forelse($portfolioItems->groupBy('type') as $type => $items)
                        <div class="p-5">
                            <div class="mb-4 flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                                    <i class="{{ $itemIcons[$type] ?? 'fa-solid fa-circle' }}"></i>
                                </span>

                                <div>
                                    <h4 class="text-sm font-black uppercase tracking-wide text-[#1f1712]">
                                        {{ $itemTypes[$type] ?? ucfirst(str_replace('_', ' ', $type)) }}
                                    </h4>
                                    <p class="text-xs font-semibold text-[#756b62]">
                                        {{ $items->count() }} item(s)
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
                                @foreach($items as $item)
                                    <article class="rounded-3xl border border-[#784828]/10 bg-[#fbf7f1] p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <h5 class="line-clamp-2 text-sm font-black text-[#1f1712]">
                                                    {{ $item->title }}
                                                </h5>

                                                @if($item->subtitle)
                                                    <p class="mt-1 line-clamp-1 text-xs font-bold text-[#756b62]">
                                                        {{ $item->subtitle }}
                                                    </p>
                                                @endif
                                            </div>

                                            <span class="inline-flex shrink-0 rounded-full px-2.5 py-1 text-[10px] font-black uppercase ring-1 {{ $statusClasses[$item->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                                {{ $item->status }}
                                            </span>
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if($item->start_date)
                                                <span class="rounded-full bg-white px-3 py-1 text-[11px] font-bold text-[#756b62] ring-1 ring-[#784828]/10">
                                                    {{ \Carbon\Carbon::parse($item->start_date)->format('M Y') }}
                                                    -
                                                    {{ $item->is_current ? 'Present' : ($item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('M Y') : 'N/A') }}
                                                </span>
                                            @endif

                                            @if($item->url)
                                                <a href="{{ $item->url }}" target="_blank" class="rounded-full bg-blue-50 px-3 py-1 text-[11px] font-black text-blue-700 ring-1 ring-blue-100">
                                                    <i class="fa-solid fa-link mr-1"></i>
                                                    Link
                                                </a>
                                            @endif

                                            <span class="rounded-full bg-white px-3 py-1 text-[11px] font-bold text-[#756b62] ring-1 ring-[#784828]/10">
                                                Sort: {{ $item->sort_order ?? 0 }}
                                            </span>
                                        </div>

                                        @if($item->description)
                                            <p class="mt-3 line-clamp-3 text-sm font-medium leading-6 text-[#756b62]">
                                                {{ $item->description }}
                                            </p>
                                        @endif

                                        <div class="mt-4 flex justify-end border-t border-[#784828]/10 pt-3">
                                            <form
                                                action="{{ route('portfolio-items.destroy', $item) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this item?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-700 ring-1 ring-red-100 transition hover:bg-red-100"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-16 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[2rem] bg-[#fff3df] text-[#8b4a2f]">
                                <i class="fa-solid fa-id-card text-2xl"></i>
                            </div>

                            <h3 class="mt-4 text-lg font-black text-[#1f1712]">
                                No CV items found
                            </h3>

                            <p class="mt-2 text-sm font-medium text-[#756b62]">
                                Add education, experience, skills, projects or achievements from the form.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Image Selection Modal --}}
        <div
            x-show="showProfileModal || showCoverModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showProfileModal = false; showCoverModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex flex-col gap-4 border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Image
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose image from media library.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[#9a8c80]"></i>
                            <input
                                type="text"
                                x-model="imageSearch"
                                placeholder="Search images..."
                                class="w-full rounded-2xl border-[#784828]/10 bg-white py-2.5 pl-10 pr-4 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20 sm:w-72"
                            >
                        </div>

                        <button
                            type="button"
                            @click="showProfileModal = false; showCoverModal = false"
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                        @forelse($imageMedia as $img)
                            <button
                                type="button"
                                x-show="@js(strtolower(($img->file_name ?? '') . ' ' . ($img->original_name ?? '') . ' ' . ($img->alt_text ?? ''))) .includes(imageSearch.toLowerCase())"
                                @click="
                                    if (showProfileModal) {
                                        chooseProfile({
                                            id: '{{ $img->id }}',
                                            url: '{{ $img->url }}',
                                            name: @js($img->file_name)
                                        });
                                    } else {
                                        chooseCover({
                                            id: '{{ $img->id }}',
                                            url: '{{ $img->url }}',
                                            name: @js($img->file_name)
                                        });
                                    }
                                "
                                class="group relative aspect-square overflow-hidden rounded-3xl bg-[#fbf7f1] ring-2 ring-transparent transition hover:-translate-y-1 hover:ring-[#8b4a2f]"
                            >
                                <img
                                    src="{{ $img->url }}"
                                    alt="{{ $img->alt_text ?: $img->file_name }}"
                                    class="h-full w-full object-cover"
                                >

                                <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/30">
                                    <span class="scale-90 rounded-full bg-white px-3 py-1 text-xs font-black text-[#8b4a2f] opacity-0 transition group-hover:scale-100 group-hover:opacity-100">
                                        Select
                                    </span>
                                </div>
                            </button>
                        @empty
                            <div class="col-span-full py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                    <i class="fa-solid fa-image text-xl"></i>
                                </div>
                                <p class="mt-3 text-sm font-bold text-[#756b62]">
                                    No images found. Upload images from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Document Selection Modal --}}
        <div
            x-show="showResumeModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showResumeModal = false"
                class="flex h-[75vh] w-full max-w-4xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex flex-col gap-4 border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Resume File
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose PDF/document from media library.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="showResumeModal = false"
                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-[#756b62] shadow-sm ring-1 ring-[#784828]/10 transition hover:text-red-600"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-3">
                        @forelse($documentMedia as $file)
                            <button
                                type="button"
                                @click="chooseResume({
                                    id: '{{ $file->id }}',
                                    name: @js($file->file_name)
                                })"
                                class="flex w-full items-center gap-4 rounded-3xl border border-[#784828]/10 bg-[#fbf7f1] p-4 text-left transition hover:bg-white hover:shadow-lg"
                            >
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-700">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-black text-[#1f1712]">
                                        {{ $file->file_name }}
                                    </span>
                                    <span class="mt-1 block text-xs font-semibold text-[#756b62]">
                                        {{ strtoupper($file->extension ?? 'FILE') }} · {{ $file->readable_size }}
                                    </span>
                                </span>
                            </button>
                        @empty
                            <div class="py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-[#fff3df] text-[#8b4a2f]">
                                    <i class="fa-solid fa-file text-xl"></i>
                                </div>
                                <p class="mt-3 text-sm font-bold text-[#756b62]">
                                    No document found. Upload PDF from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>