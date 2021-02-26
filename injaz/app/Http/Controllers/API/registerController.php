<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BassController as BassController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class registerController extends BassController
{
    use HasApiTokens;

    //for register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Your information is not correct', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('fatimah')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'You registered successfully');
    }


    //for login
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('fatimah')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'You logged in successfully');
        } else {
            return $this->sendError('Unauthorised', ['error', 'Unauthorised']);
        }
    }
}






