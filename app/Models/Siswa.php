<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class_id',
        'email',
        'password',
        'nomor_induk',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp_siswa',
        'no_hp_orangtua',
        'status_siswa',
        'tahap_roadmap',
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_approved' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'siswa_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'siswa_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'siswa_id');
    }

    public function studentEquipments()
    {
        return $this->hasMany(StudentEquipment::class, 'siswa_id');
    }

    public function studentDocuments()
    {
        return $this->hasMany(StudentDocument::class, 'siswa_id');
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class, 'siswa_id');
    }
}
