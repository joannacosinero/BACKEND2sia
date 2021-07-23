<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $patient = $request->route('patient');

        if($patient==null) {
            return response()->json(['message'=>'The Parient Record cannot be found '], 404);   
        }

        if($patient->user_id != auth()->user()->id) {
            return response()->json(['message'=>'You are not the owner of this Patient Record'], 401);
        }
        return $next($request);
    }
}
