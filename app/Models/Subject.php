<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the slug/key for this subject (used in routing & logic).
     * Maps subject name to evaluasi table type.
     */
    public function getTypeKeyAttribute(): ?string
    {
        $map = [
            'Bunpou'    => 'bunpou',
            'Kanji'     => 'kanji',
            'Kotoba'    => 'kotoba',
            'FMD'       => 'fmd',
            'Wawancara' => 'wawancara',
        ];

        return $map[$this->nama_pelajaran] ?? null;
    }
}
