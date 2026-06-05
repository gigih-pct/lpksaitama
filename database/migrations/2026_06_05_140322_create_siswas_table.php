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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            
            $table->string('nomor_induk')->unique();
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp_siswa')->nullable();
            $table->string('no_hp_orangtua')->nullable();
            
            $table->enum('status_siswa', ['Calon', 'Aktif', 'Lulus', 'Drop'])->default('Calon');
            $table->string('tahap_roadmap')->nullable();
            $table->boolean('is_approved')->default(false);
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
