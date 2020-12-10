<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    //

    public function login(AuthenticationRequest $request)
    {
        $http = new Client();
        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ]
            ]);
            return $response->getBody();


        } catch (BadResponseException $e) {
            $code = $e->getCode();
            if ($code === 400) {
                return response()->json(['code' => $code, 'message' => 'Invalid Request'], $code);
            } elseif ($code === 401) {
                return response()->json(['code' => $code, 'message' => 'Your credential is not correct'], $code);
            }
            return response()->json(['code' => $code, 'message' => 'some wrong with server'], $code);
        }
    }



    public function register(AuthenticationRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json(['data' => $user], 201);
    }



    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('logout successfully', 200);

    }

    public function getUserInfo()
    {
        $user = Auth::user();
        if ($user) {
            return new UserResource($user);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

    }




}
