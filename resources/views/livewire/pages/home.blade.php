<?php

use Livewire\Volt\Component;
use App\Models\Account;
use App\Models\Category;
use App\Models\Testimonial;

new #[\Livewire\Attributes\Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        return [
            'featuredAccounts' => Account::with('category')
                ->where('status', 'ready')
                ->inRandomOrder()
                ->take(6)
                ->get(),
            'categories' => Category::withCount(['accounts' => fn($q) => $q->where('status', 'ready')])->get(),
            'testimonials' => Testimonial::latest()->take(6)->get(),
            'totalReady' => Account::where('status', 'ready')->count(),
            'totalSold' => Account::where('status', 'sold')->count(),
        ];
    }
}; ?>

<div>
    <!-- ===== HERO SECTION ===== -->
    <section class="relative overflow-hidden min-h-[90vh] flex items-center">
        <!-- Animated Background -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-1/4 left-1/6 w-[500px] h-[500px] bg-primary/15 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/6 w-[400px] h-[400px] bg-green-700/10 rounded-full blur-[100px]" style="animation: pulse 4s ease-in-out 1.5s infinite;"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.04]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-background/60 to-background"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-20 lg:py-32">
            <div class="text-center" data-aos="fade-up">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-primary/10 border border-primary/30 px-5 py-2 rounded-full text-sm text-primary mb-8 font-semibold">
                    <span class="w-2 h-2 bg-primary rounded-full animate-ping inline-flex"></span>
                    {{ $totalReady }} Akun Ready Tersedia
                </div>

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-orbitron font-black text-white mb-6 leading-tight tracking-tight">
                    Level Up Your Game
                    <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-green-300 to-emerald-500 drop-shadow-[0_0_20px_rgba(34,197,94,0.5)]">
                        With Premium Accounts
                    </span>
                </h1>
                <p class="mt-4 text-lg md:text-xl text-secondary-text max-w-2xl mx-auto mb-10">
                    Marketplace akun game terpercaya. Transaksi aman, instan, dan 100% anti hack-back. Mobile Legends, Free Fire, dan banyak lagi.
                </p>

                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a wire:navigate href="{{ route('catalog') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.4)] hover:shadow-[0_0_40px_rgba(34,197,94,0.7)] hover:scale-105 transition-all duration-300 flex items-center gap-2 text-base">
                        <i class='bx bx-store-alt text-xl'></i>
                        Jelajahi Katalog
                    </a>
                    <a href="#tentang" class="px-8 py-4 bg-surface border border-gray-700 text-white font-bold rounded-xl hover:border-primary/50 hover:bg-gray-800/50 transition-all duration-300 flex items-center gap-2">
                        <i class='bx bx-info-circle text-xl text-primary'></i>
                        Kenapa Kami?
                    </a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-20" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-card/60 backdrop-blur-sm border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/50 transition-all group">
                    <p class="text-3xl font-orbitron font-black text-primary group-hover:scale-110 transition-transform inline-block">{{ $totalReady }}+</p>
                    <p class="text-sm text-secondary-text mt-1">Akun Ready</p>
                </div>
                <div class="bg-card/60 backdrop-blur-sm border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/50 transition-all group">
                    <p class="text-3xl font-orbitron font-black text-primary group-hover:scale-110 transition-transform inline-block">{{ $totalSold }}+</p>
                    <p class="text-sm text-secondary-text mt-1">Akun Terjual</p>
                </div>
                <div class="bg-card/60 backdrop-blur-sm border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/50 transition-all group">
                    <p class="text-3xl font-orbitron font-black text-primary group-hover:scale-110 transition-transform inline-block">100%</p>
                    <p class="text-sm text-secondary-text mt-1">Garansi Aman</p>
                </div>
                <div class="bg-card/60 backdrop-blur-sm border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/50 transition-all group">
                    <p class="text-3xl font-orbitron font-black text-primary group-hover:scale-110 transition-transform inline-block">24/7</p>
                    <p class="text-sm text-secondary-text mt-1">Live Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CATEGORIES SECTION ===== -->
    @if($categories->count() > 0)
    <section class="py-16 bg-surface/30 border-t border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-aos="fade-up">
                <h2 class="text-2xl md:text-3xl font-orbitron font-bold text-white mb-3">Game Tersedia</h2>
                <div class="w-16 h-1 bg-primary mx-auto rounded-full shadow-neon-green"></div>
            </div>
            <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="100">
                @foreach($categories as $cat)
                <a wire:navigate href="{{ route('catalog') }}?category_id={{ $cat->id }}"
                   class="flex items-center gap-3 bg-card border border-gray-800 px-6 py-3 rounded-full hover:border-primary hover:bg-primary/10 hover:text-primary transition-all group">
                    <i class='{{ $cat->icon ?? "bx bx-game" }} text-xl text-primary group-hover:scale-125 transition-transform'></i>
                    <span class="font-semibold text-white group-hover:text-primary text-sm">{{ $cat->name }}</span>
                    <span class="bg-primary/20 text-primary text-xs px-2 py-0.5 rounded-full">{{ $cat->accounts_count }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ===== FEATURED ACCOUNTS ===== -->
    <section id="katalog" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-14" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl md:text-4xl font-orbitron font-bold text-white mb-3">Akun Rekomendasi</h2>
                    <div class="w-20 h-1 bg-primary rounded-full shadow-neon-green"></div>
                </div>
                <a wire:navigate href="{{ route('catalog') }}" class="mt-4 sm:mt-0 flex items-center gap-2 text-primary hover:text-green-300 font-semibold transition-colors text-sm border border-primary/30 hover:border-primary px-4 py-2 rounded-xl hover:bg-primary/10">
                    Lihat Semua <i class='bx bx-right-arrow-alt text-xl'></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($featuredAccounts as $account)
                <a wire:navigate href="{{ route('account.detail', $account->id) }}"
                   class="bg-card border border-gray-800 rounded-2xl overflow-hidden group hover:border-primary/60 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_10px_40px_rgba(34,197,94,0.15)] relative block"
                   data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <!-- Hover Glow -->
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0 rounded-2xl"></div>

                    <!-- Image -->
                    <div class="relative h-52 overflow-hidden z-10">
                        <img src="{{ Storage::url($account->thumbnail) }}" alt="{{ $account->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <!-- Category Badge -->
                        <div class="absolute top-3 left-3 bg-background/80 backdrop-blur-md border border-gray-700 px-3 py-1 rounded-full text-xs font-semibold text-primary">
                            <i class='{{ $account->category->icon ?? "bx bx-game" }} mr-1'></i>{{ $account->category->name }}
                        </div>
                        <!-- Status badge -->
                        <div class="absolute top-3 right-3 bg-success/90 text-white text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span> READY
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 relative z-10">
                        <h3 class="text-base font-bold text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">{{ $account->title }}</h3>

                        <div class="flex items-center gap-4 mb-4 text-xs text-secondary-text">
                            @if($account->rank)
                            <div class="flex items-center gap-1">
                                <i class='bx bx-medal text-primary'></i>
                                <span>{{ $account->rank }}</span>
                            </div>
                            @endif
                            @if($account->level)
                            <div class="flex items-center gap-1">
                                <i class='bx bx-layer text-primary'></i>
                                <span>Lv. {{ $account->level }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                            <div>
                                <p class="text-xs text-secondary-text">Harga</p>
                                <p class="text-base font-bold text-white font-orbitron">Rp {{ number_format($account->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-primary/10 border border-primary text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                <i class='bx bx-chevron-right text-xl'></i>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-3 text-center py-20">
                    <div class="w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center mx-auto mb-4 text-3xl">
                        <i class='bx bx-ghost text-gray-600'></i>
                    </div>
                    <p class="text-secondary-text">Belum ada akun rekomendasi saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ===== WHY US SECTION ===== -->
    <section id="tentang" class="py-20 bg-surface/30 border-t border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-orbitron font-bold text-white mb-4">Kenapa Pilih Kami?</h2>
                <p class="text-secondary-text max-w-xl mx-auto">Kami tidak hanya menjual akun, kami memberikan pengalaman belanja game yang aman dan menyenangkan.</p>
                <div class="w-20 h-1 bg-primary mx-auto rounded-full shadow-neon-green mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['icon' => 'bx bx-shield', 'title' => '100% Anti Hack-Back', 'desc' => 'Setiap akun yang kami jual telah melalui verifikasi keamanan ketat. Kami menjamin keamanan akun Anda setelah transfer.'],
                        ['icon' => 'bx bx-timer', 'title' => 'Transfer Instan', 'desc' => 'Proses transfer akun dilakukan langsung oleh admin berpengalaman. Cepat, mudah, dan tanpa ribet.'],
                        ['icon' => 'bx bxl-whatsapp', 'title' => 'Transaksi via WhatsApp', 'desc' => 'Hubungi admin langsung melalui WhatsApp untuk proses pembelian yang mudah dan transparan.'],
                        ['icon' => 'bx bx-money-withdraw', 'title' => 'Harga Terjangkau', 'desc' => 'Kami menawarkan harga kompetitif untuk berbagai akun premium dari game populer favoritmu.'],
                        ['icon' => 'bx bx-support', 'title' => 'Support 24/7', 'desc' => 'Tim admin kami siap membantu Anda kapan saja. Tidak ada pertanyaan yang terlalu kecil untuk kami.'],
                        ['icon' => 'bx bx-check-double', 'title' => 'Akun Terverifikasi', 'desc' => 'Setiap akun dicek langsung oleh admin untuk memastikan kesesuaian data dengan deskripsi yang tertera.'],
                    ];
                @endphp

                @foreach($features as $i => $feature)
                <div class="bg-card border border-gray-800 rounded-2xl p-6 hover:border-primary/50 transition-all duration-300 hover:-translate-y-1 group" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-3xl mb-5 shadow-[0_0_15px_rgba(34,197,94,0.2)] group-hover:shadow-neon-green transition-all">
                        <i class='{{ $feature["icon"] }}'></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2 group-hover:text-primary transition-colors">{{ $feature['title'] }}</h3>
                    <p class="text-secondary-text text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONIALS SECTION (Marquee) ===== -->
    @if($testimonials->count() > 0)
    <section class="py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-orbitron font-bold text-white mb-4">Kata Mereka</h2>
                <div class="w-20 h-1 bg-primary mx-auto rounded-full shadow-neon-green"></div>
                <p class="text-secondary-text mt-4">Bukti transaksi sukses dari pelanggan setia kami.</p>
            </div>
            
            <style>
                .marquee-container {
                    overflow: hidden;
                    width: 100%;
                    position: relative;
                }
                .marquee-content {
                    display: flex;
                    gap: 1.5rem;
                    width: max-content;
                    animation: marquee 30s linear infinite;
                }
                .marquee-content:hover {
                    animation-play-state: paused;
                }
                @keyframes marquee {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-50%); }
                }
            </style>

            <div class="marquee-container" data-aos="fade-up" data-aos-delay="100">
                <div class="marquee-content">
                    <!-- Original items -->
                    @foreach($testimonials as $t)
                    <div class="w-[280px] md:w-[320px] flex-shrink-0 bg-card border border-gray-800 rounded-2xl p-4 hover:border-primary/50 transition-all cursor-pointer group shadow-lg">
                        @if($t->image)
                            <div class="w-full h-[400px] overflow-hidden rounded-xl mb-4 bg-background">
                                <img src="{{ asset('storage/' . $t->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Testimoni">
                            </div>
                        @else
                            <div class="w-full h-[400px] rounded-xl mb-4 bg-background flex items-center justify-center border border-gray-800">
                                <i class='bx bx-image-alt text-4xl text-gray-700'></i>
                            </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/20 border border-primary/30 flex items-center justify-center font-bold text-primary text-sm shrink-0">
                                {{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-semibold text-white text-sm truncate">{{ $t->name }}</p>
                                @if($t->message)
                                    <p class="text-xs text-secondary-text truncate italic">"{{ $t->message }}"</p>
                                @else
                                    <div class="flex text-yellow-400 text-xs"><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Duplicated items for seamless loop -->
                    @foreach($testimonials as $t)
                    <div class="w-[280px] md:w-[320px] flex-shrink-0 bg-card border border-gray-800 rounded-2xl p-4 hover:border-primary/50 transition-all cursor-pointer group shadow-lg">
                        @if($t->image)
                            <div class="w-full h-[400px] overflow-hidden rounded-xl mb-4 bg-background">
                                <img src="{{ asset('storage/' . $t->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Testimoni">
                            </div>
                        @else
                            <div class="w-full h-[400px] rounded-xl mb-4 bg-background flex items-center justify-center border border-gray-800">
                                <i class='bx bx-image-alt text-4xl text-gray-700'></i>
                            </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/20 border border-primary/30 flex items-center justify-center font-bold text-primary text-sm shrink-0">
                                {{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-semibold text-white text-sm truncate">{{ $t->name }}</p>
                                @if($t->message)
                                    <p class="text-xs text-secondary-text truncate italic">"{{ $t->message }}"</p>
                                @else
                                    <div class="flex text-yellow-400 text-xs"><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="200">
                <a wire:navigate href="{{ route('testimonials') }}" class="inline-flex items-center gap-2 text-white font-medium bg-surface border border-gray-700 hover:border-primary hover:bg-primary/10 px-6 py-3 rounded-full transition-all text-sm">
                    Lihat Semua Testimoni <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- ===== CTA SECTION ===== -->
    <section class="py-20 bg-surface/30 border-t border-gray-800/50">
        <div class="max-w-4xl mx-auto px-4 text-center" data-aos="fade-up">
            <div class="bg-gradient-to-br from-primary/10 to-green-900/10 border border-primary/30 rounded-3xl p-10 lg:p-16 relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-orbitron font-bold text-white mb-4">Siap Level Up?</h2>
                    <p class="text-secondary-text mb-8 text-lg">Temukan akun impian Anda sekarang. Ribuan pilihan menanti dengan harga terbaik.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a wire:navigate href="{{ route('catalog') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-neon-green hover:shadow-[0_0_40px_rgba(34,197,94,0.7)] hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class='bx bx-store-alt text-xl'></i> Lihat Katalog Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp FAB -->
    <a href="#" class="fixed bottom-6 right-6 w-14 h-14 bg-[#25D366] text-white rounded-full flex items-center justify-center text-3xl shadow-[0_0_20px_rgba(37,211,102,0.5)] hover:scale-110 hover:shadow-[0_0_35px_rgba(37,211,102,0.8)] transition-all z-50" title="Hubungi Admin">
        <i class='bx bxl-whatsapp'></i>
    </a>
</div>
