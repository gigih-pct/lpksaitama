<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop total_soal from BKK evaluasi
        Schema::table('evaluasi_bunpous', function (Blueprint $table) { $table->dropColumn('total_soal'); });
        Schema::table('evaluasi_kanjis', function (Blueprint $table) { $table->dropColumn('total_soal'); });
        Schema::table('evaluasi_kotobas', function (Blueprint $table) { $table->dropColumn('total_soal'); });

        // Rename benar to nilai
        Schema::table('nilai_bunpous', function (Blueprint $table) { $table->renameColumn('benar', 'nilai'); });
        Schema::table('nilai_kanjis', function (Blueprint $table) { $table->renameColumn('benar', 'nilai'); });
        Schema::table('nilai_kotobas', function (Blueprint $table) { $table->renameColumn('benar', 'nilai'); });

        // Add ket to FMD and Wawancara
        Schema::table('nilai_fmds', function (Blueprint $table) { $table->string('ket', 10)->nullable(); });
        Schema::table('nilai_wawancaras', function (Blueprint $table) { $table->string('ket', 10)->nullable(); });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_bunpous', function (Blueprint $table) { $table->integer('total_soal')->default(100); });
        Schema::table('evaluasi_kanjis', function (Blueprint $table) { $table->integer('total_soal')->default(100); });
        Schema::table('evaluasi_kotobas', function (Blueprint $table) { $table->integer('total_soal')->default(100); });

        Schema::table('nilai_bunpous', function (Blueprint $table) { $table->renameColumn('nilai', 'benar'); });
        Schema::table('nilai_kanjis', function (Blueprint $table) { $table->renameColumn('nilai', 'benar'); });
        Schema::table('nilai_kotobas', function (Blueprint $table) { $table->renameColumn('nilai', 'benar'); });

        Schema::table('nilai_fmds', function (Blueprint $table) { $table->dropColumn('ket'); });
        Schema::table('nilai_wawancaras', function (Blueprint $table) { $table->dropColumn('ket'); });
    }
};
