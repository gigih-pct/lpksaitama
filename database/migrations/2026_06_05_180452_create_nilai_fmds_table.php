<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_fmds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_fmd_id')->constrained('evaluasi_fmds')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->integer('skor_mtk')->nullable();
            $table->integer('skor_lari')->nullable();
            $table->integer('skor_push_up')->nullable();
            $table->integer('skor_sit_up')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('nilai_fmds');
    }
};