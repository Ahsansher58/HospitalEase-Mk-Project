<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalReview extends Model
{
  use HasFactory;

  protected $fillable = ['hospital_id', 'user_id', 'review', 'rating'];

  // Relationship with Hospital
  public function hospital()
  {
    return $this->belongsTo(HospitalsProfile::class, 'hospital_id', 'hospital_id');
  }

  // Relationship with User
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
