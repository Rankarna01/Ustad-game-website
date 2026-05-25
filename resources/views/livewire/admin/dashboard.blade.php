<?php

use Livewire\Volt\Component;
use App\Models\Account;
use App\Models\Category;
use App\Models\Testimonial;

new #[\Livewire\Attributes\Layout('layouts.admin')] class extends Component
{
    public int $totalAccounts = 0;
    public int $totalSold = 0;
    public int $totalReady = 0;
    public int $totalCategories = 0;
    public int $totalTestimonials = 0;
    public $recentAccounts = [];

    public function mount(): void
    {
        $this->totalAccounts = Account::count();
        $this->totalSold = Account::where('status', 'sold')->count();
        $this->totalReady = Account::where('status', 'ready')->count();
        $this->totalCategories = Category::count();
        $this->totalTestimonials = Testimonial::count();
        $this->recentAccounts = Account::with('category')->latest()->take(5)->get();
    }
}; ?>

<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-orbitron font-bold text-white mb-1">Dashboard</h1>
            <p class="text-secondary-text text-sm">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-primary/50 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary text-2xl border border-primary/20 shadow-neon-green">
                    <i class='bx bxs-user-account'></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1 relative z-10">{{ $totalAccounts }}</h3>
            <p class="text-secondary-text text-sm relative z-10">Total Akun</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-primary/50 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center text-success text-2xl border border-success/20">
                    <i class='bx bx-check-shield'></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1 relative z-10">{{ $totalReady }}</h3>
            <p class="text-secondary-text text-sm relative z-10">Akun Ready</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-primary/50 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-danger/10 rounded-full blur-2xl group-hover:bg-danger/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 bg-danger/10 rounded-xl flex items-center justify-center text-danger text-2xl border border-danger/20">
                    <i class='bx bx-check-double'></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1 relative z-10">{{ $totalSold }}</h3>
            <p class="text-secondary-text text-sm relative z-10">Akun Terjual</p>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-primary/50 transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary text-2xl border border-primary/20 shadow-neon-green">
                    <i class='bx bx-category-alt'></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1 relative z-10">{{ $totalCategories }}</h3>
            <p class="text-secondary-text text-sm relative z-10">Kategori Game</p>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Accounts -->
        <div class="lg:col-span-2 bg-card border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white">Akun Terbaru</h2>
                <a href="{{ route('admin.accounts') }}" class="text-primary text-sm hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-secondary-text uppercase bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Akun</th>
                            <th class="px-4 py-3">Game</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3 text-center rounded-r-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($recentAccounts as $account)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-800 overflow-hidden flex-shrink-0">
                                        @if($account->thumbnail)
                                            <img src="{{ Storage::url($account->thumbnail) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-600"><i class='bx bx-image'></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-white line-clamp-1">{{ $account->title }}</p>
                                        <p class="text-xs text-secondary-text">{{ $account->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-white">
                                <span class="bg-gray-800 px-2 py-1 rounded-md text-xs border border-gray-700">
                                    {{ $account->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-white font-medium">Rp {{ number_format($account->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-center">
                                @if($account->status === 'ready')
                                    <span class="bg-success/10 text-success border border-success/20 px-2 py-1 rounded-full text-xs">Ready</span>
                                @else
                                    <span class="bg-danger/10 text-danger border border-danger/20 px-2 py-1 rounded-full text-xs">Sold</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-secondary-text">Belum ada data akun.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Aksi Cepat</h2>
            <div class="space-y-4">
                <a href="{{ route('admin.accounts') }}" class="flex items-center space-x-4 p-4 rounded-xl border border-gray-800 hover:border-primary/50 hover:bg-primary/5 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-xl text-primary group-hover:scale-110 transition-transform">
                        <i class='bx bx-plus'></i>
                    </div>
                    <div>
                        <p class="font-medium text-white">Kelola Akun</p>
                        <p class="text-xs text-secondary-text">Upload akun baru ke marketplace</p>
                    </div>
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center space-x-4 p-4 rounded-xl border border-gray-800 hover:border-primary/50 hover:bg-primary/5 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-xl text-primary group-hover:scale-110 transition-transform">
                        <i class='bx bx-category'></i>
                    </div>
                    <div>
                        <p class="font-medium text-white">Kelola Kategori</p>
                        <p class="text-xs text-secondary-text">Tambah game atau kategori baru</p>
                    </div>
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center space-x-4 p-4 rounded-xl border border-gray-800 hover:border-primary/50 hover:bg-primary/5 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-xl text-primary group-hover:scale-110 transition-transform">
                        <i class='bx bx-cog'></i>
                    </div>
                    <div>
                        <p class="font-medium text-white">Pengaturan WA</p>
                        <p class="text-xs text-secondary-text">Ubah nomor atau template pesan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
