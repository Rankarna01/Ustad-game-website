<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.home')->name('home');
Volt::route('/katalog', 'pages.catalog')->name('catalog');
Volt::route('/akun/{id}', 'pages.detail')->name('account.detail');
Volt::route('/tentang-kami', 'pages.about')->name('about');
Volt::route('/kontak', 'pages.contact')->name('contact');
Volt::route('/testimoni', 'pages.testimonials')->name('testimonials');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Volt::route('/dashboard', 'admin.dashboard')->name('dashboard');
    Volt::route('/categories', 'admin.categories')->name('categories');
    Volt::route('/accounts', 'admin.accounts')->name('accounts');
    Volt::route('/testimonials', 'admin.testimonials')->name('testimonials');
    Volt::route('/settings', 'admin.settings')->name('settings');
});

require __DIR__.'/auth.php';
