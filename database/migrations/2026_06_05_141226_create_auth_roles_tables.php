<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Admin
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_superadmin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // Sensei
        Schema::create('senseis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_hp')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // CRM
        Schema::create('crms', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Keuangan
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Orangtua
        Schema::create('orangtuas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id'); // Relasi ke siswa
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_hp')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Foreign key ditambahkan nanti atau asumsikan siswa_id referensi siswas.id
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orangtuas');
        Schema::dropIfExists('keuangans');
        Schema::dropIfExists('crms');
        Schema::dropIfExists('senseis');
        Schema::dropIfExists('admins');
    }
};
