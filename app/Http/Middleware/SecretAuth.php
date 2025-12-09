<?php

namespace App\Http\Middleware;

use Closure;

class SecretAuth
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
        // Pre-Middleware Action
        if($request->header('secret') != 'ADeuASAfdDsadkdakK5IJas3CQ5h0TfrWGbiyHbYHBBIblitndsadJAHSASA7632bnsys7cx3'){
            return response()->json(['message' => 'Secret Token Not Found.'], 404);
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
