<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="fixed top-0 inset-x-0 z-40 bg-background/80 backdrop-blur-lg border-b border-gray-800 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="font-orbitron font-bold text-2xl text-primary tracking-wider drop-shadow-[0_0_10px_rgba(34,197,94,0.8)]" wire:navigate>
                    USTADZGAMERS.MY.ID
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex space-x-6">
                    <a href="{{ route('home') }}" wire:navigate class="{{ request()->routeIs('home') ? 'text-primary border-primary' : 'text-secondary-text border-transparent hover:text-white hover:border-gray-600' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('catalog') }}" wire:navigate class="{{ request()->routeIs('catalog') ? 'text-primary border-primary' : 'text-secondary-text border-transparent hover:text-white hover:border-gray-600' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                        Katalog
                    </a>
                    <a href="{{ route('about') }}" wire:navigate class="{{ request()->routeIs('about') ? 'text-primary border-primary' : 'text-secondary-text border-transparent hover:text-white hover:border-gray-600' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                        Tentang Kami
                    </a>
                    <a href="{{ route('testimonials') }}" wire:navigate class="{{ request()->routeIs('testimonials') ? 'text-primary border-primary' : 'text-secondary-text border-transparent hover:text-white hover:border-gray-600' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                        Testimoni
                    </a>
                    <a href="{{ route('contact') }}" wire:navigate class="{{ request()->routeIs('contact') ? 'text-primary border-primary' : 'text-secondary-text border-transparent hover:text-white hover:border-gray-600' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                        Kontak
                    </a>
                </div>
            </div>

            <!-- Right Side Auth / Actions -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-primary/10 text-primary border border-primary/50 rounded-lg hover:bg-primary hover:text-white transition-colors text-sm font-bold shadow-[0_0_10px_rgba(34,197,94,0.2)]">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium border border-gray-700">
                            Dashboard Member
                        </a>
                    @endif
                    <button wire:click="logout" class="text-secondary-text hover:text-danger p-2 transition-colors" title="Logout">
                        <i class='bx bx-log-out text-xl'></i>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="text-secondary-text hover:text-white transition-colors text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-bold shadow-[0_0_10px_rgba(34,197,94,0.3)]">Register</a>
                @endauth
            </div>

            <!-- Hamburger menu -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-secondary-text hover:text-white focus:outline-none">
                    <i class='bx' :class="open ? 'bx-x' : 'bx-menu'" class="text-3xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-surface border-b border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" wire:navigate class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-secondary-text hover:text-white hover:bg-gray-800' }} text-base font-medium">Home</a>
            <a href="{{ route('catalog') }}" wire:navigate class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('catalog') ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-secondary-text hover:text-white hover:bg-gray-800' }} text-base font-medium">Katalog</a>
            <a href="{{ route('about') }}" wire:navigate class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('about') ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-secondary-text hover:text-white hover:bg-gray-800' }} text-base font-medium">Tentang Kami</a>
            <a href="{{ route('testimonials') }}" wire:navigate class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('testimonials') ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-secondary-text hover:text-white hover:bg-gray-800' }} text-base font-medium">Testimoni</a>
            <a href="{{ route('contact') }}" wire:navigate class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('contact') ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-secondary-text hover:text-white hover:bg-gray-800' }} text-base font-medium">Kontak</a>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-800">
            @auth
                <div class="px-4 flex items-center justify-between">
                    <div>
                        <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-secondary-text">{{ auth()->user()->email }}</div>
                    </div>
                    <button wire:click="logout" class="text-danger p-2">
                        <i class='bx bx-log-out text-xl'></i>
                    </button>
                </div>
                <div class="mt-3 space-y-1 px-2">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-primary hover:text-white hover:bg-gray-800">Admin Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-text hover:text-white hover:bg-gray-800">Dashboard</a>
                    @endif
                </div>
            @else
                <div class="px-4 flex flex-col space-y-2">
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 border border-gray-700 text-white rounded-lg hover:bg-gray-800">Login</a>
                    <a href="{{ route('register') }}" class="w-full text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 shadow-[0_0_10px_rgba(34,197,94,0.3)]">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
