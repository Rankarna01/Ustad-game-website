<?php

use Livewire\Volt\Component;
use App\Models\Setting;

new #[\Livewire\Attributes\Layout('layouts.admin')] class extends Component
{
    public string $wa_number = '';
    public string $wa_message_template = '';
    public string $site_title = '';
    public string $site_description = '';

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->wa_number = $settings['wa_number'] ?? '081234567890';
        $this->wa_message_template = $settings['wa_message_template'] ?? "Halo admin saya tertarik membeli akun berikut:\n\nNama Akun: {title}\nGame: {category}\nHarga: {price}\nRank: {rank}\n\nApakah masih tersedia?";
        $this->site_title = $settings['site_title'] ?? 'Ustazz.id GameStore Marketplace';
        $this->site_description = $settings['site_description'] ?? 'Marketplace akun game terpercaya dan 100% aman.';
    }

    public function save(): void
    {
        $this->validate([
            'wa_number' => 'required|string|max:20',
            'wa_message_template' => 'required|string',
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string',
        ]);

        $settings = [
            'wa_number' => $this->wa_number,
            'wa_message_template' => $this->wa_message_template,
            'site_title' => $this->site_title,
            'site_description' => $this->site_description,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->dispatch('toast', type: 'success', message: 'Pengaturan berhasil disimpan!');
    }
}; ?>

<div>
    <div class="mb-6">
        <h1 class="text-3xl font-orbitron font-bold text-white mb-1">Pengaturan & WhatsApp</h1>
        <p class="text-secondary-text text-sm">Konfigurasi website dan pengaturan kontak WhatsApp admin.</p>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- WhatsApp Settings -->
        <div class="bg-card border border-gray-800 rounded-2xl p-6 h-fit relative overflow-hidden group hover:border-primary/50 transition-colors">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all"></div>
            
            <div class="flex items-center space-x-3 mb-6 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-[#25D366]/20 text-[#25D366] flex items-center justify-center text-xl shadow-[0_0_15px_rgba(37,211,102,0.3)]">
                    <i class='bx bxl-whatsapp'></i>
                </div>
                <h2 class="text-xl font-bold text-white">WhatsApp Config</h2>
            </div>

            <div class="space-y-4 relative z-10">
                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-1">Nomor WhatsApp Admin</label>
                    <input wire:model="wa_number" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-[#25D366] focus:border-[#25D366] transition-colors" placeholder="6281234567890">
                    <p class="text-xs text-gray-500 mt-1">Gunakan format 62... (Tanpa 0 atau +) untuk link api.whatsapp.com</p>
                    @error('wa_number') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-1">Template Pesan Pembelian</label>
                    <textarea wire:model="wa_message_template" rows="8" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-[#25D366] focus:border-[#25D366] transition-colors"></textarea>
                    
                    <div class="mt-2 p-3 bg-gray-800/50 rounded-lg border border-gray-700">
                        <p class="text-xs font-semibold text-white mb-1">Variabel Tersedia:</p>
                        <div class="flex flex-wrap gap-2 text-xs font-mono text-primary">
                            <span class="bg-primary/10 px-2 py-1 rounded border border-primary/20">{title}</span>
                            <span class="bg-primary/10 px-2 py-1 rounded border border-primary/20">{category}</span>
                            <span class="bg-primary/10 px-2 py-1 rounded border border-primary/20">{price}</span>
                            <span class="bg-primary/10 px-2 py-1 rounded border border-primary/20">{rank}</span>
                        </div>
                    </div>
                    @error('wa_message_template') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- General Settings -->
        <div class="space-y-6">
            <div class="bg-card border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-primary/50 transition-colors">
                <div class="flex items-center space-x-3 mb-6 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 text-primary flex items-center justify-center text-xl shadow-neon-green">
                        <i class='bx bx-globe'></i>
                    </div>
                    <h2 class="text-xl font-bold text-white">General Info</h2>
                </div>

                <div class="space-y-4 relative z-10">
                    <div>
                        <label class="block text-sm font-medium text-secondary-text mb-1">Nama Website (Judul)</label>
                        <input wire:model="site_title" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary transition-colors">
                        @error('site_title') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-text mb-1">Deskripsi Singkat (SEO)</label>
                        <textarea wire:model="site_description" rows="3" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary transition-colors"></textarea>
                        @error('site_description') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Save Action -->
            <div class="bg-card border border-gray-800 rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">Simpan Perubahan</h3>
                    <p class="text-xs text-secondary-text">Terapkan konfigurasi baru</p>
                </div>
                <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-green-600 transition-all shadow-neon-green flex items-center space-x-2">
                    <span wire:loading.remove wire:target="save">Simpan Sekarang</span>
                    <span wire:loading wire:target="save" class="flex items-center space-x-2">
                        <i class='bx bx-loader-alt animate-spin'></i> <span>Menyimpan...</span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
