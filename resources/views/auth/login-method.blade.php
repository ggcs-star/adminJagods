@extends('frontend.layouts.app')

@section('main-content')

    <section class="login-hero">

        <div class="container-fluid">

            <div class="row my-2 min-vh-90.5 align-items-center justify-content-end">

                <div class="col-12 col-lg-6 d-flex justify-content-center min-vh-90.5">

                    <div class="auth-content login-box">

                        {{-- HEADER --}}
                        <div class="auth-header text-center">

                            <h3 class="text-white">
                                {{ __('Login Verification') }}
                            </h3>

                            <p class="text-white-50">
                                {{ __('Choose how you want to verify your login') }}
                            </p>

                        </div>


                        {{-- USER INFO --}}
                        @php
                            $email = session('pending_login_email');
                        @endphp

                        @if ($email)
                            <div class="text-center text-white mb-4">
                                <small class="text-white-50">
                                    {{ __('Login email') }}
                                </small>

                                <div class="fw-semibold mt-1">
                                    {{ $email }}
                                </div>
                            </div>
                        @endif


                        {{-- CODE LOGIN --}}
                        <form method="POST" action="{{ route('login.code') }}">
                            @csrf

                            <div class="form-group mb-3">

                                <label for="code" class="form-label text-white">
                                    {{ __('Login Code') }}
                                </label>

                                <input
                                    type="password"
                                    name="code"
                                    id="code"
                                    class="form-control @error('code') is-invalid @enderror"
                                    placeholder="Enter login code"
                                    autocomplete="off"
                                    required
                                >

                                @error('code')
                                    <span class="text-danger d-block mt-2">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                            <button
                                type="submit"
                                class="form-btn"
                            >
                                {{ __('Login with Code') }}
                            </button>

                        </form>


                        {{-- OR --}}
                        <div class="or-divider">

                            <span>
                                {{ __('OR') }}
                            </span>

                        </div>


                        {{-- OTP LOGIN --}}
                        <form method="POST" action="{{ route('login.otp.send') }}">

                            @csrf

                            <button
                                type="submit"
                                class="otp-btn"
                            >
                                {{ __('Login with OTP') }}
                            </button>

                        </form>


                        {{-- BACK --}}
                        <div class="text-center mt-4">

                            <a
                                href="{{ route('login') }}"
                                class="back-login"
                            >
                                ← {{ __('Back to Login') }}
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <style>

        /* =========================
           BACKGROUND
        ========================= */

        .login-hero {
            position: relative;
            min-height: 90.5vh;

            background:
                url('{{ asset('frontend/images/auth-bg.png') }}')
                center/cover no-repeat;

            display: flex;
            align-items: center;

            overflow: hidden;
        }


        .login-hero .container-fluid {
            position: relative;
            z-index: 1;
            height: 92.5vh;
        }


        /* =========================
           LOGIN BOX
        ========================= */

        .auth-content {
            height: auto;
        }

        .login-box {
            width: 90.5%;
            max-width: 650px;

            padding: 40px 45px;

            border-radius: 25px;

            background: rgba(255, 255, 255, 0.08);

            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);

            border: 2px solid rgba(255, 255, 255, 0.35);

            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.25),
                inset 0 0 0 1px rgba(255, 255, 255, 0.15);

            color: #ffffff;
        }


        /* =========================
           HEADER
        ========================= */

        .auth-header {
            padding: 10px 0 20px;
            text-align: center;
        }

        .auth-header h3 {
            line-height: 34px;
            margin-bottom: 8px;

            font-size: 32px;
            font-weight: 600;
        }

        .auth-header p {
            line-height: 20px;
            font-size: 14px;
            font-weight: 300;
        }


        /* =========================
           INPUT
        ========================= */

        .login-box .form-label {
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
        }

        .login-box .form-control {
            background: rgba(255, 255, 255, 0.15);

            border: 1px solid rgba(255, 255, 255, 0.4);

            color: #ffffff;

            border-radius: 12px;

            padding: 12px 14px;

            height: 50px;
        }

        .login-box .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .login-box .form-control:focus {
            background: rgba(255, 255, 255, 0.18);

            border-color: rgba(255, 255, 255, 0.8);

            color: #ffffff;

            box-shadow: none;
        }


        /* =========================
           MAIN BUTTON
        ========================= */

        .form-btn {
            background-color: #6C34CC !important;

            color: #ffffff;

            border: 2px solid rgba(255, 255, 255, 0.35);

            width: 100%;

            padding: 12px;

            border-radius: 6px;

            font-weight: 600;

            transition: 0.3s;
        }

        .form-btn:hover {
            background-color: #5528a8 !important;

            color: #ffffff;
        }


        /* =========================
           OR DIVIDER
        ========================= */

        .or-divider {
            display: flex;

            align-items: center;

            gap: 15px;

            margin: 25px 0;

            color: rgba(255, 255, 255, 0.65);

            font-size: 14px;

            text-align: center;
        }

        .or-divider::before,
        .or-divider::after {
            content: "";

            flex: 1;

            height: 1px;

            background: rgba(255, 255, 255, 0.3);
        }


        /* =========================
           OTP BUTTON
        ========================= */

        .otp-btn {
            width: 100%;

            padding: 12px;

            border-radius: 6px;

            background: transparent;

            color: #ffffff;

            border: 2px solid rgba(255, 255, 255, 0.5);

            font-weight: 600;

            transition: 0.3s;
        }

        .otp-btn:hover {
            background: rgba(255, 255, 255, 0.12);

            border-color: #ffffff;

            color: #ffffff;
        }


        /* =========================
           BACK
        ========================= */

        .back-login {
            color: rgba(255, 255, 255, 0.85);

            text-decoration: none;

            font-size: 14px;

            transition: 0.3s;
        }

        .back-login:hover {
            color: #ffffff;

            text-decoration: underline;
        }


        /* =========================
           ERROR
        ========================= */

        .text-danger {
            font-size: 13px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 992px) {

            .row {
                justify-content: center !important;
            }

            .login-box {
                margin: 40px 15px;

                padding: 30px;
            }

        }


        @media (max-width: 576px) {

            .login-box {
                width: 95%;

                padding: 25px 20px;
            }

            .auth-header h3 {
                font-size: 26px;
            }

        }

    </style>

@endsection