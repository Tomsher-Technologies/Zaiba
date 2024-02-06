<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{

  protected $fillable = [
    'name', 'address', 'phone', 'email', 'working_hours', 'latitude', 'longitude', 'status'
  ];

}
