<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('role_target', ['Siswa', 'Orangtua', 'Staff', 'Semua'])->default('Semua');
            $table->string('lokasi')->default('Dashboard'); // 'Auth', 'Beranda', 'Dashboard', dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
