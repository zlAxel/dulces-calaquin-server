<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {

        $tipoLogin = $this->input('login_type');
        $rules = [
            'email' => ['required', 'string', 'email'],
        ];
        
        if ( $tipoLogin == 'login' ){
            // ? Concatenamos el nuevo array con el anterior
            $rules = array_merge($rules, [
                'password' => ['required', 'string'],
            ]);
        }else
        if ( $tipoLogin == 'login_punto' ){
            $rules = array_merge($rules, [
                'pin' => ['required'],
            ]);
        }

        return $rules;
    }

    public function messages() {
        return [
            'email.required' => 'El correo electrónico no puede ir vacío',
            'email.string' => 'El correo electrónico debe ser una cadena de texto',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida',
            'password.required' => 'La contraseña no puede ir vacía',
            'password.string' => 'La contraseña debe ser una cadena de texto',
            'pin.required' => 'El pin de compras no puede ir vacío',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Attempt to authenticate the request's credentials of the phisic store.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate_punto(): void
    {

        $this->ensureIsNotRateLimited();

        if ( ! $this->customAuthentication($this->input('email'), $this->input('pin')) ) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }else{
            $user = User::where('email', $this->input('email'))->first();
            Auth::login($user, $this->boolean('remember'));
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
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

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }

    /**
     * Create a new custom authentication request.
     */
    private function customAuthentication($email, $pin)
    {
        $user = User::where('email', $email)->first();
        
        if ($user && Hash::check($pin, $user->pin)) {
            return true;
        }
        
        return false;
    }
}
