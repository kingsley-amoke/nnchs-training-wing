<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NNCHS Training Wing</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">


    {{-- header start --}}


    <header class="bg-white dark:bg-black fixed top-0 w-full shadow-md">

    </header>


    {{-- header ends --}}

    <main class="h-screen flex flex-col gap-10 justify-center items-center">
        <h1 class="text-5xl">NNCHS</h1>
        <a href="/admin"
            class="bg-teal-500 hover:bg-teal-700 py-5 px-9 rounded-md text-white text-2xl font-bold">Login</a>

    </main>


    {{-- footer start --}}
    <footer class="py-16 text-center text-sm text-black dark:text-white/70">

    </footer>

    {{-- footer end --}}

</body>

</html>
