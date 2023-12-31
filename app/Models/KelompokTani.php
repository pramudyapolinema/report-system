<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokTani extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function petani() {
        return $this->hasMany(Petani::class);
    }
}
