<?php

namespace App\Http\Middleware;

use App\Models\VendorDueDiligence\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
class VendorIsAuthenticated extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
