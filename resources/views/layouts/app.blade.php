<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ustazz.id GameStore') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <!-- AOS Animation -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
        <!-- Scripts -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-background text-secondary-text min-h-screen flex flex-col selection:bg-primary selection:text-white scroll-smooth">


        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-surface border-b border-gray-800/50 shadow-sm mt-16 lg:mt-0">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow pt-16 lg:pt-0">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-surface border-t border-gray-800 mt-12 relative overflow-hidden">
            <!-- Glow decoration -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-1 bg-gradient-to-r from-transparent via-primary to-transparent opacity-60"></div>
            <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                    <!-- Brand Column -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center gap-2 mb-5">
                            <span class="text-2xl font-orbitron font-black text-white">USTAZZ<span class="text-primary">.ID</span></span>
                        </div>
                        <p class="text-secondary-text text-sm leading-relaxed mb-6 max-w-sm">
                            Marketplace jual beli akun game terpercaya di Indonesia. Transaksi aman, proses instan, dan garansi 100% anti hack-back.
                        </p>
                        <!-- Social Links -->
                        <div class="flex items-center gap-3">
                            <a href="#" class="w-10 h-10 rounded-xl bg-card border border-gray-800 flex items-center justify-center text-secondary-text hover:text-primary hover:border-primary transition-all">
                                <i class='bx bxl-instagram text-xl'></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-card border border-gray-800 flex items-center justify-center text-secondary-text hover:text-primary hover:border-primary transition-all">
                                <i class='bx bxl-tiktok text-xl'></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-card border border-gray-800 flex items-center justify-center text-secondary-text hover:text-[#25D366] hover:border-[#25D366] transition-all">
                                <i class='bx bxl-whatsapp text-xl'></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-bold mb-5 font-orbitron text-sm tracking-wider uppercase">Navigasi</h4>
                        <ul class="space-y-3">
                            <li><a wire:navigate href="{{ route('home') }}" class="text-secondary-text hover:text-primary transition-colors text-sm flex items-center gap-2"><i class='bx bx-chevron-right text-primary'></i> Home</a></li>
                            <li><a wire:navigate href="{{ route('catalog') }}" class="text-secondary-text hover:text-primary transition-colors text-sm flex items-center gap-2"><i class='bx bx-chevron-right text-primary'></i> Katalog Akun</a></li>
                            @if(Route::has('about'))
                            <li><a wire:navigate href="{{ route('about') }}" class="text-secondary-text hover:text-primary transition-colors text-sm flex items-center gap-2"><i class='bx bx-chevron-right text-primary'></i> Tentang Kami</a></li>
                            @endif
                            @if(Route::has('contact'))
                            <li><a wire:navigate href="{{ route('contact') }}" class="text-secondary-text hover:text-primary transition-colors text-sm flex items-center gap-2"><i class='bx bx-chevron-right text-primary'></i> Kontak</a></li>
                            @endif
                        </ul>
                    </div>

                    <!-- Info -->
                    <div>
                        <h4 class="text-white font-bold mb-5 font-orbitron text-sm tracking-wider uppercase">Informasi</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm text-secondary-text">
                                <i class='bx bx-map-pin text-primary mt-0.5 flex-shrink-0'></i>
                                <span>Indonesia</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-secondary-text">
                                <i class='bx bx-time text-primary mt-0.5 flex-shrink-0'></i>
                                <span>Layanan 24/7</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-secondary-text">
                                <i class='bx bxl-whatsapp text-primary mt-0.5 flex-shrink-0'></i>
                                <span>WhatsApp Admin</span>
                            </li>
                        </ul>
                        
                        <!-- Trust Badges -->
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="bg-primary/10 border border-primary/20 text-primary text-xs px-3 py-1 rounded-full font-semibold">100% Aman</span>
                            <span class="bg-primary/10 border border-primary/20 text-primary text-xs px-3 py-1 rounded-full font-semibold">Anti Hack</span>
                            <span class="bg-primary/10 border border-primary/20 text-primary text-xs px-3 py-1 rounded-full font-semibold">Garansi</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-secondary-text">
                        &copy; {{ date('Y') }} <span class="text-primary font-semibold">Ustazz.id GameStore</span>. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-600">
                        Powered by <span class="text-gray-500">Laravel & Livewire</span>
                    </p>
                </div>
            </div>
        </footer>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- AOS Animation JS -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener('livewire:navigated', () => {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        once: true,
                        offset: 50,
                        duration: 600,
                        easing: 'ease-out-cubic',
                    });
                }
            });
            
            // Listen for Livewire SweetAlert events
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('toast', (event) => {
                    let evt = (Array.isArray(event) && event.length > 0) ? event[0] : event;
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#1f1f1f',
                        color: '#ffffff',
                    });

                    Toast.fire({
                        icon: evt.type || 'success',
                        title: evt.message || 'Berhasil'
                    });
                });
                
                Livewire.on('swal', (event) => {
                    let evt = (Array.isArray(event) && event.length > 0) ? event[0] : event;
                    Swal.fire({
                        title: evt.title,
                        text: evt.text,
                        icon: evt.icon
                    });
                });
            });
        </script>
        @livewireScripts
    </body>
</html>
