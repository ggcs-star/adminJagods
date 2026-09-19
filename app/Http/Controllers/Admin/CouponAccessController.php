<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\BackendController;
class CouponAccessController extends BackendController
{
    public function show()
    {
        return view('admin.coupon.access');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        if ($request->code !== config('services.coupon_access.code')) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' => 'Invalid access code.',
                ]);
        }

        return redirect()
            ->route('admin.coupon.index')
            ->withCookie(
                cookie(
                    'coupon_access_granted',
                    '1',
                    0
                )
            );
    }
}