<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
  use HasFactory;

  // Define the table name if it's not the plural form of the model name
  protected $table = 'medical_records';

  // Specify which columns can be mass-assigned
  protected $fillable = [
    'user_id',
    'report_category',
    'report_name',
    'report_date',
    'report_file',
  ];

  /**
   * Define a relationship with the User model
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
