@extends('frontend.layouts.app')

@section('main-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card">
                    <div class="card-header">
                        Login Verification
                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <p>
                            Verification OTP has been sent to your registered email.
                        </p>

                        <form method="POST" action="{{ route('login.otp.verify') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="otp">
                                    Enter OTP
                                </label>

                                <input type="text" name="otp" id="otp" maxlength="6" inputmode="numeric"
                                    class="form-control @error('otp') is-invalid @enderror" placeholder="Enter 6 digit OTP"
                                    required>

                                @error('otp')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Verify OTP
                            </button>
                        </form>

                        <form method="POST" action="{{ route('login.otp.resend') }}" class="mt-3">
                            @csrf

                            <button type="submit" class="btn btn-link p-0">
                                Resend OTP
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
