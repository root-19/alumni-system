<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alumni</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-[#1b1b18] flex flex-col items-center justify-center min-h-screen p-6">

<main class="flex flex-col items-center justify-center text-center px-4 space-y-8">
    <!-- Logo Image -->
    <img src="{{ asset('image/logo.png') }}" 
         alt="Logo"   
         class="mx-auto w-full max-w-[320px] h-auto object-contain">

    <!-- Title -->
    <h1 class="text-4xl md:text-5xl font-bold text-gray-800">Welcome to Alumni Portal</h1>

    <!-- Register Button -->
    @if (Route::has('register'))
        <a href="{{ route('register') }}"
           class="bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 
                  text-white text-xl md:text-2xl font-semibold px-8 py-4 rounded-lg shadow-lg transition transform hover:scale-105">
            Go to Register
        </a>
    @endif
</main>

</body>
</html>
