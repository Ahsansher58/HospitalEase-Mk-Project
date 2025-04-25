<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\EmergencyRequest;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function home()
  {
    $specializations = SubCategory::where('id', 3)->pluck('value')->first();
    $businessCategory = BusinessCategory::where('is_sub_category', 0)->orderBy('order_no', 'ASC')->take(8)->get();
    $cities = DB::table('hospitals_profile')->distinct()->pluck('city');
    $emergencyRequests = EmergencyRequest::where('status', 1)->orderBy('id', 'desc')->with('user:id,mobile')->get();
    return view('frontend.content.home.home', compact(
      'specializations',
      'cities',
      'emergencyRequests',
      'businessCategory'
    ));
  }
  public function contactUs()
  {
    return view('frontend.content.contact-us');
  }
}
