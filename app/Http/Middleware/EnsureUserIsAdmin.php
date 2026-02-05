<?php
// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;

// class EnsureUserIsAdmin
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if (! $request->user() || ! $request->user()->is_admin) {
//             // you can redirect to home with an error
//             return redirect('/')->with('error','Access denied.');
//         }
//         return $next($request);
//     }
// }



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is not authenticated, send them to the login page
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // If authenticated but not an admin, respond with 403 (forbidden)
        if (!auth()->user()->is_admin) {
            abort(403, 'You are not authorized to access the admin panel.');
        }

        return $next($request);
    }
}
