<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
 
    public function handle($request, Closure $next)
    {
        $local = $request->hasHeader('X-localization') ? $request->header('X-localization') : 'en';

        app()->setLocale($local);
        
        return $next($request);
    }

}