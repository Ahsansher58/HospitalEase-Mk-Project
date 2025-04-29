<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSocialMedia extends Model
{
  use HasFactory;

  protected $fillable = [
    'doctor_id',
    'youtube_link',
    'facebook_link',
    'linkdin_link',
    'instagram_link',
  ];
}
