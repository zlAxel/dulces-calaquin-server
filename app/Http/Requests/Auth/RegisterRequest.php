<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
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
        ];
    }

    // ? Validamos manualmente que la contraseña y la confirmación de la contraseña sean iguales
    public function withValidator($validator) {
        $validator->after(function ($validator) {
            // ? Obtenemos el password encriptado y lo desencriptamos
            $password_crypt   = $this->input('password');
            $password_decrypt = Crypt::decrypt($password_crypt, unserialize: false);
            
            // ? Obtenemos el password de confirmación encriptado y lo desencriptamos
            $password_confirmation_Crypt   = $this->input('password_confirmation');
            $password_confirmation_decrypt = Crypt::decrypt($password_confirmation_Crypt, unserialize: false);

            // ? Obtenemos el pin encriptado y lo desencriptamos
            $pin_crypt     = $this->input('pin');
            $pin_decrypt   = Crypt::decrypt($pin_crypt, unserialize: false);

            // ? Validamos que la contraseña no vaya vacía
            if ( $password_decrypt == "" || $password_confirmation_decrypt == "" ){
                $validator->errors()->add('password', 'Las contraseñas deben estar escritas.');
            }
            // ? Validamos que la contraseña y la confirmación de la contraseña no vayan vacías
            if ( $password_decrypt != "" && $password_confirmation_decrypt != "" ){
                // * Validamos que la contraseña cumpla con los requisitos de seguridad
                if ( ! preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', $password_decrypt) ) {
                    $validator->errors()->add('password', 'La contraseña debe tener al menos 1 letra mayúscula, 1 letra minúscula, 1 número y 1 carácter especial.');
                } else {
                    // * Validamos que la contraseña y la confirmación de la contraseña sean iguales
                    if ( $password_decrypt !== $password_confirmation_decrypt ) {
                        $validator->errors()->add('password', 'Las contraseñas escritas no coinciden.');
                    }
                }
            }
            // ? Validamos que el pin no vaya vacío
            if ( $pin_decrypt == "" ){
                $validator->errors()->add('pin', 'El pin de compras no puede ir vacío.');
            }
        });
    }

    public function messages()
    {
        return [
            'name.required' => 'Tu nombre no puede ir vacío.',
            'name.string' => 'Tu nombre debe ser una cadena de texto.',
            'name.max' => 'Tu nombre debe tener máximo 255 caracteres.',
            'email.required' => 'El correo electrónico no puede ir vacío.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.max' => 'El correo electrónico debe tener máximo 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña no puede ir vacía.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
            'pin.required' => 'El pin de compras no puede ir vacío.',
        ];
    }
}
