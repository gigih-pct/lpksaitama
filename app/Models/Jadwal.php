<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['kelas_id', 'subject_id', 'hari', 'jam_mulai', 'jam_selesai', 'kegiatan'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'jadwal_id');
    }
}
