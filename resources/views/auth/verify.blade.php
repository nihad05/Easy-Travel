@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
        <div style="width: 100%; max-width: 500px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 30px;">
            <h2 style="text-align: center; margin-bottom: 20px;">Verify Your Email</h2>

            @if (session('resent'))
                <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    A new verification code has been sent to your email address.
                </div>
            @endif

            <p style="text-align: center; margin-bottom: 25px;">
                Please enter the 5-digit verification code sent to your email.
            </p>

            <form method="POST" action="">
                @csrf

                <div style="margin-bottom: 15px;">
                    <input type="text"
                           name="verification_code"
                           placeholder="Enter 5-digit code"
                           maxlength="5"
                           pattern="\d{5}"
                           required
                           style="width: 100%; padding: 12px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; text-align: center;">

                    @error('verification_code')
                    <div style="color: red; margin-top: 5px; text-align: center;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div style="text-align: center;">
                    <button type="submit"
                            style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                        Verify Code
                    </button>
                </div>
            </form>

            <form method="POST" action="" style="margin-top: 20px; text-align: center;">
                @csrf
                <button type="submit"
                        style="background: none; border: none; color: #007bff; cursor: pointer; font-size: 14px;">
                    Didn't get the code? Resend
                </button>
            </form>
        </div>
    </div>
@endsection
