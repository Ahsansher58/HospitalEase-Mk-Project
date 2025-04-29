<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorEducationalQualification extends Model
{
  use HasFactory;

  protected $fillable = [
    'doctor_id',
    'college_name',
    'year_studied',
    'degree',
    'qualification_certificate',
    'show_certificate_in_public',
  ];
}
