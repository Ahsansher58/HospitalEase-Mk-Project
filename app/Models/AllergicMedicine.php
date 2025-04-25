<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergicMedicine extends Model
{
  use HasFactory;

  // The table associated with the model.
  protected $table = 'allergic_medicine';

  // The primary key associated with the table.
  protected $primaryKey = 'id';

  // Indicates if the model should be timestamped.
  public $timestamps = true;

  // The attributes that are mass assignable.
  protected $fillable = [
    'user_id',
    'medicine_name',
    'symptoms_reactions',
  ];

  // The attributes that should be hidden for arrays.
  protected $hidden = [];

  // If you want to define cast types for attributes, you can add them here.
  protected $casts = [
    'status' => 'boolean',
  ];
}
