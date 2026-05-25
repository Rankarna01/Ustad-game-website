<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        
        if ($user->role === 'admin') {
            $this->redirect(route('admin.dashboard'));
        } else {
            $this->redirect(route('home'));
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-secondary-text mb-2">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-secondary-text">
                    <i class='bx bx-envelope text-xl'></i>
                </div>
                <input wire:model="form.email" id="email" type="email" class="w-full bg-background border border-gray-800 text-white rounded-xl pl-12 pr-4 py-3 focus:ring-primary focus:border-primary transition-all @error('form.email') border-danger focus:border-danger focus:ring-danger @enderror" placeholder="admin@ustazz.id" required autofocus autocomplete="username">
            </div>
            @error('form.email') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-secondary-text mb-2">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-secondary-text">
                    <i class='bx bx-lock-alt text-xl'></i>
                </div>
                <input wire:model="form.password" id="password" type="password" class="w-full bg-background border border-gray-800 text-white rounded-xl pl-12 pr-4 py-3 focus:ring-primary focus:border-primary transition-all @error('form.password') border-danger focus:border-danger focus:ring-danger @enderror" placeholder="••••••••" required autocomplete="current-password">
            </div>
            @error('form.password') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded bg-background border-gray-800 text-primary focus:ring-primary focus:ring-offset-background transition-colors">
                <span class="ml-2 text-sm text-secondary-text group-hover:text-white transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-primary hover:text-green-400 hover:underline transition-colors">
                    Lupa Password?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full py-3 px-4 bg-primary text-white font-bold rounded-xl hover:bg-green-600 transition-all shadow-[0_0_15px_rgba(34,197,94,0.4)] hover:shadow-[0_0_25px_rgba(34,197,94,0.6)] flex justify-center items-center">
                <span wire:loading.remove wire:target="login">Sign In <i class='bx bx-log-in-circle ml-2'></i></span>
                <span wire:loading wire:target="login" class="flex items-center space-x-2">
                    <i class='bx bx-loader-alt animate-spin text-xl'></i> <span>Authenticating...</span>
                </span>
            </button>
        </div>
        
        <div class="text-center mt-6 pt-6 border-t border-gray-800">
            <p class="text-sm text-secondary-text">
                Belum punya akun? 
                <a href="{{ route('register') }}" wire:navigate class="text-primary hover:text-green-400 font-medium hover:underline transition-colors">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</div>
