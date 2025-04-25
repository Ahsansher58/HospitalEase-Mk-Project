<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\HospitalsProfile;
use App\Models\Setting;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  /*protected $fillable = [
    'name',
    'email',
    'password',
  ];*/
  protected $fillable = [
    'name',
    'email',
    'mobile',
    'dob',
    'gender',
    'type',
    'password',
    'otp',
    'otp_expires_at',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
   * Get the hosptial profile.
   */

  public function profile()
  {
    return $this->hasOne(HospitalsProfile::class, 'hospital_id', 'id');
  }
  /**
   * Get the favorite Hospitals.
   */
  public function favoriteHospitals()
  {
    return $this->hasMany(FavoriteHospital::class);
  }

  /**
   * Get the User profile.
   */

  public function userprofile()
  {
    return $this->hasOne(UserProfile::class, 'user_id', 'id');
  }
}
