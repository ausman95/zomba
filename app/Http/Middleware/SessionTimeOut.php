<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionTimeOut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {
        // session()->forget('lastActivityTime');

        if (! session()->has('lastActivityTime')) {
            session(['lastActivityTime' => now()]);
        }

        // dd(
        //     session('lastActivityTime')->format('Y-M-jS h:i:s A'),
        //     now()->diffInMinutes(session('lastActivityTime')),
        //     now()->diffInMinutes(session('lastActivityTime')) >= config('session.lifetime')
        // );

        if (now()->diffInMinutes(session('lastActivityTime')) >= (2) ) {  // also you can this value in your config file and use here
            if (auth()->check() && auth()->id() > 1) {
                $user = auth()->user();
                auth()->logout();

                $user->update(['is_logged_in' => false]);
                $this->reCacheAllUsersData();

                session()->forget('lastActivityTime');

                return redirect(route('users.login'));
            }

        }
        session(['lastActivityTime' => now()]);

        return $next($request);
    }

}
