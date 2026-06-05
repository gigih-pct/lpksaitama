<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'subject_id',
        'sensei_id',
        'kategori',
        'judul',
        'kkm',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function sensei()
    {
        return $this->belongsTo(Sensei::class, 'sensei_id');
    }

    public function scores()
    {
        return $this->hasMany(ExamScore::class, 'exam_id');
    }
}
