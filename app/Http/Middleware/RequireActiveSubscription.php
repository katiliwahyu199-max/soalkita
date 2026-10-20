<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->isAdmin() || $user->hasActiveSubscription()) {
            return $next($request);
        }

        return redirect()->route('subscription.index')
            ->with('warning', 'Masa aktif Anda telah berakhir. Silakan ajukan pembayaran untuk melanjutkan.');
    }
}