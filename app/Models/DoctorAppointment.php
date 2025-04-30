<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAppointment extends Model
{
  use HasFactory;

  protected $fillable = [
    'doctor_id',
    'day',
    'from_time',
    'to_time',
    'hospital_id',
    'country',
    'state',
    'city',
    'locality',
  ];
}
