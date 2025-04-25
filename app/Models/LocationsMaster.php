<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationsMaster extends Model
{
    use HasFactory;
    protected $table = 'location_master';
    protected $fillable = [
      'locality',
      'city',
      'state',
      'country'
  ];
}
