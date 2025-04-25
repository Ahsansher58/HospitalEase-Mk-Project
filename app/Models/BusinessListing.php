<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessListing extends Model
{
  use HasFactory;

  protected $table = 'business_listings'; // Ensure this matches the table name

  protected $fillable = [
    'business_name',
    'business_address',
    'city',
    'state',
    'country',
    'whatsapp_number',
    'mobile_number',
    'email',
    'website',
    'description',
    'google_map_url',
    'photos',
    'categories',
    'facebook_link',
    'instagram_link',
    'youtube_link',
    'linkedin_link',
    'banner_image',
    'slug',
  ];

  protected $casts = [
    'photos' => 'array',
    'categories' => 'array'
  ];

  public function getBusinessCategory()
  {
    $categoryIds = is_string($this->categories) ? json_decode($this->categories) : $this->categories;
    if (is_array($categoryIds) && !empty($categoryIds)) {
      $categories = BusinessCategory::whereIn('id', $categoryIds)->orderBy('order_no', 'ASC')->pluck('name')->toArray();
      return $categories;
    }
    return [];
  }
}
