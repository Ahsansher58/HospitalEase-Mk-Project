<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisements extends Model
{
    use HasFactory;
    protected $fillable = [
        'campaign_name',
        'placement',
        'option',
        'image_code',
        'start_date',
        'end_date',
    ];
}
