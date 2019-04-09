<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    public function handle($request, Closure $next, $permission)
    {

        $permission = (int)$permission;

        if (auth()->check()) {

            $permissions = [];
            foreach (auth()->user()->roles as $rol) {
                foreach($rol->permissions as $perm){
                   if(!in_array($perm->id,$permissions)){
                       $permissions[] = $perm->id;
                   }
                }
            }

            if (in_array($permission, $permissions)) {
                return $next($request);
            } else {
                return response()->json([
                    'message' => 'Unauthorized'], 403);
            }
        }
    }
}
