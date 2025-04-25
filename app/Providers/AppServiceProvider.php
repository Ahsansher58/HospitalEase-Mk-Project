<?php

namespace App\Providers;

use App\Models\BusinessCategory;
use App\Models\SubCategory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
      if ($src !== null) {
        return [
          'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
        ];
      }
      return [];
    });
    View::composer('*', function ($view) {
      $subCategory = SubCategory::where('id', 11)->first();
      if ($subCategory && !empty($subCategory->option)) {
        $subCategory = explode(',', $subCategory->option);
        sort($subCategory, SORT_STRING);
      } else {
        $subCategory = [];
      }
      /*bussiness category */
      $businessCategory = BusinessCategory::where('is_sub_category', 0)->orderBy('order_no', 'desc')->get();
      $businessCategoryData = [];
      if ($businessCategory->isNotEmpty()) {
        $businessCategoryData = $businessCategory
          ->pluck('name', 'id')
          ->toArray();
      }
      $view->with([
        'headerSubCategory' => $subCategory,
        'headerBusinessCategory' => $businessCategoryData,
      ]);
    });
  }
}
