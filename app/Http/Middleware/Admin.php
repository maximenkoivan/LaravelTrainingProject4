<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if(User::find($request->id)) {
            if (Auth::id() == $request->id || Auth::user()->admin) {

                    return $next($request);
            }
        }
        abort(404);
    }
}
