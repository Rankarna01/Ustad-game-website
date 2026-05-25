<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->string('rank')->nullable();
            $table->string('level')->nullable();
            $table->text('heroes')->nullable();
            $table->text('skins')->nullable();
            $table->text('description');
            $table->string('thumbnail');
            $table->enum('status', ['ready', 'sold'])->default('ready');
            $table->string('wa_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
