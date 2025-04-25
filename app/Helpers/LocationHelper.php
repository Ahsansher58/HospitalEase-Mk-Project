<?php

use App\Models\LocationsMaster;

if (!function_exists('get_locality_country')) {

  function get_locality_country()
  {
    return LocationsMaster::select('country')->distinct()->pluck('country');
  }
}

if (!function_exists('get_locality_state')) {
  function get_locality_state($country = '')
  {
    return LocationsMaster::where('country', $country)
      ->distinct()
      ->pluck('state');
  }
}

if (!function_exists('get_locality_city')) {
  function get_locality_city($country = '', $state = '')
  {
    return LocationsMaster::where('country', $country)
      ->where('state', $state)
      ->distinct()
      ->pluck('city');
  }
}

if (!function_exists('get_locality')) {
  function get_locality($country = '', $state = '', $city = '')
  {
    return LocationsMaster::where('country', $country)
      ->where('state', $state)
      ->where('city', $city)
      ->distinct()
      ->pluck('locality');
  }
}
