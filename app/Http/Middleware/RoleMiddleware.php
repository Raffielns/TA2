<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role;
        $allowedRoles = explode(',', $role);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
