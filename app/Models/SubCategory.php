<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategoryType;
use App\Models\SubCategoryOption;

class SubCategory extends Model
{
  use HasFactory;
  protected $table = 'sub_categories';

  protected $fillable = [
    'main_category_id',
    'sort_order',
    'sub_category_name',
    'type',
    'value',
    'sub_category_required',
    'option',
  ];
  public function mainCategory()
  {
    return $this->belongsTo(MainCategory::class, 'main_category_id');
  }
}
