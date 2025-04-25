<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
  public function handle(Request $request, Closure $next)
  {
    if (Auth::check() && Auth::user()->type == 3) {
      return $next($request);
    }

    if (Auth::check() && Auth::user()->type == 4) {
      return $next($request);
    }

    return redirect('/');
  }
}
