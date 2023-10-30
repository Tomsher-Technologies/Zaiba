<?php

namespace App\Models\Utilities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'ext',
    ];
}
