<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Poppins', 'sans-serif'],
                            orbitron: ['Orbitron', 'sans-serif'],
                        },
                        colors: {
                            primary: '#22c55e',
                            background: '#0f0f0f',
                            surface: '#181818',
                            card: '#1f1f1f',
                            'secondary-text': '#bdbdbd',
                            danger: '#ef4444',
                            success: '#22c55e',
                        },
                        boxShadow: {
                            'neon-green': '0 0 10px #22c55e, 0 0 20px #22c55e',
                        },
                    }
                }
            }
        </script>
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-background text-secondary-text min-h-screen flex flex-col selection:bg-primary selection:text-white">
        <!-- Floating shapes -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-primary/5 rounded-full blur-[100px]"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div class="mb-8 text-center" data-aos="fade-down">
                <a href="{{ route('home') }}" wire:navigate class="font-orbitron font-bold text-4xl text-primary tracking-wider drop-shadow-[0_0_15px_rgba(34,197,94,0.8)]">
                    USTAZZ.ID
                </a>
                <p class="text-secondary-text mt-2 text-sm">Masuk ke panel untuk mengelola toko</p>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-card/80 backdrop-blur-xl border border-gray-800 shadow-[0_0_40px_rgba(0,0,0,0.5)] sm:rounded-3xl relative" data-aos="fade-up">
                <!-- Inner glow top -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-primary/50 to-transparent"></div>
                
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
</html>
