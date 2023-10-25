<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): Response
    {
        // ? Obtenemos el password encriptado y lo desencriptamos
        $password_crypt   = $request->password;
        $password_decrypt = Crypt::decrypt($password_crypt, unserialize: false);
        
        // ? Obtenemos el pin encriptado y lo desencriptamos
        $pin_crypt     = $request->pin;
        $pin_decrypt   = Crypt::decrypt($pin_crypt, unserialize: false);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $password_decrypt ),
            'pin' => Hash::make( $pin_decrypt ),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
