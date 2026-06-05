<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sensei extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function classes()
    {
        return $this->belongsToMany(Kelas::class, 'class_sensei', 'sensei_id', 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'sensei_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'sensei_id');
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class, 'sensei_id');
    }
}
