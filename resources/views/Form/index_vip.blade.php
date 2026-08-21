<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="PT Global Expo Management" />
    <meta name="theme-color" content="#f4f7fb" />
    <title>Visitor Registration | PT Global Expo Management</title>
    <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon">

    <script>
        (function () {
            let theme = 'light';

            try {
                const saved_theme = localStorage.getItem('theme');

                if (saved_theme === 'light' || saved_theme === 'dark') {
                    theme = saved_theme;
                } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    theme = 'dark';
                }
            } catch (error) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    theme = 'dark';
                }
            }

            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
        })();
    </script>

    <script src="{{ asset('rukada/js/jquery.min.js') }}"></script>

    <link href="{{ asset('rukada/css/icons.css') }}" rel="stylesheet" type="text/css">

    {{-- G Tag --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8RZR4YTC1K"></script>
</head>

<body class="regular-form-page">
    <div id="app"></div>

    @vite('resources/js/Form/Master/index_vip.js')
</body>

</html>
