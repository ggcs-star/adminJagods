<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;

    public $data;

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'verifyOtp',
            'resendOtp',
            'loginWithCode',
            'sendOtp',
        ]);
    }

    public function showLoginForm()
    {
        $this->data['site_title'] = 'login';

        return view('auth.login', $this->data);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::validate($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput($request->only('email'));
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'User not found.',
                ])
                ->withInput($request->only('email'));
        }

        if ($user->status != 5) {
            return back()
                ->withBlock(
                    'Your account currently inactive. you can\'t login our system.'
                )
                ->withInput();
        }
        session([
            'pending_login_user_id' => $user->id,
            'pending_login_email' => $user->email,
            'pending_login_type' => $request->type,
            'pending_login_remember' => $request->filled('remember'),
        ]);

        return redirect()->route('login.method');
    }
    public function showLoginMethod()
    {
        if (!session()->has('pending_login_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.login-method');
    }

    public function loginWithCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
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
                'pending_login_remember',
            ]);

            return redirect()->route('login');
        }

        $configuredCode = (string) config('services.login.access_code');
        $enteredCode = (string) $request->code;

        if (
            empty($configuredCode) ||
            !hash_equals($configuredCode, $enteredCode)
        ) {
            return back()->withErrors([
                'code' => 'Invalid login code.',
            ]);
        }

        $loginType = session('pending_login_type');
        $remember = session('pending_login_remember', false);

        session()->forget([
            'pending_login_user_id',
            'pending_login_email',
            'pending_login_type',
            'pending_login_remember',
        ]);

        Auth::login($user, $remember);

        $request->session()->regenerate();

        return $this->redirectAfterLogin(
            $request,
            $loginType
        );
    }

    public function sendOtp(Request $request)
    {
        $userId = session('pending_login_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        $adminVerifyEmail = config('services.admin_verification.email');

        if (empty($adminVerifyEmail)) {
            return back()->withErrors([
                'code' => 'OTP verification email is not configured.',
            ]);
        }

        $otp = random_int(100000, 999999);

        Cache::put(
            'login_otp_' . $user->id,
            $otp,
            now()->addMinutes(5)
        );

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

    /**
     * OTP page
     */
    public function showOtpForm()
    {
        if (!session()->has('pending_login_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.login-otp');
    }
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
                'pending_login_remember',
            ]);

            return redirect()->route('login');
        }

        $storedOtp = Cache::get('login_otp_' . $userId);

        if (!$storedOtp || (string) $storedOtp !== (string) $request->otp) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.',
            ]);
        }

        Cache::forget('login_otp_' . $userId);

        $loginType = session('pending_login_type');
        $remember = session('pending_login_remember', false);

        session()->forget([
            'pending_login_user_id',
            'pending_login_email',
            'pending_login_type',
            'pending_login_remember',
        ]);

        Auth::login($user, $remember);

        $request->session()->regenerate();

        return $this->redirectAfterLogin(
            $request,
            $loginType
        );
    }

    public function resendOtp(Request $request)
    {
        return $this->sendOtp($request);
    }

    protected function redirectAfterLogin(
        Request $request,
        ?string $loginType = null
    ) {
        if ($loginType === 'admin') {
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
