<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_wawancaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eval_wawancara_id')->constrained('evaluasi_wawancaras')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->integer('materi_program')->nullable();
            $table->integer('materi_umum')->nullable();
            $table->integer('materi_jepang')->nullable();
            $table->integer('materi_indonesia')->nullable();
            $table->integer('sikap_cara_duduk')->nullable();
            $table->integer('sikap_suara')->nullable();
            $table->integer('sikap_fokus')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('nilai_wawancaras');
    }
};