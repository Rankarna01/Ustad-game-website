<?php

use function Livewire\Volt\{layout};

layout('layouts.app');

?>

<div class="py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-orbitron font-bold text-white mb-4">Tentang Kami</h1>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full shadow-neon-green"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="relative" data-aos="fade-right">
                <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full w-3/4 h-3/4 mx-auto mix-blend-screen pointer-events-none"></div>
                <div class="relative bg-card border border-gray-800 p-2 rounded-3xl overflow-hidden shadow-2xl z-10">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Gaming Setup" class="w-full h-[400px] object-cover rounded-2xl opacity-80 hover:opacity-100 transition-opacity duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
                </div>
                
                <!-- Floating badge -->
                <div class="absolute -bottom-6 -right-6 bg-surface border border-gray-700 p-6 rounded-2xl shadow-neon-green z-20 animate-bounce" style="animation-duration: 3s;">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary text-2xl">
                            <i class='bx bxs-check-shield'></i>
                        </div>
                        <div>
                            <p class="font-bold text-white text-xl">100%</p>
                            <p class="text-xs text-secondary-text">Terpercaya & Aman</p>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-left">
                <h2 class="text-3xl font-orbitron font-bold text-white mb-6">Partner Terbaik Kebutuhan Gaming Anda</h2>
                <div class="space-y-6 text-secondary-text leading-relaxed">
                    <p>
                        <strong>USTADZGAMERS.MY.ID GameStore Marketplace</strong> adalah platform jual beli akun game premium terpercaya di Indonesia. Berdiri sejak tahun 2021, kami telah melayani lebih dari puluhan ribu gamers yang mencari akun idaman mereka.
                    </p>
                    <p>
                        Kami memahami bahwa keamanan adalah prioritas utama dalam transaksi akun digital. Oleh karena itu, kami menerapkan sistem verifikasi ketat pada setiap akun yang dijual untuk memastikan 100% aman dan bebas dari masalah hack-back di kemudian hari.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-6 mt-10">
                    <div class="bg-card border border-gray-800 p-6 rounded-2xl group hover:border-primary/50 transition-colors">
                        <i class='bx bx-rocket text-4xl text-primary mb-3 group-hover:scale-110 transition-transform'></i>
                        <h4 class="text-xl font-bold text-white mb-1">Cepat</h4>
                        <p class="text-sm text-secondary-text">Transaksi instan dan responsif via WhatsApp</p>
                    </div>
                    <div class="bg-card border border-gray-800 p-6 rounded-2xl group hover:border-primary/50 transition-colors">
                        <i class='bx bx-support text-4xl text-primary mb-3 group-hover:scale-110 transition-transform'></i>
                        <h4 class="text-xl font-bold text-white mb-1">Support</h4>
                        <p class="text-sm text-secondary-text">Tim admin siap membantu proses kendala Anda</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
