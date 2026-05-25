<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Testimonial;

new #[\Livewire\Attributes\Layout('layouts.admin')] class extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteTestimonial(int $id): void
    {
        Testimonial::findOrFail($id)->delete();
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
            <p class="text-secondary-text text-sm">Testimoni yang masuk dari pelanggan yang telah bertransaksi.</p>
        </div>
    </div>

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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($testimonials as $testimonial)
            <div class="bg-background border border-gray-800 rounded-2xl p-5 relative group hover:border-primary/30 transition-all">
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
                <div class="flex items-center space-x-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $testimonial->rating)
                            <i class='bx bxs-star text-yellow-400 text-sm'></i>
                        @else
                            <i class='bx bx-star text-gray-600 text-sm'></i>
                        @endif
                    @endfor
                    <span class="text-xs text-secondary-text ml-1">({{ $testimonial->rating }}/5)</span>
                </div>

                <!-- Message -->
                <p class="text-secondary-text text-sm leading-relaxed">{{ $testimonial->message }}</p>
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
