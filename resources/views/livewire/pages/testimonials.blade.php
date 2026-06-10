<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Testimonial;

new #[\Livewire\Attributes\Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public function with(): array
    {
        return [
            'testimonials' => Testimonial::latest()->paginate(12),
        ];
    }
}; ?>

<div>
    <div class="pt-24 pb-12 text-center" data-aos="fade-down">
        <h1 class="text-3xl md:text-5xl font-orbitron font-bold text-white mb-4">Testimoni Pelanggan</h1>
        <p class="text-secondary-text max-w-2xl mx-auto">Bukti nyata dari ribuan transaksi sukses yang telah kami tangani.</p>
        <div class="w-20 h-1 bg-primary mx-auto mt-6 rounded-full shadow-neon-green"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        @if($testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($testimonials as $t)
                <div class="bg-card border border-gray-800 rounded-2xl p-4 hover:border-primary/50 transition-all cursor-pointer group shadow-lg" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    @if($t->image)
                        <div class="w-full h-64 overflow-hidden rounded-xl mb-4 bg-background">
                            <img src="{{ asset('storage/' . $t->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Testimoni">
                        </div>
                    @else
                        <div class="w-full h-64 rounded-xl mb-4 bg-background flex items-center justify-center border border-gray-800">
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

            <div class="mt-12 flex justify-center">
                {{ $testimonials->links(data: ['scrollTo' => false]) }}
            </div>
        @else
            <div class="text-center py-20 bg-card rounded-2xl border border-gray-800">
                <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center mx-auto mb-6 text-4xl">
                    <i class='bx bx-message-square-detail text-gray-500'></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Belum ada Testimoni</h3>
                <p class="text-secondary-text">Jadilah yang pertama untuk memberikan testimoni setelah bertransaksi!</p>
            </div>
        @endif
    </div>
</div>
