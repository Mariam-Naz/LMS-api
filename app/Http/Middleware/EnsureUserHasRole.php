<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if($user->Role->name != $role && $user->Role->name != 'admin') {
            $response = [
                'status' => 2,
                'message' => 'Unauthorized',
            ];

            return redirect('/api/login');
        }
        return $next($request);
    }
}
