<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_tani_id', 'name', 'nik', 'address', 'luas_lahan',
    ];

    public function kelompokTani() {
        return $this->belongsTo(KelompokTani::class);
    }
}
