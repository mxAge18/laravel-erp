<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    //

    public function login(AuthenticationRequest $request)
    {
        $loginInfo['email'] = $request->username;
        $loginInfo['password'] = $request->password;
        if (Auth::attempt($loginInfo)) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('laravel-erp')->accessToken;
            return response()->json(['success' => $success], 201);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }


    public function register(AuthenticationRequest $request)
    {
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $success['token'] =  $user->createToken('laravel-erp')->accessToken;
        return response()->json(['success' => $success], 201);
    }

    public function captcha()
    {

    }


    public function logout()
    {

    }

}
