<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAwardAchievement extends Model
{
  use HasFactory;

  protected $fillable = [
    'doctor_id',
    'award_name',
    'awarded_year',
    'award_certificate',
  ];
}
