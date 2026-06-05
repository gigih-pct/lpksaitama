<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('attendances')->delete();
        DB::table('attendance_sessions')->delete();

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['curriculum_id']);
            $table->dropColumn('curriculum_id');
            $table->foreignId('jadwal_id')->after('id')->constrained('jadwals')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        DB::table('attendances')->delete();
        DB::table('attendance_sessions')->delete();

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['jadwal_id']);
            $table->dropColumn('jadwal_id');
            $table->foreignId('curriculum_id')->after('id')->constrained('curricula')->onDelete('cascade');
        });
    }
};
