<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Angkatan
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('nama_angkatan'); // e.g., Angkatan 1
            $table->integer('tahun');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Kelas
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->string('nama_kelas'); // e.g., N5-A
            $table->string('level')->nullable(); // e.g., N5, N4
            $table->timestamps();
        });

        // Pivot Kelas - Sensei
        Schema::create('class_sensei', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('sensei_id')->constrained('senseis')->onDelete('cascade');
            $table->timestamps();
        });

        // Add foreign key class_id to siswas table
        Schema::table('siswas', function (Blueprint $table) {
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
        });

        // Mata Pelajaran
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelajaran'); // e.g., Choukai, Bunpou
            $table->integer('kkm')->default(60);
            $table->timestamps();
        });

        // Nilai
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('sensei_id')->nullable()->constrained('senseis')->onDelete('set null');
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_input')->nullable();
            $table->timestamps();
        });

        // Evaluasi
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('sensei_id')->nullable()->constrained('senseis')->onDelete('set null');
            $table->string('judul');
            $table->text('deskripsi');
            $table->date('tanggal_evaluasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('subjects');
        
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
        });

        Schema::dropIfExists('class_sensei');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('batches');
    }
};
