<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Str;

new #[\Livewire\Attributes\Layout('layouts.admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $name = '';
    public string $slug = '';
    public string $icon = '';
    public ?int $categoryId = null;
    public bool $isModalOpen = false;

    public function updatedName($value): void
    {
        if (!$this->categoryId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(?int $id = null): void
    {
        $this->resetValidation();

        if ($id) {
            $category = Category::findOrFail($id);
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->icon = $category->icon ?? '';
        } else {
            $this->reset(['name', 'slug', 'icon', 'categoryId']);
        }

        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->reset(['name', 'slug', 'icon', 'categoryId']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'required|unique:categories,slug,' . $this->categoryId,
            'icon' => 'nullable|string|max:255',
        ]);

        if ($this->categoryId) {
            Category::findOrFail($this->categoryId)->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'icon' => $this->icon,
            ]);
            $this->dispatch('toast', type: 'success', message: 'Kategori berhasil diperbarui!');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'icon' => $this->icon,
            ]);
            $this->dispatch('toast', type: 'success', message: 'Kategori berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function deleteCategory(int $id): void
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Kategori berhasil dihapus!');
    }

    public function rendering(): void
    {
        //
    }

    public function with(): array
    {
        return [
            'categories' => Category::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-orbitron font-bold text-white mb-1">Kategori</h1>
            <p class="text-secondary-text text-sm">Kelola kategori game untuk marketplace Anda.</p>
        </div>
        <button wire:click="openModal" class="bg-primary hover:bg-green-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-neon-green flex items-center space-x-2">
            <i class='bx bx-plus text-xl'></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-card border border-gray-800 rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="relative w-full sm:w-64">
                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-text text-xl'></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kategori..." class="w-full bg-background border border-gray-800 text-white rounded-xl pl-10 pr-4 py-2.5 focus:ring-primary focus:border-primary transition-colors">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-secondary-text uppercase bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 rounded-l-lg">ID</th>
                        <th class="px-4 py-3">Ikon</th>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3 text-right rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-4 py-4 text-white font-medium">#{{ $category->id }}</td>
                        <td class="px-4 py-4 text-primary text-2xl">
                            <i class='{{ $category->icon ?? "bx bx-category" }}'></i>
                        </td>
                        <td class="px-4 py-4 text-white font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-4 text-secondary-text">{{ $category->slug }}</td>
                        <td class="px-4 py-4 text-right space-x-1">
                            <button wire:click="openModal({{ $category->id }})" class="w-8 h-8 rounded-lg bg-gray-800 text-secondary-text hover:text-white hover:bg-gray-700 transition-colors inline-flex items-center justify-center"><i class='bx bx-edit'></i></button>
                            <button onclick="confirmDelete({{ $category->id }}, 'deleteCategory')" class="w-8 h-8 rounded-lg bg-gray-800 text-secondary-text hover:text-danger hover:bg-danger/10 transition-colors inline-flex items-center justify-center"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-secondary-text">
                            Belum ada kategori yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $categories->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-[90] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-card border border-gray-800 rounded-2xl w-full max-w-md overflow-hidden shadow-2xl z-10">
            <div class="p-6 border-b border-gray-800 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
                <button wire:click="closeModal" class="text-secondary-text hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <form wire:submit="save" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-1">Nama Kategori</label>
                    <input wire:model.live="name" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary transition-colors" placeholder="Contoh: Mobile Legends">
                    @error('name') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-1">Slug</label>
                    <input wire:model="slug" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary transition-colors" placeholder="mobile-legends">
                    @error('slug') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-secondary-text mb-1">Ikon (Boxicons Class)</label>
                    <input wire:model="icon" type="text" class="w-full bg-background border border-gray-800 text-white rounded-xl px-4 py-2.5 focus:ring-primary focus:border-primary transition-colors" placeholder="bx bx-joystick">
                    <p class="text-xs text-gray-500 mt-1">Gunakan class dari <a href="https://boxicons.com/" target="_blank" class="text-primary hover:underline">Boxicons</a></p>
                    @error('icon') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-5 py-2.5 border border-gray-700 text-secondary-text rounded-xl hover:bg-gray-800 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-green-600 transition-colors shadow-neon-green flex items-center space-x-2">
                        <span wire:loading.remove wire:target="save">Simpan</span>
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
                title: 'Apakah Anda yakin?',
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
