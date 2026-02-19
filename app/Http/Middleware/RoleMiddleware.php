<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;



class RoleMiddleware
{
     public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
    return redirect()->route('login');
}

$user = Auth::user();

        $userRole = $request->user()->role ? $request->user()->role->name : null;

        if (! in_array($userRole, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
