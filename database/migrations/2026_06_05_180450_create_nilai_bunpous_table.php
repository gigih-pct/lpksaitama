<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_bunpous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_bunpou_id')->constrained('evaluasi_bunpous')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->integer('benar');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('nilai_bunpous');
    }
};