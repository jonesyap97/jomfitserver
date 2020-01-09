<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class isAdmin
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
        if(Auth::user()->isAdmin == 0)
        {
            return redirect()->back();
        }
        elseif(Auth::user()->isAdmin == 1)
        {
            return $next($request);
        }

        else
        {
            dd('Guest');
        }
       
    }
}
