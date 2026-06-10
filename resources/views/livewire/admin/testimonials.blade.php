<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

new #[\Livewire\Attributes\Layout('layouts.admin')] class extends Component
{
    use WithPagination;
    use WithFileUploads;

    public string $search = '';
    
    // Form fields
    public $name;
    public $message;
    public $image;
    
    public bool $isCreating = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'message' => 'nullable|string',
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        $imagePath = $this->image->store('testimonials', 'public');

        Testimonial::create([
            'name' => $this->name,
            'message' => $this->message,
            'image' => $imagePath,
            'rating' => 5, // default
        ]);

        $this->reset(['name', 'message', 'image', 'isCreating']);
        $this->dispatch('toast', type: 'success', message: 'Testimoni berhasil ditambahkan!');
    }

    public function deleteTestimonial(int $id): void
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }
        $testimonial->delete();
        $this->dispatch('toast', type: 'success', message: 'Testimoni berhasil dihapus!');
    }

    public function with(): array
    {
        return [
            'testimonials' => Testimonial::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-orbitron font-bold text-white mb-1">Testimoni Customer</h1>
            <p class="text-secondary-text text-sm">Kelola testimoni dan screenshot bukti transaksi.</p>
        </div>
        <button wire:click="$toggle('isCreating')" class="bg-primary hover:bg-green-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-neon-green flex items-center space-x-2">
            <i class='bx bx-plus text-xl'></i>
            <span>Tambah Testimoni</span>
        </button>
    </div>

    @if($isCreating)
    <div class="bg-card border border-primary/50 rounded-2xl p-6 mb-6 shadow-[0_0_15px_rgba(34,197,94,0.15)]">
        <h2 class="text-xl font-bold text-white mb-4">Tambah Testimoni Baru</h2>
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-2">Nama Customer</label>
                    <input wire:model="name" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="Cth: Budi (Gamer)">
                    @error('name') <span class="text-danger text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-2">Pesan (Opsional)</label>
                    <input wire:model="message" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="Cth: Akun aman, proses cepat!">
                    @error('message') <span class="text-danger text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-text mb-2">Upload Screenshot Transaksi (Image)</label>
                    <input wire:model="image" type="file" accept="image/*" class="block w-full text-sm text-secondary-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 transition-colors">
                    <div wire:loading wire:target="image" class="text-primary text-xs mt-2">Mengunggah...</div>
                    @error('image') <span class="text-danger text-xs mt-1 block">{{ $message }}</span> @enderror
                    
                    @if ($image)
                        <div class="mt-4">
                            <p class="text-xs text-secondary-text mb-2">Preview Gambar:</p>
                            <img src="{{ $image->temporaryUrl() }}" class="h-32 object-contain border border-gray-800 rounded-lg">
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$toggle('isCreating')" class="px-5 py-2.5 rounded-xl border border-gray-800 text-secondary-text hover:text-white hover:bg-gray-800 transition-colors">Batal</button>
                <button type="submit" class="bg-primary hover:bg-green-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-neon-green" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan Testimoni</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-card border border-gray-800 rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="relative w-full sm:w-64">
                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-text text-xl'></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berdasarkan nama..." class="w-full bg-background border border-gray-800 text-white rounded-xl pl-10 pr-4 py-2.5 focus:ring-primary focus:border-primary transition-colors">
            </div>
            <div class="text-sm text-secondary-text">
                Total: {{ $testimonials->total() }} testimoni
            </div>
        </div>

        @if($testimonials->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($testimonials as $testimonial)
            <div class="bg-background border border-gray-800 rounded-2xl overflow-hidden relative group hover:border-primary/30 transition-all flex flex-col">
                @if($testimonial->image)
                    <div class="h-40 w-full bg-gray-900 border-b border-gray-800">
                        <img src="{{ asset('storage/' . $testimonial->image) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" alt="Testimoni">
                    </div>
                @endif
                
                <div class="p-5 flex-1 flex flex-col">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-primary/20 border border-primary/50 flex items-center justify-center text-primary font-bold text-sm">
                                {{ strtoupper(substr($testimonial->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-white text-sm">{{ $testimonial->name }}</p>
                                <p class="text-xs text-secondary-text">{{ $testimonial->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <button onclick="confirmDeleteTestimonial({{ $testimonial->id }})" class="opacity-0 group-hover:opacity-100 w-7 h-7 rounded-lg bg-danger/10 text-danger hover:bg-danger/20 transition-all inline-flex items-center justify-center">
                            <i class='bx bx-trash text-sm'></i>
                        </button>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex items-center space-x-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class='bx bxs-star text-yellow-400 text-xs'></i>
                        @endfor
                    </div>

                    <!-- Message -->
                    @if($testimonial->message)
                        <p class="text-secondary-text text-xs leading-relaxed italic border-l-2 border-primary/50 pl-2">"{{ $testimonial->message }}"</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 text-3xl mb-4 text-gray-600">
                <i class='bx bx-message-square-detail'></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Belum Ada Testimoni</h3>
            <p class="text-secondary-text text-sm">Testimoni dari customer akan muncul di sini.</p>
        </div>
        @endif

        <div class="mt-6">
            {{ $testimonials->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <script>
        function confirmDeleteTestimonial(id) {
            Swal.fire({
                title: 'Hapus Testimoni?',
                text: 'Testimoni dari customer ini akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1f1f1f',
                color: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteTestimonial', id);
                }
            });
        }
    </script>
</div>
