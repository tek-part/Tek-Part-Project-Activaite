<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'email'    => ['required'],
            'password' => ['required'],
        ];
    }

    public function messages(): array {
        return [
            'email.required'    => 'حقل البريد الالكترونى مطلوب',
            'password.required' => 'حقل كلمة المرور مطلوب',
        ];
    }

    public function authenticate($guard = 'web'): void {
        $this->ensureIsNotRateLimited();

        if (!Auth::guard($guard)->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            back()->with('error', 'البريد الالكترونى او كلمة المرور خطأ');

            throw ValidationException::withMessages([
                'message' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        back()->with('success', 'مرحبا بك فى النظام ' . auth('web')->user()->name);
    }

    public function ensureIsNotRateLimited(): void {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}