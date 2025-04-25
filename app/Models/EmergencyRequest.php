<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyRequest extends Model
{
  use HasFactory;

  protected $fillable = [
    'notes',
    'status',
    'date_time',
    'ip_address',
    'user_id',
  ];

  protected $casts = [
    'status' => 'integer',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
