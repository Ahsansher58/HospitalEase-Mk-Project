<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalAppointment extends Model
{
  use HasFactory;

  protected $fillable = [
    'patient_name',
    'phone_number',
    'email',
    'appointment_date',
    'hospital_id'
  ];

  /**
   * Define a relationship with the Hospital model if applicable.
   */
  public function hospitalProfile()
  {
    return $this->belongsTo(HospitalsProfile::class, 'hospital_id', 'hospital_id');
  }
}
