<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalsProfile extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hospitals_profile';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'hospital_id',
    'hospital_name',
    'description',
    'specialization',
    'insurances',
    'medical_system',
    'phone',
    'emergency_contact',
    'email',
    'website',
    'location',
    'facilities',
    'values',
    'hospital_slug',
    'hospital_images',
    'other_data'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'values' => 'array', // Automatically casts JSON to an array
  ];

  /**
   * Define a relationship with the Hospital model if applicable.
   */
  public function hospital()
  {
    return $this->belongsTo(User::class, 'hospital_id', 'id');
  }
  /**
   * Get the hosptial Setting.
   */

  public function hospitalSetting()
  {
    return $this->hasOne(Setting::class, 'hospital_id', 'hospital_id');
  }

  public function hospitalReview()
  {
    return $this->hasMany(HospitalReview::class, 'hospital_id', 'hospital_id');
  }
}
