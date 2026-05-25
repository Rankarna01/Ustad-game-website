<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Account;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

new #[\Livewire\Attributes\Layout('layouts.admin')] class extends Component
{
    use WithPagination, WithFileUploads;

    // Filters
    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    // Modal state
    public bool $isModalOpen = false;

    // Form fields
    public ?int $accountId = null;
    public string $title = '';
    public string $category_id = '';
    public string $price = '';
    public string $rank = '';
    public string $level = '';
    public string $heroes = '';
    public string $skins = '';
    public string $description = '';
    public string $status = 'ready';
    public string $wa_number = '';
    public $thumbnail = null;
    public ?string $existingThumbnail = null;
    public $galleryImages = [];
    public $existingGalleries = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openModal(?int $id = null): void
    {
        $this->resetValidation();

        if ($id) {
            $account = Account::with('galleries')->findOrFail($id);
            $this->accountId = $account->id;
            $this->title = $account->title;
            $this->category_id = (string) $account->category_id;
            $this->price = (string) $account->price;
            $this->rank = $account->rank ?? '';
            $this->level = $account->level ?? '';
            $this->heroes = $account->heroes ?? '';
            $this->skins = $account->skins ?? '';
            $this->description = $account->description;
            $this->status = $account->status;
            $this->wa_number = $account->wa_number ?? '';
            $this->existingThumbnail = $account->thumbnail;
            $this->thumbnail = null;
            $this->existingGalleries = $account->galleries;
            $this->galleryImages = [];
        } else {
            $this->reset(['accountId', 'title', 'category_id', 'price', 'rank', 'level', 'heroes', 'skins', 'description', 'wa_number', 'thumbnail', 'existingThumbnail', 'galleryImages', 'existingGalleries']);
            $this->status = 'ready';
        }

        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->reset(['accountId', 'title', 'category_id', 'price', 'rank', 'level', 'heroes', 'skins', 'description', 'wa_number', 'thumbnail', 'existingThumbnail', 'galleryImages', 'existingGalleries']);
        $this->status = 'ready';
        $this->resetValidation();
    }
    
    public function removeGalleryImage(int $galleryId): void
    {
        $gallery = Gallery::findOrFail($galleryId);
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();
        
        // Refresh existing galleries
        if ($this->accountId) {
            $this->existingGalleries = Gallery::where('account_id', $this->accountId)->get();
        }
        
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Gambar berhasil dihapus!']);
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'rank' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'heroes' => 'nullable|string',
            'skins' => 'nullable|string',
            'description' => 'required|string',
            'status' => 'required|in:ready,sold',
            'wa_number' => 'nullable|string|max:20',
            'thumbnail' => 'nullable|image|max:2048',
            'galleryImages.*' => 'nullable|image|max:2048',
        ];

        if (!$this->accountId && !$this->thumbnail) {
            $rules['thumbnail'] = 'required|image|max:2048';
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'rank' => $this->rank,
            'level' => $this->level,
            'heroes' => $this->heroes,
            'skins' => $this->skins,
            'description' => $this->description,
            'status' => $this->status,
            'wa_number' => $this->wa_number,
        ];

        if ($this->thumbnail) {
            $path = $this->thumbnail->store('accounts', 'public');
            $data['thumbnail'] = $path;

            if ($this->accountId && $this->existingThumbnail) {
                Storage::disk('public')->delete($this->existingThumbnail);
            }
        }

        if ($this->accountId) {
            $account = Account::findOrFail($this->accountId);
            $account->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Akun berhasil diperbarui!']);
        } else {
            $account = Account::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Akun baru berhasil ditambahkan!']);
        }
        
        if ($this->galleryImages && count($this->galleryImages) > 0) {
            foreach ($this->galleryImages as $image) {
                $path = $image->store('galleries', 'public');
                Gallery::create([
                    'account_id' => $account->id,
                    'image_path' => $path
                ]);
            }
        }

        $this->closeModal();
    }

    public function deleteAccount(int $id): void
    {
        $account = Account::with('galleries')->findOrFail($id);
        if ($account->thumbnail) {
            Storage::disk('public')->delete($account->thumbnail);
        }
        foreach ($account->galleries as $gallery) {
            if (Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
        }
        $account->delete();
        $this->dispatch('toast', type: 'success', message: 'Akun berhasil dihapus!');
    }

    public function with(): array
    {
        $query = Account::with('category')->latest();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }
        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return [
            'accounts' => $query->paginate(10),
            'allCategories' => Category::orderBy('name')->get(),
        ];
    }
}; ?>

<div>
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-orbitron font-bold text-white mb-1">Kelola Akun</h1>
            <p class="text-secondary-text text-sm">Manajemen listing akun game yang akan dijual.</p>
        </div>
        <button wire:click="openModal" class="bg-primary hover:bg-green-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-neon-green flex items-center space-x-2">
            <i class='bx bx-plus text-xl'></i>
            <span>Tambah Akun</span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-card border border-gray-800 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="relative w-full md:w-64">
                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-text text-xl'></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul akun..." class="w-full bg-background border border-gray-800 text-white rounded-xl pl-10 pr-4 py-2.5 focus:ring-primary focus:border-primary transition-colors">
            </div>
            
            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-4">
                <select wire:model.live="filterCategory" class="bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary">
                    <option value="">Semua Kategori</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                
                <select wire:model.live="filterStatus" class="bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary">
                    <option value="">Semua Status</option>
                    <option value="ready">Ready</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-secondary-text uppercase bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 rounded-l-lg">Akun</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($accounts as $account)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-lg bg-gray-800 overflow-hidden border border-gray-700 flex-shrink-0">
                                    @if($account->thumbnail)
                                        <img src="{{ Storage::url($account->thumbnail) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-600"><i class='bx bx-image text-xl'></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-white line-clamp-1">{{ $account->title }}</p>
                                    <p class="text-xs text-secondary-text mt-1">
                                        <i class='bx bx-medal text-primary'></i> {{ $account->rank ?? '-' }}
                                    </p>
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
                        <td class="px-4 py-4 text-right space-x-1">
                            <button wire:click="openModal({{ $account->id }})" class="w-8 h-8 rounded-lg bg-gray-800 text-secondary-text hover:text-white hover:bg-gray-700 transition-colors inline-flex items-center justify-center"><i class='bx bx-edit'></i></button>
                            <button onclick="confirmDelete({{ $account->id }}, 'deleteAccount')" class="w-8 h-8 rounded-lg bg-gray-800 text-secondary-text hover:text-danger hover:bg-danger/10 transition-colors inline-flex items-center justify-center"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-secondary-text">Belum ada akun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $accounts->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-[90] flex items-center justify-center p-4 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-card border border-gray-800 rounded-2xl w-full max-w-4xl my-8 shadow-2xl z-10">
            <div class="p-6 border-b border-gray-800 flex items-center justify-between sticky top-0 bg-card z-10 rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">{{ $accountId ? 'Edit Akun' : 'Tambah Akun' }}</h3>
                <button wire:click="closeModal" class="text-secondary-text hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <form wire:submit="save" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-text mb-1">Judul Akun</label>
                            <input wire:model="title" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="Contoh: Akun MLBB Sultan">
                            @error('title') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-text mb-1">Kategori Game</label>
                                <select wire:model="category_id" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($allCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-text mb-1">Harga (Rp)</label>
                                <input wire:model="price" type="number" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="150000">
                                @error('price') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-text mb-1">Rank Saat Ini</label>
                                <input wire:model="rank" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="Mythic Glory">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-text mb-1">Level</label>
                                <input wire:model="level" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="105">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary-text mb-1">Jumlah Hero/Karakter</label>
                            <input wire:model="heroes" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="110 Hero">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary-text mb-1">Jumlah Skin/Item</label>
                            <input wire:model="skins" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="15 Epic, 5 Legend">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-text mb-1">Deskripsi Lengkap</label>
                            <textarea wire:model="description" rows="5" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="Deskripsikan spesifikasi akun secara detail..."></textarea>
                            @error('description') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary-text mb-1">Upload Thumbnail</label>
                            <div class="relative border-2 border-dashed border-gray-800 rounded-xl p-4 text-center hover:border-primary/50 transition-colors bg-background">
                                <input wire:model="thumbnail" type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                @if($thumbnail)
                                    <div class="relative w-full h-32 rounded-lg overflow-hidden">
                                        <img src="{{ $thumbnail->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @elseif($existingThumbnail)
                                    <div class="relative w-full h-32 rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($existingThumbnail) }}" class="w-full h-full object-cover opacity-70">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="bg-black/50 text-white px-3 py-1 rounded-md text-xs backdrop-blur-sm">Gambar Saat Ini</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="py-4">
                                        <i class='bx bx-cloud-upload text-4xl text-secondary-text mb-2'></i>
                                        <p class="text-sm text-secondary-text">Klik atau Drag & Drop gambar</p>
                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div wire:loading wire:target="thumbnail" class="text-sm text-primary mt-2 flex items-center gap-2">
                                <i class='bx bx-loader-alt animate-spin'></i> Uploading...
                            </div>
                            @error('thumbnail') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-secondary-text mb-1">Gambar Gallery Tambahan</label>
                            <div class="relative border-2 border-dashed border-gray-800 rounded-xl p-4 text-center hover:border-primary/50 transition-colors bg-background">
                                <input wire:model="galleryImages" type="file" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div class="py-4">
                                    <i class='bx bx-images text-4xl text-secondary-text mb-2'></i>
                                    <p class="text-sm text-secondary-text">Pilih banyak gambar sekaligus</p>
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB/gambar)</p>
                                </div>
                            </div>
                            
                            <div wire:loading wire:target="galleryImages" class="text-sm text-primary mt-2 flex items-center gap-2">
                                <i class='bx bx-loader-alt animate-spin'></i> Uploading...
                            </div>
                            @error('galleryImages.*') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                            
                            <!-- Existing & New Gallery Previews -->
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-4">
                                <!-- Existing -->
                                @foreach($existingGalleries as $gallery)
                                    <div class="relative w-full h-24 rounded-lg overflow-hidden border border-gray-700 group">
                                        <img src="{{ Storage::url($gallery->image_path) }}" class="w-full h-full object-cover opacity-80">
                                        <button type="button" wire:click="removeGalleryImage({{ $gallery->id }})" class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class='bx bx-trash text-danger text-xl'></i>
                                        </button>
                                    </div>
                                @endforeach
                                
                                <!-- New Uploads Preview -->
                                @if($galleryImages)
                                    @foreach($galleryImages as $image)
                                        <div class="relative w-full h-24 rounded-lg overflow-hidden border border-primary/50">
                                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                            <div class="absolute top-1 right-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded shadow">Baru</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-text mb-1">Status Penjualan</label>
                                <select wire:model="status" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary">
                                    <option value="ready">Ready (Tersedia)</option>
                                    <option value="sold">Sold (Terjual)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-text mb-1">No. WA Khusus (Opsional)</label>
                                <input wire:model="wa_number" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary" placeholder="08123456789">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-6 mt-6 border-t border-gray-800 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-6 py-2.5 border border-gray-700 text-secondary-text rounded-xl hover:bg-gray-800 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-green-600 transition-colors shadow-neon-green flex items-center space-x-2">
                        <span wire:loading.remove wire:target="save">Simpan Akun</span>
                        <span wire:loading wire:target="save" class="flex items-center space-x-2">
                            <i class='bx bx-loader-alt animate-spin'></i> <span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    
    <!-- Delete Confirmation Script -->
    <script>
        function confirmDelete(id, method) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
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
                    @this.call(method, id);
                }
            });
        }
    </script>
</div>
