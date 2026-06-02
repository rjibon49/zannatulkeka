{{-- resources/views/settings/index.blade.php --}}
@php
    $imageMedia = $media ?? collect();

    $logo = $setting->logo;
    $favicon = $setting->favicon;
    $banner = $setting->banner;
    $defaultOgImage = $setting->defaultOgImage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#1f1712]">
                    Website Settings
                </h2>
                <p class="mt-1 text-sm font-medium text-[#756b62]">
                    Manage website identity, homepage hero, contact information, SEO, social links and footer.
                </p>
            </div>
        </div>
    </x-slot>

    <div
        x-data="{
            showMediaModal: false,
            mediaTarget: '',
            imageSearch: '',

            logoId: @js(old('logo_media_id', $setting->logo_media_id)),
            logoUrl: @js($logo?->url),
            logoName: @js($logo?->file_name),

            faviconId: @js(old('favicon_media_id', $setting->favicon_media_id)),
            faviconUrl: @js($favicon?->url),
            faviconName: @js($favicon?->file_name),

            bannerId: @js(old('banner_media_id', $setting->banner_media_id)),
            bannerUrl: @js($banner?->url),
            bannerName: @js($banner?->file_name),

            ogImageId: @js(old('default_og_media_id', $setting->default_og_media_id)),
            ogImageUrl: @js($defaultOgImage?->url),
            ogImageName: @js($defaultOgImage?->file_name),

            openMedia(target) {
                this.mediaTarget = target;
                this.showMediaModal = true;
            },

            chooseImage(image) {
                if (this.mediaTarget === 'logo') {
                    this.logoId = image.id;
                    this.logoUrl = image.url;
                    this.logoName = image.name;
                }

                if (this.mediaTarget === 'favicon') {
                    this.faviconId = image.id;
                    this.faviconUrl = image.url;
                    this.faviconName = image.name;
                }

                if (this.mediaTarget === 'banner') {
                    this.bannerId = image.id;
                    this.bannerUrl = image.url;
                    this.bannerName = image.name;
                }

                if (this.mediaTarget === 'og') {
                    this.ogImageId = image.id;
                    this.ogImageUrl = image.url;
                    this.ogImageName = image.name;
                }

                this.showMediaModal = false;
            }
        }"
        class="w-full px-4 py-6 sm:px-6 lg:px-8"
    >
        <form action="{{ route('settings.update') }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_430px]">
            @csrf

            <input type="hidden" name="logo_media_id" :value="logoId">
            <input type="hidden" name="favicon_media_id" :value="faviconId">
            <input type="hidden" name="banner_media_id" :value="bannerId">
            <input type="hidden" name="default_og_media_id" :value="ogImageId">

            <section class="space-y-5">
                {{-- Site Identity --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff3df] text-[#8b4a2f]">
                            <i class="fa-solid fa-gear"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Site Identity
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Basic website name, title and description.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Site Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="site_name"
                                value="{{ old('site_name', $setting->site_name) }}"
                                required
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Zannatul Keka"
                            >
                            @error('site_name')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Site Title
                            </label>
                            <input
                                type="text"
                                name="site_title"
                                value="{{ old('site_title', $setting->site_title) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Zannatul Keka Portfolio"
                            >
                            @error('site_title')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Site Description
                            </label>
                            <textarea
                                name="site_description"
                                rows="4"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Personal portfolio, articles, gallery and video archive."
                            >{{ old('site_description', $setting->site_description) }}</textarea>
                            @error('site_description')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Homepage Hero --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                            <i class="fa-solid fa-house"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Homepage Hero
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Main heading and subheading for the public homepage.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Heading
                            </label>
                            <input
                                type="text"
                                name="heading"
                                value="{{ old('heading', $setting->heading) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Zannatul Keka"
                            >
                            @error('heading')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Subheading
                            </label>
                            <input
                                type="text"
                                name="subheading"
                                value="{{ old('subheading', $setting->subheading) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Portfolio, Articles and Creative Works"
                            >
                            @error('subheading')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
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
                                Public contact information for frontend.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Contact Email
                            </label>
                            <input
                                type="email"
                                name="contact_email"
                                value="{{ old('contact_email', $setting->contact_email) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="contact@example.com"
                            >
                            @error('contact_email')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Contact Phone
                            </label>
                            <input
                                type="text"
                                name="contact_phone"
                                value="{{ old('contact_phone', $setting->contact_phone) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="+880..."
                            >
                            @error('contact_phone')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Address
                            </label>
                            <textarea
                                name="address"
                                rows="4"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Full address..."
                            >{{ old('address', $setting->address) }}</textarea>
                            @error('address')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
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
                                Social and professional profile links.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        @foreach([
                            'facebook_url' => ['Facebook', 'https://facebook.com/...'],
                            'linkedin_url' => ['LinkedIn', 'https://linkedin.com/in/...'],
                            'twitter_url' => ['Twitter / X', 'https://x.com/...'],
                            'instagram_url' => ['Instagram', 'https://instagram.com/...'],
                            'youtube_url' => ['YouTube', 'https://youtube.com/...'],
                            'github_url' => ['GitHub', 'https://github.com/...'],
                        ] as $field => [$label, $placeholder])
                            <div>
                                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                    {{ $label }}
                                </label>
                                <input
                                    type="url"
                                    name="{{ $field }}"
                                    value="{{ old($field, $setting->{$field}) }}"
                                    class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                    placeholder="{{ $placeholder }}"
                                >
                                @error($field)
                                    <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
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
                                Default SEO Settings
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Default meta information for frontend pages.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Default Meta Title
                            </label>
                            <input
                                type="text"
                                name="default_meta_title"
                                value="{{ old('default_meta_title', $setting->default_meta_title) }}"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Default SEO title"
                            >
                            @error('default_meta_title')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                                Default Meta Description
                            </label>
                            <textarea
                                name="default_meta_description"
                                rows="4"
                                class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                                placeholder="Default SEO description..."
                            >{{ old('default_meta_description', $setting->default_meta_description) }}</textarea>
                            @error('default_meta_description')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#784828]/10 pb-5">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                            <i class="fa-solid fa-window-maximize"></i>
                        </span>

                        <div>
                            <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                                Footer Settings
                            </h3>
                            <p class="text-sm font-medium text-[#756b62]">
                                Footer content for frontend.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-[#756b62]">
                            Footer Text
                        </label>
                        <textarea
                            name="footer_text"
                            rows="4"
                            class="w-full rounded-2xl border-[#784828]/10 bg-[#fbf7f1] px-4 py-3 text-sm font-semibold text-[#1f1712] focus:border-[#8b4a2f] focus:ring-[#8b4a2f]/20"
                            placeholder="Footer description..."
                        >{{ old('footer_text', $setting->footer_text) }}</textarea>
                        @error('footer_text')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Sidebar --}}
            <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                {{-- Logo --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Logo
                    </h3>

                    <button
                        type="button"
                        @click="openMedia('logo')"
                        class="mt-5 flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="logoUrl">
                            <img :src="logoUrl" :alt="logoName || 'Logo'" class="h-full w-full object-contain p-4">
                        </template>

                        <template x-if="!logoUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-image text-3xl"></i>
                                <span class="text-xs font-black uppercase">Select Logo</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="openMedia('logo')" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button type="button" x-show="logoId" @click="logoId = ''; logoUrl = ''; logoName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                {{-- Favicon --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Favicon
                    </h3>

                    <button
                        type="button"
                        @click="openMedia('favicon')"
                        class="mt-5 flex aspect-square w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="faviconUrl">
                            <img :src="faviconUrl" :alt="faviconName || 'Favicon'" class="h-full w-full object-contain p-8">
                        </template>

                        <template x-if="!faviconUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-star text-3xl"></i>
                                <span class="text-xs font-black uppercase">Select Favicon</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="openMedia('favicon')" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button type="button" x-show="faviconId" @click="faviconId = ''; faviconUrl = ''; faviconName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                {{-- Banner --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Homepage Banner
                    </h3>

                    <button
                        type="button"
                        @click="openMedia('banner')"
                        class="mt-5 flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="bannerUrl">
                            <img :src="bannerUrl" :alt="bannerName || 'Banner'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!bannerUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-panorama text-3xl"></i>
                                <span class="text-xs font-black uppercase">Select Banner</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="openMedia('banner')" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button type="button" x-show="bannerId" @click="bannerId = ''; bannerUrl = ''; bannerName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                {{-- OG Image --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Default OG Image
                    </h3>

                    <button
                        type="button"
                        @click="openMedia('og')"
                        class="mt-5 flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-[#fff3df] ring-1 ring-[#784828]/10 transition hover:ring-[#8b4a2f]"
                    >
                        <template x-if="ogImageUrl">
                            <img :src="ogImageUrl" :alt="ogImageName || 'OG Image'" class="h-full w-full object-cover">
                        </template>

                        <template x-if="!ogImageUrl">
                            <span class="flex flex-col items-center gap-2 text-[#8b4a2f]">
                                <i class="fa-solid fa-share-nodes text-3xl"></i>
                                <span class="text-xs font-black uppercase">Select OG Image</span>
                            </span>
                        </template>
                    </button>

                    <div class="mt-3 flex gap-2">
                        <button type="button" @click="openMedia('og')" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-4 py-2.5 text-xs font-black text-white transition hover:bg-[#62311f]">
                            <i class="fa-solid fa-image"></i>
                            Choose
                        </button>

                        <button type="button" x-show="ogImageId" @click="ogImageId = ''; ogImageUrl = ''; ogImageName = ''" class="inline-flex items-center justify-center rounded-2xl bg-red-50 px-4 py-2.5 text-xs font-black text-red-700 ring-1 ring-red-100 transition hover:bg-red-100">
                            Remove
                        </button>
                    </div>
                </div>

                {{-- Save --}}
                <div class="rounded-[2rem] border border-[#784828]/10 bg-white/85 p-6 shadow-xl shadow-[#312114]/5">
                    <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                        Save Settings
                    </h3>

                    <p class="mt-1 text-sm font-medium leading-6 text-[#756b62]">
                        Save all website settings after making changes.
                    </p>

                    <button
                        type="submit"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#8b4a2f] px-6 py-3 text-sm font-black text-white shadow-lg shadow-[#8b4a2f]/20 transition hover:-translate-y-0.5 hover:bg-[#62311f]"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Settings
                    </button>
                </div>
            </aside>
        </form>

        {{-- Media Modal --}}
        <div
            x-show="showMediaModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
        >
            <div
                @click.away="showMediaModal = false"
                class="flex h-[85vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl"
            >
                <div class="flex flex-col gap-4 border-b border-[#784828]/10 bg-[#fbf7f1] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[#1f1712]">
                            Select Image
                        </h3>
                        <p class="mt-1 text-sm font-medium text-[#756b62]">
                            Choose an image from media library.
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
                            @click="showMediaModal = false"
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
                                @click="chooseImage({
                                    id: '{{ $img->id }}',
                                    url: '{{ $img->url }}',
                                    name: @js($img->file_name)
                                })"
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
                                    No image found. Upload images from Media Library first.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>