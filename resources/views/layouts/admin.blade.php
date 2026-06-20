<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ustazz.id Admin') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
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
    <body class="font-sans antialiased bg-background text-secondary-text h-screen flex overflow-hidden selection:bg-primary selection:text-white" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/80 z-40 lg:hidden backdrop-blur-sm" @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar -->
        <aside class="bg-surface w-64 border-r border-gray-800 flex-shrink-0 transition-all duration-300 z-50 fixed lg:relative inset-y-0 left-0 h-screen" :class="{ '-translate-x-full lg:translate-x-0 lg:-ml-64': !sidebarOpen }">
            <div class="h-16 flex items-center justify-center border-b border-gray-800">
                <a href="{{ route('admin.dashboard') ?? '#' }}" class="font-orbitron font-bold text-2xl text-primary tracking-wider drop-shadow-[0_0_10px_rgba(34,197,94,0.8)]">USTAZZ</a>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-white bg-primary/20 border-primary/50 shadow-neon-green' : 'text-secondary-text hover:text-white hover:bg-gray-800 border-transparent' }} flex items-center space-x-3 p-3 rounded-xl transition-all border">
                    <i class='bx bxs-dashboard text-xl'></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.accounts') }}" class="{{ request()->routeIs('admin.accounts') ? 'text-white bg-primary/20 border-primary/50 shadow-neon-green' : 'text-secondary-text hover:text-white hover:bg-gray-800 border-transparent' }} flex items-center space-x-3 p-3 rounded-xl transition-all border">
                    <i class='bx bx-user-pin text-xl'></i>
                    <span class="font-medium">Kelola Akun</span>
                </a>
                <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories') ? 'text-white bg-primary/20 border-primary/50 shadow-neon-green' : 'text-secondary-text hover:text-white hover:bg-gray-800 border-transparent' }} flex items-center space-x-3 p-3 rounded-xl transition-all border">
                    <i class='bx bx-category text-xl'></i>
                    <span class="font-medium">Kategori</span>
                </a>
                <a href="{{ route('admin.testimonials') }}" class="{{ request()->routeIs('admin.testimonials') ? 'text-white bg-primary/20 border-primary/50 shadow-neon-green' : 'text-secondary-text hover:text-white hover:bg-gray-800 border-transparent' }} flex items-center space-x-3 p-3 rounded-xl transition-all border">
                    <i class='bx bx-message-square-detail text-xl'></i>
                    <span class="font-medium">Testimoni</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'text-white bg-primary/20 border-primary/50 shadow-neon-green' : 'text-secondary-text hover:text-white hover:bg-gray-800 border-transparent' }} flex items-center space-x-3 p-3 rounded-xl transition-all border">
                    <i class='bx bx-cog text-xl'></i>
                    <span class="font-medium">Pengaturan</span>
                </a>
                
                <div class="pt-8">
                    @volt('admin-logout')
                    <?php
                    $logout = function (\App\Livewire\Actions\Logout $logoutAction) {
                        $logoutAction();
                        $this->redirect('/', navigate: true);
                    };
                    ?>
                    <button wire:click="logout" class="w-full flex items-center space-x-3 text-danger hover:bg-danger/10 p-3 rounded-xl transition-all border border-transparent hover:border-danger/30">
                        <i class='bx bx-log-out text-xl'></i>
                        <span class="font-medium">Logout</span>
                    </button>
                    @endvolt
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 bg-background overflow-y-auto">
            <!-- Topbar -->
            <header class="h-16 bg-surface border-b border-gray-800 flex items-center justify-between px-6 sticky top-0 z-30">
                <button @click="sidebarOpen = !sidebarOpen" class="text-secondary-text hover:text-primary transition-colors focus:outline-none">
                    <i class='bx bx-menu text-3xl'></i>
                </button>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-primary">Administrator</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-primary/20 border border-primary flex items-center justify-center text-primary shadow-neon-green">
                        <i class='bx bxs-user'></i>
                    </div>
                </div>
            </header>

            <!-- Content Slot -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
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
                        icon: evt.icon,
                        background: '#1f1f1f',
                        color: '#ffffff',
                        confirmButtonColor: '#22c55e',
                        cancelButtonColor: '#ef4444',
                    });
                });
            });
        </script>
        @livewireScripts
    </body>
</html>
