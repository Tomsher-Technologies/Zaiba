<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsUser
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
        if (
            Auth::check() &&
            (Auth::user()->user_type == 'customer'
                // || Auth::user()->user_type == 'seller' ||
                //     Auth::user()->user_type == 'delivery_boy'
            )
        ) {

            return $next($request);
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response([
                    'error' => 'unauthorized',
                    'error_description' => 'Failed authentication.',
                    'data' => [],
                ], 401);
            }
            session(['link' => url()->current()]);
            return redirect()->route('user.login');
        }
    }
}
