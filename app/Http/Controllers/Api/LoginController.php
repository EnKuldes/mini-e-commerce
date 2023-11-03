<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
            , 'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation fail.', $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => '1'])) {
            $user = Auth::user();
            $user['token'] = $user->createToken('mini-e-commerce')->accessToken;
            return $this->sendResponse($user, 'success login', 200);
        }

        return $this->sendErrorResponse('Fail to login.', [
            'email' => 'The provided credentials do not match our records.',
        ], 402);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse([], 'success logout', 200);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user['token'] = $user->token()->accessToken;
        return $this->sendResponse($user, 'success retieve user data', 200);
    }


    // ========
    private function sendResponse($data, $message, $successCode = 200)
    {
        return response()->json([
            'success' => true
            , 'data' => $data
            , 'message' => $message
        ], $successCode);
    }

    private function sendErrorResponse($error, $errorMessage = [], $errorCode = 404)
    {
        return response()->json([
            'success' => false
            , 'data' => $errorMessage
            , 'message' => $error
        ], $errorCode);
    }
}
