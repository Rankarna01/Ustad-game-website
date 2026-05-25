<?php

use function Livewire\Volt\{layout};

layout('layouts.app');

?>

<div class="py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-orbitron font-bold text-white mb-4">Hubungi Kami</h1>
            <p class="text-secondary-text max-w-2xl mx-auto">Ada pertanyaan atau butuh bantuan? Jangan ragu untuk menghubungi tim support kami melalui saluran di bawah ini.</p>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full shadow-neon-green mt-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            
            <a href="#" class="bg-card border border-gray-800 p-8 rounded-3xl text-center group hover:border-[#25D366] hover:bg-[#25D366]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-[#25D366]/10 text-[#25D366] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(37,211,102,0.2)]">
                    <i class='bx bxl-whatsapp'></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">WhatsApp</h3>
                <p class="text-sm text-secondary-text">Respon Cepat (08:00 - 22:00)</p>
            </a>

            <a href="#" class="bg-card border border-gray-800 p-8 rounded-3xl text-center group hover:border-[#E1306C] hover:bg-[#E1306C]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-[#E1306C]/10 text-[#E1306C] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(225,48,108,0.2)]">
                    <i class='bx bxl-instagram'></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Instagram</h3>
                <p class="text-sm text-secondary-text">@ustazz.id</p>
            </a>

            <a href="#" class="bg-card border border-gray-800 p-8 rounded-3xl text-center group hover:border-[#00f2fe] hover:bg-[#00f2fe]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 bg-[#00f2fe]/10 text-[#00f2fe] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(0,242,254,0.2)]">
                    <i class='bx bxl-tiktok'></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">TikTok</h3>
                <p class="text-sm text-secondary-text">@ustazz.gamestore</p>
            </a>

            <a href="#" class="bg-card border border-gray-800 p-8 rounded-3xl text-center group hover:border-[#0088cc] hover:bg-[#0088cc]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                <div class="w-16 h-16 bg-[#0088cc]/10 text-[#0088cc] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(0,136,204,0.2)]">
                    <i class='bx bxl-telegram'></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Telegram</h3>
                <p class="text-sm text-secondary-text">Grup Komunitas</p>
            </a>

        </div>

        <!-- FAQ Section -->
        <div class="max-w-3xl mx-auto mt-24">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-orbitron font-bold text-white mb-4">FAQ</h2>
                <p class="text-secondary-text">Pertanyaan yang sering diajukan</p>
            </div>

            <div class="space-y-4" data-aos="fade-up" x-data="{ activeAccordion: null }">
                
                <div class="bg-card border border-gray-800 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion === 1 ? activeAccordion = null : activeAccordion = 1" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-800/50 transition-colors">
                        <span class="font-bold text-white">Apakah akun yang dijual aman?</span>
                        <i class='bx bx-chevron-down text-xl text-primary transition-transform duration-300' :class="{ 'rotate-180': activeAccordion === 1 }"></i>
                    </button>
                    <div x-show="activeAccordion === 1" x-collapse>
                        <div class="px-6 pb-4 text-secondary-text text-sm">
                            Ya, 100% aman. Setiap akun yang kami jual telah melewati proses verifikasi dan pembersihan data dari pemilik sebelumnya untuk menghindari hack-back.
                        </div>
                    </div>
                </div>

                <div class="bg-card border border-gray-800 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion === 2 ? activeAccordion = null : activeAccordion = 2" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-800/50 transition-colors">
                        <span class="font-bold text-white">Bagaimana sistem pembayarannya?</span>
                        <i class='bx bx-chevron-down text-xl text-primary transition-transform duration-300' :class="{ 'rotate-180': activeAccordion === 2 }"></i>
                    </button>
                    <div x-show="activeAccordion === 2" x-collapse>
                        <div class="px-6 pb-4 text-secondary-text text-sm">
                            Pembayaran dapat dilakukan melalui transfer Bank (BCA, Mandiri, BNI, BRI) dan E-Wallet (Dana, OVO, Gopay, ShopeePay) langsung ke rekening resmi Ustazz.id.
                        </div>
                    </div>
                </div>

                <div class="bg-card border border-gray-800 rounded-2xl overflow-hidden">
                    <button @click="activeAccordion === 3 ? activeAccordion = null : activeAccordion = 3" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none hover:bg-gray-800/50 transition-colors">
                        <span class="font-bold text-white">Berapa lama proses transaksi?</span>
                        <i class='bx bx-chevron-down text-xl text-primary transition-transform duration-300' :class="{ 'rotate-180': activeAccordion === 3 }"></i>
                    </button>
                    <div x-show="activeAccordion === 3" x-collapse>
                        <div class="px-6 pb-4 text-secondary-text text-sm">
                            Proses penyerahan data akun sangat cepat, biasanya memakan waktu 5-15 menit setelah pembayaran Anda terverifikasi oleh admin.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
