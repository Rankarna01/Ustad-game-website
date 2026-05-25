<?php

use function Livewire\Volt\{state, layout, usesPagination, with};
use App\Models\Account;
use App\Models\Category;

usesPagination();
layout('layouts.app');

state([
    'search' => '',
    'category_id' => '',
    'sort' => 'latest',
]);

with(function () {
    $query = Account::with('category')->where('status', 'ready');

    if ($this->search) {
        $query->where('title', 'like', '%' . $this->search . '%');
    }

    if ($this->category_id) {
        $query->where('category_id', $this->category_id);
    }

    switch ($this->sort) {
        case 'lowest_price':
            $query->orderBy('price', 'asc');
            break;
        case 'highest_price':
            $query->orderBy('price', 'desc');
            break;
        default:
            $query->latest();
    }

    return [
        'accounts' => $query->paginate(12),
        'categories' => Category::orderBy('name')->get()
    ];
});

?>

<div class="py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-orbitron font-bold text-white mb-4">Katalog Akun</h1>
            <p class="text-secondary-text max-w-2xl mx-auto">Temukan akun impian Anda dari berbagai pilihan game populer dengan harga terbaik dan dijamin 100% aman.</p>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6 mb-12 shadow-lg" data-aos="fade-up" data-aos-delay="100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <!-- Search Box -->
                <div class="md:col-span-6 relative">
                    <i class='bx bx-search absolute left-4 top-1/2 transform -translate-y-1/2 text-secondary-text text-xl'></i>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berdasarkan judul, rank, dll..." class="w-full bg-background border border-gray-800 text-white rounded-xl pl-12 pr-4 py-3 focus:ring-primary focus:border-primary transition-all">
                </div>

                <!-- Category Filter -->
                <div class="md:col-span-3">
                    <select wire:model.live="category_id" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-3 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Semua Game</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="md:col-span-3">
                    <select wire:model.live="sort" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-3 focus:ring-primary focus:border-primary transition-all">
                        <option value="latest">Terbaru</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Grid Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative min-h-[400px]">
            <!-- Skeleton Loader State -->
            <div wire:loading.delay wire:target="search, category_id, sort" class="absolute inset-0 z-20 bg-background/80 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-primary shadow-neon-green mb-4"></div>
                    <span class="text-primary font-bold animate-pulse">Memuat data...</span>
                </div>
            </div>

            @forelse($accounts as $account)
            <a href="{{ route('account.detail', $account->id) }}" class="bg-card border border-gray-800 rounded-2xl overflow-hidden group hover:border-primary/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-neon-green relative block" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                <!-- Image -->
                <div class="relative h-48 overflow-hidden z-10">
                    <img src="{{ Storage::url($account->thumbnail) }}" alt="{{ $account->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute top-3 right-3 bg-background/80 backdrop-blur-md border border-gray-700 px-3 py-1 rounded-full text-xs font-semibold text-primary">
                        {{ $account->category->name }}
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-5 relative z-10 bg-card">
                    <h3 class="text-lg font-bold text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">{{ $account->title }}</h3>
                    
                    <div class="flex items-center space-x-3 mb-4 text-xs text-secondary-text">
                        <div class="flex items-center space-x-1">
                            <i class='bx bx-medal text-primary'></i>
                            <span>{{ Str::limit($account->rank ?? 'Unranked', 10) }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class='bx bx-layer text-primary'></i>
                            <span>Lv. {{ $account->level ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-800 flex items-center justify-between">
                        <span class="text-lg font-bold text-white font-orbitron">Rp {{ number_format($account->price, 0, ',', '.') }}</span>
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class='bx bx-chevron-right text-xl'></i>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-20 bg-card border border-gray-800 rounded-2xl">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-800 text-4xl mb-6 text-gray-600">
                    <i class='bx bx-ghost'></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2 font-orbitron">Pencarian Tidak Ditemukan</h3>
                <p class="text-secondary-text">Maaf, kami tidak dapat menemukan akun yang sesuai dengan filter Anda.</p>
                <button wire:click="$set('search', ''); $set('category_id', '')" class="mt-6 px-6 py-2 bg-primary/10 border border-primary text-primary hover:bg-primary hover:text-white rounded-xl transition-colors">
                    Reset Filter
                </button>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $accounts->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
