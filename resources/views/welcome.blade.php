{{-- resources/views/welcome.blade.php --}}
@php
    use App\Models\Setting;
    use App\Models\Portfolio;

    $setting = null;
    $portfolio = null;

    try {
        $setting = Setting::with(['logo', 'banner', 'defaultOgImage'])->first();
        $portfolio = Portfolio::with(['profilePicture', 'coverImage'])
            ->where('status', 'active')
            ->first();
    } catch (\Throwable $e) {
        $setting = null;
        $portfolio = null;
    }

    $siteName = $setting?->site_name ?: 'Zannatul Keka';
    $siteTitle = $setting?->site_title ?: 'Zannatul Keka | Portfolio, Articles & Creative Works';
    $siteDescription = $setting?->site_description ?: 'A modern personal portfolio and content archive for articles, gallery, videos, publications and creative works.';

    $logoUrl = $setting?->logo?->url ?? null;
    $profileImageUrl = $portfolio?->profilePicture?->url ?? null;
    $coverImageUrl = $portfolio?->coverImage?->url ?? $setting?->banner?->url ?? null;

    $displayName = $portfolio?->name ?: 'Zannatul Keka';
    $designation = $portfolio?->designation ?: 'Writer, Researcher & Creative Professional';
    $headline = $portfolio?->headline ?: 'Personal Portfolio, Articles, Gallery and Video Archive';
    $intro = $portfolio?->short_intro ?: 'A clean and professional digital platform to showcase biography, work identity, articles, publications, galleries, videos and achievements.';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteTitle }}</title>

    <meta name="description" content="{{ $siteDescription }}">
    <meta name="author" content="{{ $displayName }}">

    <meta property="og:title" content="{{ $siteTitle }}">
    <meta property="og:description" content="{{ $siteDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($setting?->defaultOgImage?->url)
        <meta property="og:image" content="{{ $setting->defaultOgImage->url }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --zk-bg: #f7f3ee;
            --zk-bg-soft: #fffaf4;
            --zk-dark: #17110d;
            --zk-muted: #756b62;
            --zk-border: rgba(23, 17, 13, 0.10);
            --zk-primary: #8b4a2f;
            --zk-primary-dark: #62311f;
            --zk-gold: #c69a52;
            --zk-white: #ffffff;
            --zk-shadow: 0 24px 80px rgba(49, 33, 20, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Instrument Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(198, 154, 82, 0.18), transparent 32rem),
                radial-gradient(circle at top right, rgba(139, 74, 47, 0.16), transparent 30rem),
                var(--zk-bg);
            color: var(--zk-dark);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .zk-page {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .zk-container {
            width: min(100% - 48px, 1440px);
            margin: 0 auto;
        }

        .zk-header {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(18px);
            background: rgba(247, 243, 238, 0.78);
            border-bottom: 1px solid var(--zk-border);
        }

        .zk-nav {
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .zk-brand {
            display: inline-flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .zk-logo-img {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 12px 32px rgba(139, 74, 47, 0.18);
            background: var(--zk-white);
        }

        .zk-logo-fallback {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, var(--zk-primary), var(--zk-gold));
            box-shadow: 0 12px 32px rgba(139, 74, 47, 0.22);
        }

        .zk-brand-text strong {
            display: block;
            font-size: 18px;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .zk-brand-text span {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--zk-muted);
            white-space: nowrap;
        }

        .zk-menu {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .zk-menu a {
            padding: 10px 13px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(23, 17, 13, 0.72);
            transition: 0.22s ease;
        }

        .zk-menu a:hover {
            color: var(--zk-dark);
            background: rgba(255, 255, 255, 0.70);
        }

        .zk-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .zk-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 44px;
            padding: 12px 18px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid transparent;
            transition: 0.22s ease;
            white-space: nowrap;
        }

        .zk-btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--zk-primary), var(--zk-primary-dark));
            box-shadow: 0 14px 30px rgba(98, 49, 31, 0.22);
        }

        .zk-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 38px rgba(98, 49, 31, 0.28);
        }

        .zk-btn-light {
            background: rgba(255, 255, 255, 0.74);
            border-color: var(--zk-border);
            color: var(--zk-dark);
        }

        .zk-btn-light:hover {
            background: #fff;
            transform: translateY(-1px);
        }

        .zk-hero {
            position: relative;
            padding: 78px 0 58px;
        }

        .zk-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(420px, 0.95fr);
            gap: 46px;
            align-items: center;
        }

        .zk-kicker {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--zk-border);
            color: var(--zk-primary-dark);
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .zk-hero h1 {
            margin: 0;
            max-width: 860px;
            font-size: clamp(44px, 7vw, 96px);
            line-height: 0.96;
            letter-spacing: -0.075em;
        }

        .zk-hero h1 span {
            color: var(--zk-primary);
        }

        .zk-hero-text {
            max-width: 700px;
            margin: 24px 0 0;
            color: var(--zk-muted);
            font-size: clamp(17px, 1.7vw, 21px);
            line-height: 1.72;
        }

        .zk-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 34px;
        }

        .zk-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 42px;
            max-width: 760px;
        }

        .zk-stat-card {
            padding: 18px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid var(--zk-border);
            box-shadow: 0 16px 34px rgba(49, 33, 20, 0.06);
        }

        .zk-stat-card i {
            color: var(--zk-primary);
            font-size: 18px;
            margin-bottom: 13px;
        }

        .zk-stat-card strong {
            display: block;
            font-size: 28px;
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .zk-stat-card span {
            display: block;
            margin-top: 7px;
            color: var(--zk-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .zk-profile-panel {
            position: relative;
            min-height: 640px;
            border-radius: 42px;
            padding: 18px;
            background:
                linear-gradient(145deg, rgba(255,255,255,0.82), rgba(255,255,255,0.36)),
                rgba(255,255,255,0.50);
            border: 1px solid rgba(255,255,255,0.76);
            box-shadow: var(--zk-shadow);
            overflow: hidden;
        }

        .zk-cover {
            position: absolute;
            inset: 18px;
            border-radius: 32px;
            overflow: hidden;
            background: linear-gradient(135deg, #2c1b14, #8b4a2f 55%, #c69a52);
        }

        .zk-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.75;
            filter: saturate(0.88) contrast(1.04);
        }

        .zk-cover::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(23,17,13,0.05), rgba(23,17,13,0.72)),
                radial-gradient(circle at top right, rgba(255,255,255,0.26), transparent 20rem);
        }

        .zk-profile-content {
            position: absolute;
            left: 38px;
            right: 38px;
            bottom: 38px;
            z-index: 2;
            color: #fff;
        }

        .zk-profile-img {
            width: 124px;
            height: 124px;
            border-radius: 34px;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.86);
            box-shadow: 0 18px 50px rgba(0,0,0,0.25);
            background: #fff;
            margin-bottom: 22px;
        }

        .zk-profile-placeholder {
            width: 124px;
            height: 124px;
            border-radius: 34px;
            display: grid;
            place-items: center;
            border: 5px solid rgba(255,255,255,0.86);
            background: linear-gradient(135deg, #fff, #f2dec5);
            color: var(--zk-primary-dark);
            font-size: 38px;
            font-weight: 900;
            margin-bottom: 22px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.25);
        }

        .zk-profile-content h2 {
            margin: 0;
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1;
            letter-spacing: -0.06em;
        }

        .zk-profile-content p {
            margin: 14px 0 0;
            max-width: 460px;
            color: rgba(255,255,255,0.82);
            line-height: 1.65;
        }

        .zk-floating-card {
            position: absolute;
            top: 34px;
            right: 34px;
            z-index: 4;
            width: 230px;
            padding: 18px;
            border-radius: 26px;
            background: rgba(255,255,255,0.88);
            color: var(--zk-dark);
            border: 1px solid rgba(255,255,255,0.82);
            box-shadow: 0 18px 50px rgba(0,0,0,0.12);
            backdrop-filter: blur(14px);
        }

        .zk-floating-card i {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            color: #fff;
            background: var(--zk-primary);
            margin-bottom: 14px;
        }

        .zk-floating-card strong {
            display: block;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .zk-floating-card span {
            display: block;
            color: var(--zk-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .zk-section {
            padding: 64px 0;
        }

        .zk-section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 24px;
        }

        .zk-section-head h2 {
            margin: 0;
            font-size: clamp(32px, 4vw, 52px);
            line-height: 1;
            letter-spacing: -0.06em;
        }

        .zk-section-head p {
            max-width: 560px;
            margin: 0;
            color: var(--zk-muted);
            line-height: 1.65;
        }

        .zk-feature-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .zk-feature-card {
            min-height: 220px;
            padding: 24px;
            border-radius: 30px;
            background: rgba(255,255,255,0.70);
            border: 1px solid var(--zk-border);
            box-shadow: 0 18px 42px rgba(49, 33, 20, 0.06);
            transition: 0.24s ease;
        }

        .zk-feature-card:hover {
            transform: translateY(-4px);
            background: rgba(255,255,255,0.92);
            box-shadow: 0 24px 58px rgba(49, 33, 20, 0.10);
        }

        .zk-feature-card i {
            width: 50px;
            height: 50px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, var(--zk-primary), var(--zk-gold));
            font-size: 18px;
            margin-bottom: 22px;
        }

        .zk-feature-card h3 {
            margin: 0;
            font-size: 20px;
            letter-spacing: -0.03em;
        }

        .zk-feature-card p {
            margin: 12px 0 0;
            color: var(--zk-muted);
            line-height: 1.65;
            font-size: 14px;
        }

        .zk-footer {
            padding: 28px 0 40px;
            color: var(--zk-muted);
        }

        .zk-footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-top: 24px;
            border-top: 1px solid var(--zk-border);
        }

        .zk-social {
            display: flex;
            gap: 10px;
        }

        .zk-social a {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: rgba(255,255,255,0.74);
            border: 1px solid var(--zk-border);
            color: var(--zk-primary);
            transition: 0.22s ease;
        }

        .zk-social a:hover {
            background: var(--zk-primary);
            color: #fff;
            transform: translateY(-2px);
        }

        @media (max-width: 1100px) {
            .zk-hero-grid {
                grid-template-columns: 1fr;
            }

            .zk-profile-panel {
                min-height: 560px;
            }

            .zk-feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .zk-container {
                width: min(100% - 28px, 1440px);
            }

            .zk-nav {
                height: auto;
                padding: 16px 0;
                align-items: flex-start;
            }

            .zk-menu {
                display: none;
            }

            .zk-brand-text span {
                white-space: normal;
            }

            .zk-actions {
                flex-shrink: 0;
            }

            .zk-btn {
                min-height: 40px;
                padding: 10px 13px;
                font-size: 13px;
            }

            .zk-hero {
                padding: 46px 0 34px;
            }

            .zk-stats {
                grid-template-columns: 1fr;
            }

            .zk-profile-panel {
                min-height: 520px;
                border-radius: 30px;
            }

            .zk-cover {
                inset: 12px;
                border-radius: 24px;
            }

            .zk-profile-content {
                left: 26px;
                right: 26px;
                bottom: 28px;
            }

            .zk-floating-card {
                display: none;
            }

            .zk-feature-grid {
                grid-template-columns: 1fr;
            }

            .zk-section-head {
                display: block;
            }

            .zk-section-head p {
                margin-top: 14px;
            }

            .zk-footer-inner {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
<div class="zk-page">
    <header class="zk-header">
        <div class="zk-container">
            <nav class="zk-nav">
                <a href="{{ route('home') }}" class="zk-brand" aria-label="{{ $siteName }}">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }} Logo" class="zk-logo-img">
                    @else
                        <span class="zk-logo-fallback">ZK</span>
                    @endif

                    <span class="zk-brand-text">
                        <strong>{{ $siteName }}</strong>
                        <span>Portfolio CMS & Digital Archive</span>
                    </span>
                </a>

                <div class="zk-menu">
                    <a href="#about">About</a>
                    <a href="#features">Sections</a>
                    <a href="#contact">Contact</a>
                </div>

                <div class="zk-actions">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="zk-btn zk-btn-primary">
                                <i class="fa-solid fa-gauge-high"></i>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="zk-btn zk-btn-light">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Login
                            </a>
                        @endauth
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="zk-hero" id="about">
            <div class="zk-container">
                <div class="zk-hero-grid">
                    <div>
                        <div class="zk-kicker">
                            <i class="fa-solid fa-star"></i>
                            Personal portfolio and content archive
                        </div>

                        <h1>
                            {{ $displayName }}
                            <br>
                            <span>digital portfolio.</span>
                        </h1>

                        <p class="zk-hero-text">
                            {{ $intro }}
                        </p>

                        <div class="zk-hero-actions">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="zk-btn zk-btn-primary">
                                    <i class="fa-solid fa-layer-group"></i>
                                    Manage Dashboard
                                </a>
                            @else
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="zk-btn zk-btn-primary">
                                        <i class="fa-solid fa-lock"></i>
                                        Admin Login
                                    </a>
                                @endif
                            @endauth

                            <a href="#features" class="zk-btn zk-btn-light">
                                <i class="fa-solid fa-arrow-down"></i>
                                Explore Sections
                            </a>
                        </div>

                        <div class="zk-stats">
                            <div class="zk-stat-card">
                                <i class="fa-solid fa-user-pen"></i>
                                <strong>CV</strong>
                                <span>Education, experience, skills and achievements.</span>
                            </div>

                            <div class="zk-stat-card">
                                <i class="fa-solid fa-newspaper"></i>
                                <strong>Articles</strong>
                                <span>SEO-ready articles, categories, tags and media.</span>
                            </div>

                            <div class="zk-stat-card">
                                <i class="fa-solid fa-images"></i>
                                <strong>Gallery</strong>
                                <span>Photo albums, image archive and YouTube videos.</span>
                            </div>
                        </div>
                    </div>

                    <aside class="zk-profile-panel">
                        <div class="zk-cover">
                            @if($coverImageUrl)
                                <img src="{{ $coverImageUrl }}" alt="{{ $displayName }} Cover">
                            @endif
                        </div>

                        <div class="zk-floating-card">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <strong>CMS Powered</strong>
                            <span>Logo, banner, profile, articles, gallery and videos can be controlled from dashboard.</span>
                        </div>

                        <div class="zk-profile-content">
                            @if($profileImageUrl)
                                <img src="{{ $profileImageUrl }}" alt="{{ $displayName }}" class="zk-profile-img">
                            @else
                                <div class="zk-profile-placeholder">
                                    {{ strtoupper(mb_substr($displayName, 0, 1)) }}
                                </div>
                            @endif

                            <h2>{{ $headline }}</h2>
                            <p>{{ $designation }}</p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="zk-section" id="features">
            <div class="zk-container">
                <div class="zk-section-head">
                    <h2>Website sections</h2>
                    <p>
                        This homepage is ready for your backend-controlled portfolio system.
                        When frontend routes are completed, these sections can connect directly with articles,
                        gallery albums, videos and portfolio items.
                    </p>
                </div>

                <div class="zk-feature-grid">
                    <div class="zk-feature-card">
                        <i class="fa-solid fa-id-card"></i>
                        <h3>Profile & CV</h3>
                        <p>Show name, designation, biography, education, experience, skills, services, publications and achievements.</p>
                    </div>

                    <div class="zk-feature-card">
                        <i class="fa-solid fa-pen-nib"></i>
                        <h3>Articles</h3>
                        <p>Publish SEO-friendly articles with categories, tags, featured image, metadata and YouTube embed support.</p>
                    </div>

                    <div class="zk-feature-card">
                        <i class="fa-solid fa-photo-film"></i>
                        <h3>Media Library</h3>
                        <p>Upload and reuse images, documents, thumbnails, banners, profile pictures and SEO images from dashboard.</p>
                    </div>

                    <div class="zk-feature-card">
                        <i class="fa-brands fa-youtube"></i>
                        <h3>Video Archive</h3>
                        <p>Add YouTube links and show them later in a modern video gallery without hosting videos on your server.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="zk-footer" id="contact">
        <div class="zk-container">
            <div class="zk-footer-inner">
                <div>
                    <strong>{{ $siteName }}</strong>
                    <div>
                        {{ $setting?->footer_text ?: '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.' }}
                    </div>
                </div>

                <div class="zk-social">
                    @if($setting?->facebook_url)
                        <a href="{{ $setting->facebook_url }}" target="_blank" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    @endif

                    @if($setting?->linkedin_url)
                        <a href="{{ $setting->linkedin_url }}" target="_blank" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    @endif

                    @if($setting?->youtube_url)
                        <a href="{{ $setting->youtube_url }}" target="_blank" aria-label="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif

                    @if($setting?->github_url)
                        <a href="{{ $setting->github_url }}" target="_blank" aria-label="GitHub">
                            <i class="fa-brands fa-github"></i>
                        </a>
                    @endif

                    @if(!$setting?->facebook_url && !$setting?->linkedin_url && !$setting?->youtube_url && !$setting?->github_url)
                        <a href="{{ Route::has('login') ? route('login') : '#' }}" aria-label="Admin">
                            <i class="fa-solid fa-user-shield"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>