<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class InventoryMiddleware
{
  
/**
 * Handle an incoming request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure  $next
 * @param  string|null  $guard
 * @return mixed
 */
	public function handle($request, Closure $next, $guard = 'inventory')
	{
		if (!Auth::guard('inventory')->check()) {
			return redirect('inventory/login');
		}

		return $next($request);
	}
}
