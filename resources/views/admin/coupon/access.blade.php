@extends('admin.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-6">

        <h2 class="text-xl font-semibold mb-2">
            Coupon Access
        </h2>

        <p class="text-sm text-gray-500 mb-6">
            Please enter the access code to continue.
        </p>

        <form action="{{ route('admin.coupon.access.verify') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                    Access Code
                </label>

                <input
                    type="password"
                    name="code"
                    class="w-full border rounded-lg px-3 py-2"
                    placeholder="Enter access code"
                    autofocus
                >

                @error('code')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-white rounded-lg px-4 py-2"
            >
                Continue
            </button>
        </form>

    </div>
</div>
@endsection