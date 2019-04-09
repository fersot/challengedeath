<?php

namespace App\Http\Middleware;

use Closure;

class CheckBearer
{

    public function handle($request, Closure $next)
    {
        if($request->headers->has('Authorization')){
            return $next($request);
        }else{
          abort(401);
        }
    }
}
