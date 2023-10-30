<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            Cache::forget('currency');
        });

        static::updating(function ($model) {
            Cache::forget('currency');
        });

        static::deleting(function ($model) {
            Cache::forget('currency');
        });

        parent::boot();
    }
}
