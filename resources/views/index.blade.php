<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Alumni</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-white text-[#1b1b18] flex flex-col items-center justify-center min-h-screen p-6">

        <!-- Header with Login/Register Links -->
        <header class="w-full max-w-4xl text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="px-5 py-1.5 text-[#1b1b18] dark:text-[#EDEDEC] border border-[#19140035] hover:border-[#1915014a] rounded-sm text-sm"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="px-5 py-1.5 text-[#1b1b18] dark:text-[#EDEDEC] border border-transparent hover:border-[#19140035] rounded-sm text-sm"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="px-5 py-1.5 text-[#1b1b18] dark:text-[#EDEDEC] border border-[#19140035] hover:border-[#1915014a] rounded-sm text-sm"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Welcome Button Section -->
        <main class="text-center">
            <h1 class="text-4xl font-bold mb-6">Welcome to Alumni Portal</h1>
         

           @if (Route::has('register'))

        <a href="{{ route('register') }}"
           class="bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 
                  text-white text-2xl font-semibold px-8 py-4 rounded-lg shadow-lg transition transform hover:scale-105">
            Go to Register
        </a>
    </div>
@endif

        </main>

    </body>
</html>
