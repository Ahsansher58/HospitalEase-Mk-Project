<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
  use HasFactory;
  protected $table = 'main_categories';
  protected $fillable = ['name', 'sort_order'];
  public function subCategories()
  {
    return $this->hasMany(SubCategory::class, 'main_category_id');
  }
}
