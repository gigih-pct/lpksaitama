<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_kotobas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_kotoba_id')->constrained('evaluasi_kotobas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->integer('benar');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('nilai_kotobas');
    }
};