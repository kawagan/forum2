<?php

namespace App\Http\Middleware;

use Closure;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // i use it to see if user can lock a thread or not 
    public function handle($request, Closure $next)
    {
        if(auth()->user() && auth()->user()->isAdmin()){
            return $next($request);
        }
        abort(403, 'you are not Admin to lock a Thread');
            

    }
}
