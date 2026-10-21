<?php

namespace App\Http\Middleware;

use Closure;

class cek_level
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
        if(auth()->user()->level == "mahasiswa" || auth()->user()->level == "dosen" || auth()->user()->level == "admin")
        {
            return $next($request);
        }
        else{
            return redirect()->route('/');
        }
    }
}
