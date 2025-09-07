<?php 

// app/Http/Middleware/SetDefaultGuard.php
namespace App\Http\Middleware;

use Closure; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Config;

class SetDefaultGuard 
{
  public function handle(Request $request, Closure $next, string $guard) 
  {

    Config::set('auth.defaults.guard', $guard);
    return $next($request);

  }
}
