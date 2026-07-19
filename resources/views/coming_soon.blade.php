<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#243A94">

    <title>Coming Soon | GEM Indonesia</title>
    <meta name="description"
        content="A new digital experience from GEM Indonesia is coming soon. Stay connected for exhibitions, industries, and global business opportunities.">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Coming Soon | GEM Indonesia">
    <meta property="og:description"
        content="A new digital experience from GEM Indonesia is coming soon. Stay connected for exhibitions, industries, and global business opportunities.">
    <meta property="og:image" content="{{ asset('assets/images/logo-gem-indonesia.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-gem-indonesia.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


    <script>
        (() => {
            const savedTheme = localStorage.getItem('gem-theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

            document.documentElement.setAttribute('data-theme', initialTheme);
        })();
    </script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
</head>

<body>
    <div class="site-shell d-flex flex-column min-vh-100">
        <div class="background-grid" aria-hidden="true"></div>
        <div class="background-glow background-glow-one" aria-hidden="true"></div>
        <div class="background-glow background-glow-two" aria-hidden="true"></div>

        <header class="site-header py-3 py-lg-4 position-relative z-3">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <a class="brand-link d-inline-flex align-items-center" href="#main-content"
                        aria-label="GEM Indonesia home" data-aos="fade-down">
                        <span class="brand-surface">
                            <img class="brand-logo" src="{{ asset('assets/images/logo-gem-indonesia.png') }}"
                                alt="GEM Indonesia Logo">
                        </span>
                    </a>

                    <button id="themeToggle" class="theme-toggle" type="button" aria-label="Switch to dark mode"
                        aria-pressed="false" title="Switch theme" data-aos="fade-down" data-aos-delay="100">
                        <span class="theme-toggle-track" aria-hidden="true">
                            <span class="theme-toggle-thumb">
                                <i class="bi bi-sun-fill theme-icon theme-icon-sun"></i>
                                <i class="bi bi-moon-stars-fill theme-icon theme-icon-moon"></i>
                            </span>
                        </span>
                        <span id="themeToggleText" class="theme-toggle-text">Light</span>
                    </button>
                </div>
            </div>
        </header>

        <main id="main-content" class="flex-grow-1 d-flex align-items-center position-relative z-2">
            <section class="hero-section w-100 py-5" aria-labelledby="page-title">
                <div class="container">
                    <div class="row align-items-center gy-5 gx-xl-5">
                        <div class="col-lg-7 col-xl-7">
                            <div class="hero-copy text-center text-lg-start">
                                <div class="eyebrow d-inline-flex align-items-center gap-2 mb-3" data-aos="fade-up">
                                    <span class="eyebrow-dot" aria-hidden="true"></span>
                                    Something Exciting Is On The Way
                                </div>

                                <h1 id="page-title" class="hero-title mb-4" data-aos="fade-up" data-aos-delay="100">
                                    We’re <span>Coming Soon</span>
                                </h1>

                                <p class="hero-lead mx-auto mx-lg-0 mb-3" data-aos="fade-up" data-aos-delay="200">
                                    We are preparing a new digital experience to connect businesses,
                                    industries, and opportunities worldwide.
                                </p>

                                <p class="hero-support mx-auto mx-lg-0 mb-4" data-aos="fade-up" data-aos-delay="250">
                                    Stay tuned as GEM Indonesia brings you a better way to discover
                                    our exhibitions, services, and global business opportunities.
                                </p>

                                <div class="launch-note d-inline-flex align-items-center gap-3" data-aos="fade-up"
                                    data-aos-delay="320">
                                    <span class="launch-note-icon" aria-hidden="true">
                                        <i class="bi bi-stars"></i>
                                    </span>
                                    <span>
                                        <small>New Website Experience</small>
                                        <strong>Launching Soon</strong>
                                    </span>
                                </div>

                                <div class="social-area d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-lg-start gap-3 gap-sm-4 mt-4"
                                    data-aos="fade-up" data-aos-delay="400">
                                    <span class="social-title">Follow Our Updates</span>

                                    <div class="d-flex align-items-center gap-2" aria-label="Social media links">
                                        <a class="social-link" href="#" aria-label="Instagram">
                                            <i class="bi bi-instagram" aria-hidden="true"></i>
                                        </a>
                                        <a class="social-link" href="#" aria-label="LinkedIn">
                                            <i class="bi bi-linkedin" aria-hidden="true"></i>
                                        </a>
                                        <a class="social-link" href="#" aria-label="Facebook">
                                            <i class="bi bi-facebook" aria-hidden="true"></i>
                                        </a>
                                        <a class="social-link" href="#" aria-label="YouTube">
                                            <i class="bi bi-youtube" aria-hidden="true"></i>
                                        </a>
                                    </div>

                                    <a class="email-link" href="mailto:info@gem-indonesia.com">
                                        info@gem-indonesia.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 col-xl-5">
                            <div class="visual-column d-flex justify-content-center align-items-center"
                                data-aos="fade-left" data-aos-delay="180">
                                <div class="world-visual" aria-hidden="true">
                                    <div class="visual-glow"></div>
                                    <div class="orbit orbit-one"></div>
                                    <div class="orbit orbit-two"></div>
                                    <div class="orbit-dot orbit-dot-one"></div>
                                    <div class="orbit-dot orbit-dot-two"></div>

                                    <div class="globe-shell">
                                        <div class="globe-grid"></div>
                                        <img class="globe-logo"
                                            src="{{ asset('assets/images/logo-gem-indonesia.png') }}" alt="">
                                        <div class="globe-shine"></div>
                                    </div>

                                    <div class="floating-card floating-card-top">
                                        <span class="floating-icon">
                                            <i class="bi bi-globe2"></i>
                                        </span>
                                        <span>
                                            <small>Global Reach</small>
                                            <strong>Worldwide Network</strong>
                                        </span>
                                    </div>

                                    <div class="floating-card floating-card-bottom">
                                        <span class="floating-icon">
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </span>
                                        <span>
                                            <small>Business Growth</small>
                                            <strong>New Opportunities</strong>
                                        </span>
                                    </div>

                                    <span class="particle particle-one"></span>
                                    <span class="particle particle-two"></span>
                                    <span class="particle particle-three"></span>
                                    <span class="particle particle-four"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer position-relative z-3 py-4">
            <div class="container">
                <div class="footer-divider"></div>
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 pt-4">
                    <p class="mb-0">© 2026 GEM Indonesia. All Rights Reserved.</p>
                    <p class="footer-tagline mb-0">Connecting Industries. Creating Opportunities.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
</body>

</html>
