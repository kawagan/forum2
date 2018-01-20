<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfEmailNotConfirmed
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    // the user can't make thread without confirm email
    // we make it as middlware becs we maybe need it also in many places
    public function handle($request, Closure $next)
    {
            $user=$request->user();
            
            if(!$user->confirmed && !$user->isAdmin()){
                session()->push('m','warning');
                session()->push('m','you are not confirmed your E-mail.');
                return redirect()->back();
            }
        return $next($request);
    }
}
