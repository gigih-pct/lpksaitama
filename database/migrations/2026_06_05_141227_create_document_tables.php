<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jenis Berkas / Document Types (Master)
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('nama_berkas'); // e.g., KTP, KK, Ijazah, Paspor
            $table->boolean('is_mandatory')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Berkas Siswa / Student Documents
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade');
            $table->string('file_path')->nullable(); // path to file
            $table->enum('status', ['Belum Kumpul', 'Menunggu Verifikasi', 'Sudah Verifikasi', 'Ditolak'])->default('Belum Kumpul');
            $table->date('tanggal_kumpul')->nullable();
            $table->text('catatan')->nullable(); // Alasan ditolak dsb
            $table->timestamps();
        });

        // Request Dokumen Siswa
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('tujuan_request')->nullable();
            $table->enum('status', ['Pending', 'Diproses', 'Selesai', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });

        // Pengumuman / Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->enum('role_target', ['Semua', 'Siswa', 'Sensei', 'Orangtua'])->default('Semua');
            $table->date('tanggal');
            $table->string('banner_image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('document_types');
    }
};
