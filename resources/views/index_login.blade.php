<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>Visitor Registration</title>

    <script>
        (function () {
            var theme = null;

            try {
                theme = localStorage.getItem('theme');
            } catch (error) {
                theme = null;
            }

            if (theme !== 'light' && theme !== 'dark') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <script src="{{ asset('rukada/js/jquery.min.js') }}"></script>

    {{-- Css --}}
    <link href="{{ asset('rukada/css/icons.css') }}" rel="stylesheet" type="text/css">
</head>

<body class="login-page">
    <div id="app">
    </div>

    @vite('resources/js/Master/index_login.js')
</body>

</html>
