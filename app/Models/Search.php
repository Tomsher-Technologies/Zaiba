<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
  protected $fillable = [
    'user_id',
    'temp_user_id',
    'query',
    'ip_address',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
