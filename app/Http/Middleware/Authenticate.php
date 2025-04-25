<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    if ($request->is('user/*') || $request->is('user')) {
      return route('users.login');
    }
    if ($request->is('hospital/*') || $request->is('hospital')) {
      return route('hospitals.login');
    }
    if ($request->is('doctor/*') || $request->is('doctor')) {
      return route('doctors.login');
    }

    // Default redirection for other routes
    return $request->expectsJson() ? null : route('login');
  }
}
