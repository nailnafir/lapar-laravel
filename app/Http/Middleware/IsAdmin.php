<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        // membuat validasi apakah user sedang login dan rolesnya admin
        if (Auth::user() && Auth::user()->roles == 'ADMIN') {
            return $next($request);
        } else {
            $request->session()->flush();
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Role anda bukan "ADMIN"',
                'description' => 'Anda tidak dapat mengakses halaman pengelolaan data',
            ]);
        }
    }
}
