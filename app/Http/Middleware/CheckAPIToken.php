<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;

class CheckAPIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('X-APITOKEN');

        // echo $header;
        $is_exists = User::where('api_token', $header)->exists();
        if ($is_exists) {
            return $next($request);
        }

        return response()->json('Invalid Token', 401);
    }
}
