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
        Schema::create('evaluasi_nilai_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('sensei_id')->constrained('senseis')->cascadeOnDelete();
            $table->string('judul');
            $table->date('tanggal');
            $table->json('kriteria_kolom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_nilai_akhirs');
    }
};
