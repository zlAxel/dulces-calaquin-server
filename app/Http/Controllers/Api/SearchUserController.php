<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchUserController extends Controller{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request){

        $this->validate($request, [
            'email' => 'required|email'
        ],[
            'email.required' => 'Por favor escriba su correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.'
        ]);
        
        // ? Buscamos el usuario por su email
        $user = User::where('email', $request->email)->first();

        // ? Si no existe el usuario, retornamos un booleano false
        if( ! $user ){
            return "false";
        }else{
            return "true";
        }
    }
}
