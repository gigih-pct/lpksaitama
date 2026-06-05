<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluasi_kanjis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('sensei_id')->constrained('senseis')->cascadeOnDelete();
            $table->string('kategori');
            $table->string('judul');
            $table->date('tanggal');
            $table->integer('kkm')->default(75);
            $table->integer('total_soal');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('evaluasi_kanjis');
    }
};