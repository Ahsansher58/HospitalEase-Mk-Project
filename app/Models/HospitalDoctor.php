<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalDoctor extends Model
{
  use HasFactory;

  protected $fillable = [
    'doctor_id',
    'hospital_id',
    'is_approved',
  ];
}
