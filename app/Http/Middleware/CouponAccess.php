<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CouponAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->cookie('coupon_access_granted')) {
            return redirect()->route('admin.coupon.access.form');
        }

        return $next($request);
    }
}