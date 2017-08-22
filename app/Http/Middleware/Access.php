<?php

namespace App\Http\Middleware;

use Closure;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        switch($role){
            case 'admin':
                if(!\Auth::user()){
                    if($request->ajax())
                        return response('Access Denied')->setStatusCode(403);
                    abort(404);
                }else
                if(!$request->user()->is_admin){
                    if($request->ajax())
                        return response('Access Denied')->setStatusCode(403);
                    abort(404);
                }
            break;
            case 'moderator':
                if(!\Auth::user()){
                    if($request->ajax())
                        return response('Access Denied')->setStatusCode(403);
                    abort(404);
                }else
                if(!$request->user()->is_moderator){
                    if($request->ajax())
                        return response('Access Denied')->setStatusCode(403);
                    abort(404);
                }
            break;
            default:
                return response('Access Denied')->setStatusCode(403);
            break;
        }



        return $next($request);
    }
}
