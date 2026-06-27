<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            var div = document.createElement("div");
            div.style.background = "red";
            div.style.color = "white";
            div.style.padding = "20px";
            div.style.position = "fixed";
            div.style.zIndex = "999999";
            div.style.top = "0";
            div.style.left = "0";
            div.style.width = "100%";
            div.innerHTML = "<h1>JAVASCRIPT ERROR</h1><p>" + message + "</p><pre>" + (error ? error.stack : "") + "</pre>";
            document.body.appendChild(div);
        };
    </script>
        @inertia
    </body>
</html>
