<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'pin' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tu nombre no puede ir vacío',
            'name.string' => 'Tu nombre debe ser una cadena de texto',
            'name.max' => 'Tu nombre debe tener máximo 255 caracteres',
            'email.required' => 'El correo electrónico no puede ir vacío',
            'email.string' => 'El correo electrónico debe ser una cadena de texto',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida',
            'email.max' => 'El correo electrónico debe tener máximo 255 caracteres',
            'email.unique' => 'El correo electrónico ya está registrado',
            'password.required' => 'La contraseña no puede ir vacía',
            'password.string' => 'La contraseña debe ser una cadena de texto',
            'password.confirmed' => 'Las contraseñas escritas no coinciden',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres',
            'pin.required' => 'El pin de compras no puede ir vacío',
        ];
    }
}
