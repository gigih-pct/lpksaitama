<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('sensei_id')->constrained('senseis')->onDelete('cascade');
            $table->string('kategori'); // e.g. "Evaluasi Oktober", "Ujian Akhir", "Test Bab"
            $table->string('judul'); // e.g. "Bab 1", "Eva 1"
            $table->integer('kkm')->default(75);
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
