<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tagihan / Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('judul'); // e.g., Pembayaran Tahap 1
            $table->decimal('nominal', 15, 2);
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->timestamps();
        });

        // Pembayaran / Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->decimal('nominal_dibayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('bukti_bayar')->nullable(); // path to image/pdf
            $table->enum('status_verifikasi', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Perlengkapan / Equipments (Master)
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perlengkapan'); // e.g., Buku Minna no Nihongo, Seragam
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Perlengkapan Siswa / Student Equipments (Pivot / Tracking)
        Schema::create('student_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
            $table->enum('status', ['Belum Bisa Diambil', 'Bisa Diambil', 'Sudah Diambil'])->default('Belum Bisa Diambil');
            $table->date('tanggal_diambil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_equipments');
        Schema::dropIfExists('equipments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
    }
};
