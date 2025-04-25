<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteHospital extends Model
{
  use HasFactory;
  protected $table = 'favorite_hospitals';
  protected $fillable = [
    'user_id',
    'hospital_id'
  ];

  // Optionally, define relationships if needed:
  // public function user() {
  //     return $this->belongsTo(User::class);
  // }

  /**
   * Define a relationship with the HospitalsProfile model.
   */
  public function hospital()
  {
    return $this->belongsTo(HospitalsProfile::class, 'hospital_id', 'hospital_id');
  }
}
