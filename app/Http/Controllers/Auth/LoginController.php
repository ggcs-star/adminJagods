<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Gloudemans\Shoppingcart\Facades\Cart;

class LoginController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;

    public $data;

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'verifyOtp', 'resendOtp']);
    }

    public function showLoginForm()
    {
        $this->data['site_title'] = 'login';

        return view('auth.login', $this->data);
    }

    /**
     * Custom Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        // Check email + password
        if (!Auth::validate($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput($request->only('email'));
        }

        // Get user
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'User not found.',
                ])
                ->withInput($request->only('email'));
        }

        // Check account status
        if ($user->status != 5) {
            return back()
                ->withBlock(
                    'Your account currently inactive. you can\'t login our system.'
                )
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Admin Email Verification
        |--------------------------------------------------------------------------
        */

        $adminVerifyEmail = config('services.admin_verification.email');

        if (!empty($adminVerifyEmail)) {

            $otp = random_int(100000, 999999);

            Cache::put(
                'login_otp_' . $user->id,
                $otp,
                now()->addMinutes(5)
            );

            session([
                'pending_login_user_id' => $user->id,
                'pending_login_email'   => $user->email,
                'pending_login_type'    => $request->type,
            ]);

            Mail::raw(
                "Login verification OTP is: {$otp}\n\n"
                    . "Login email: {$user->email}\n"
                    . "This OTP will expire in 5 minutes.",
                function ($message) use ($adminVerifyEmail) {
                    $message
                        ->to($adminVerifyEmail)
                        ->subject('Login Verification OTP');
                }
            );

            return redirect()
                ->route('login.otp')
                ->withSuccess('OTP has been sent for login verification.');
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Login
        |--------------------------------------------------------------------------
        */

        Auth::login($user, $request->filled('remember'));

        $request->session()->regenerate();

        return $this->redirectAfterLogin($request);
    }

    /**
     * OTP Verification Page
     */
    public function showOtpForm()
    {
        if (!session()->has('pending_login_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.login-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = session('pending_login_user_id');

        if (!$userId) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Login session expired. Please login again.',
                ]);
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            session()->forget([
                'pending_login_user_id',
                'pending_login_email',
                'pending_login_type',
            ]);

            return redirect()->route('login');
        }

        $storedOtp = Cache::get('login_otp_' . $userId);

        if (!$storedOtp || (string) $storedOtp !== (string) $request->otp) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.',
            ]);
        }

        // OTP verified
        Cache::forget('login_otp_' . $userId);

        $loginType = session('pending_login_type');

        session()->forget([
            'pending_login_user_id',
            'pending_login_email',
            'pending_login_type',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        if ($loginType === 'admin') {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->route('home');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $userId = session('pending_login_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        $otp = random_int(100000, 999999);

        Cache::put(
            'login_otp_' . $user->id,
            $otp,
            now()->addMinutes(5)
        );

        Mail::raw(
            "Your new login verification OTP is: {$otp}\n\nThis OTP will expire in 5 minutes.",
            function ($message) use ($user) {
                $message
                    ->to($user->email)
                    ->subject('Login Verification OTP');
            }
        );

        return back()->withSuccess('New OTP has been sent to your email.');
    }

    /**
     * Redirect after normal login
     */
    protected function redirectAfterLogin(Request $request)
    {
        if ($request->type === 'admin') {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->route('home');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
