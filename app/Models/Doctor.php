<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
  use HasFactory;

  protected $fillable = [
    'hospital_id',
    'user_id',
    'doctor_no',
    'name',
    'email',
    'dob',
    'gender',
    'phone',
    'degree',
    'specialization',
    'years_experience',
    'year_studied',
    'college_name',
    'ima_registration_number',
    'profile_image',
    'short_intro',
    'marital_status',
    'height',
    'weight',
    'address',
    'locality',
    'city',
    'state',
    'pincode',
    'country',
    'clinic_name',
    'clinic_address1',
    'clinic_address2',
    'clinic_phone',
  ];
}
