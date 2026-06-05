<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->integer('benar')->nullable();
            $table->integer('total_soal')->nullable();
            $table->string('keterangan')->nullable(); // L / TL
            $table->timestamps();
            
            $table->unique(['exam_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_scores');
    }
};
