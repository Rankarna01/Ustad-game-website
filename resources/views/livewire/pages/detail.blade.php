<?php

use Livewire\Volt\Component;
use App\Models\Account;
use App\Models\Setting;

new #[\Livewire\Attributes\Layout('layouts.app')] class extends Component
{
    public ?Account $account = null;
    public string $waLink = '';

    public function mount(int $id): void
    {
        $this->account = Account::with(['category', 'galleries'])->findOrFail($id);

        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $adminWa = $this->account->wa_number ?: ($settings['wa_number'] ?? '081234567890');

        $template = $settings['wa_message_template'] ?? "Halo admin saya tertarik membeli akun berikut:\n\nNama Akun: {title}\nGame: {category}\nHarga: {price}\nRank: {rank}\n\nApakah masih tersedia?";

        $message = str_replace(
            ['{title}', '{category}', '{price}', '{rank}'],
            [
                $this->account->title,
                $this->account->category->name ?? '',
                'Rp ' . number_format($this->account->price, 0, ',', '.'),
                $this->account->rank ?? '-',
            ],
            $template
        );

        $this->waLink = 'https://wa.me/' . $adminWa . '?text=' . urlencode($message);
    }
}; ?>

<div class="py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-secondary-text mb-8" data-aos="fade-right">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap gap-y-1">
                <li class="inline-flex items-center">
                    <a wire:navigate href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <i class='bx bx-home'></i> Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class='bx bx-chevron-right text-gray-500 mx-1'></i>
                        <a wire:navigate href="{{ route('catalog') }}" class="hover:text-primary transition-colors">Katalog</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class='bx bx-chevron-right text-gray-500 mx-1'></i>
                        <span class="text-white line-clamp-1 max-w-[200px]">{{ $account->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-card border border-gray-800 rounded-3xl p-6 lg:p-10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 relative z-10">
                <!-- Gallery Section (Left) -->
                <div x-data="{
                        activeImage: '{{ Storage::url($account->thumbnail) }}',
                        images: [
                            '{{ Storage::url($account->thumbnail) }}',
                            @foreach($account->galleries as $gallery)
                                '{{ Storage::url($gallery->image_path) }}',
                            @endforeach
                        ]
                    }"
                    data-aos="fade-right"
                >
                    <!-- Main Image -->
                    <div class="w-full h-80 sm:h-96 rounded-2xl overflow-hidden border-2 border-gray-800 mb-4 bg-background relative">
                        <img :src="activeImage" alt="Account Image" class="w-full h-full object-contain transition-all duration-300">

                        <!-- Status Badge -->
                        @if($account->status === 'ready')
                            <div class="absolute top-4 right-4 bg-success text-white font-bold px-4 py-2 rounded-full text-sm shadow-[0_0_15px_rgba(34,197,94,0.5)] flex items-center gap-1">
                                <i class='bx bx-check-circle'></i> READY
                            </div>
                        @else
                            <div class="absolute top-4 right-4 bg-danger text-white font-bold px-4 py-2 rounded-full text-sm flex items-center gap-1">
                                <i class='bx bx-x-circle'></i> SOLD OUT
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    <div class="flex space-x-3 overflow-x-auto pb-2">
                        <template x-for="image in images" :key="image">
                            <button @click="activeImage = image"
                                    class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-200"
                                    :class="activeImage === image ? 'border-primary shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'border-gray-800 hover:border-gray-600'">
                                <img :src="image" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Info Section (Right) -->
                <div data-aos="fade-left">
                    <div class="mb-5">
                        <div class="inline-flex items-center gap-2 bg-primary/10 border border-primary/30 px-3 py-1 rounded-full text-sm text-primary mb-4 font-semibold">
                            <i class='{{ $account->category->icon ?? "bx bx-category" }}'></i>
                            <span>{{ $account->category->name }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-orbitron font-bold text-white leading-tight mb-2">
                            {{ $account->title }}
                        </h1>
                        <p class="text-secondary-text text-sm flex items-center gap-1">
                            <i class='bx bx-time-five'></i>
                            Diposting {{ $account->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Price -->
                    <div class="py-5 border-y border-gray-800 mb-6">
                        <p class="text-sm text-secondary-text mb-1">Harga Akun</p>
                        <p class="text-4xl sm:text-5xl font-orbitron font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-green-300 drop-shadow-[0_0_10px_rgba(34,197,94,0.4)]">
                            Rp {{ number_format($account->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @if($account->rank)
                        <div class="bg-background border border-gray-800 rounded-xl p-4 flex items-center gap-3 hover:border-primary/30 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xl flex-shrink-0">
                                <i class='bx bx-medal'></i>
                            </div>
                            <div>
                                <p class="text-xs text-secondary-text">Rank</p>
                                <p class="font-bold text-white text-sm">{{ $account->rank }}</p>
                            </div>
                        </div>
                        @endif
                        @if($account->level)
                        <div class="bg-background border border-gray-800 rounded-xl p-4 flex items-center gap-3 hover:border-primary/30 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xl flex-shrink-0">
                                <i class='bx bx-layer'></i>
                            </div>
                            <div>
                                <p class="text-xs text-secondary-text">Level</p>
                                <p class="font-bold text-white text-sm">{{ $account->level }}</p>
                            </div>
                        </div>
                        @endif
                        @if($account->heroes)
                        <div class="bg-background border border-gray-800 rounded-xl p-4 flex items-center gap-3 hover:border-primary/30 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xl flex-shrink-0">
                                <i class='bx bxs-group'></i>
                            </div>
                            <div>
                                <p class="text-xs text-secondary-text">Heroes</p>
                                <p class="font-bold text-white text-sm">{{ $account->heroes }}</p>
                            </div>
                        </div>
                        @endif
                        @if($account->skins)
                        <div class="bg-background border border-gray-800 rounded-xl p-4 flex items-center gap-3 hover:border-primary/30 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xl flex-shrink-0">
                                <i class='bx bx-diamond'></i>
                            </div>
                            <div>
                                <p class="text-xs text-secondary-text">Skins</p>
                                <p class="font-bold text-white text-sm">{{ $account->skins }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($account->description)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-white mb-3 font-orbitron flex items-center gap-2">
                            <i class='bx bx-info-circle text-primary'></i> Detail Deskripsi
                        </h3>
                        <div class="bg-background p-5 rounded-xl border border-gray-800 text-sm text-secondary-text whitespace-pre-wrap leading-relaxed max-h-48 overflow-y-auto">{{ $account->description }}</div>
                    </div>
                    @endif

                    <!-- CTA Button -->
                    @if($account->status === 'ready')
                        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                           class="w-full py-4 bg-gradient-to-r from-primary to-green-400 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.4)] hover:shadow-[0_0_35px_rgba(34,197,94,0.7)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 text-lg">
                            <i class='bx bxl-whatsapp text-2xl'></i>
                            <span>Beli Sekarang via WhatsApp</span>
                        </a>
                        <p class="text-center text-xs text-secondary-text mt-3 flex items-center justify-center gap-1">
                            <i class='bx bx-shield-check text-primary'></i>
                            Transaksi aman 100% langsung dengan Admin
                        </p>
                    @else
                        <button disabled class="w-full py-4 bg-gray-800/80 text-gray-500 font-bold rounded-xl border border-gray-700 cursor-not-allowed flex items-center justify-center gap-3 text-lg">
                            <i class='bx bx-x-circle text-2xl'></i>
                            <span>Akun Telah Terjual</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Guarantee Badges -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10" data-aos="fade-up">
            <div class="bg-card border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/30 transition-colors">
                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary text-2xl flex items-center justify-center mx-auto mb-3"><i class='bx bx-shield'></i></div>
                <p class="font-bold text-white text-sm">100% Aman</p>
                <p class="text-xs text-secondary-text mt-1">Garansi anti hack-back</p>
            </div>
            <div class="bg-card border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/30 transition-colors">
                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary text-2xl flex items-center justify-center mx-auto mb-3"><i class='bx bx-timer'></i></div>
                <p class="font-bold text-white text-sm">Transfer Instan</p>
                <p class="text-xs text-secondary-text mt-1">Proses cepat & mudah</p>
            </div>
            <div class="bg-card border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/30 transition-colors">
                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary text-2xl flex items-center justify-center mx-auto mb-3"><i class='bx bx-support'></i></div>
                <p class="font-bold text-white text-sm">Support 24/7</p>
                <p class="text-xs text-secondary-text mt-1">Admin siap melayani</p>
            </div>
            <div class="bg-card border border-gray-800 rounded-2xl p-5 text-center hover:border-primary/30 transition-colors">
                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary text-2xl flex items-center justify-center mx-auto mb-3"><i class='bx bx-check-double'></i></div>
                <p class="font-bold text-white text-sm">Akun Terverifikasi</p>
                <p class="text-xs text-secondary-text mt-1">Dicek langsung admin</p>
            </div>
        </div>
    </div>
</div>
