<?php

namespace App\Http\Controllers\Api;

use App\User;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;

class UserController extends ApiController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Registration failed', $validator->errors(), 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token['token'] =  $user->createToken('RestA')->accessToken; 
        $token['token_type'] =  'Bearer';
        
        return $this->sendResponse(['user' => $user, 'token' => $token], 'Successfully created user!', 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Registration failed', $validator->errors(), 200);
        }

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return $this->sendError('Unauthorized', [], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('RestA');
        $token = $tokenResult->token;
        
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $result['access_token'] = $tokenResult->accessToken;
        $result['token_type'] = 'Bearer';
        $result['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

        return $this->sendResponse($result, 'Successfully Login!');
    }
  
    public function logout(Request $request)
    {
        return $this->sendResponse($request->user()->token()->revoke(), 'Successfully logged out');
    }
  
    public function details(Request $request)
    {
        return response()->json($request->user());
    }
}
