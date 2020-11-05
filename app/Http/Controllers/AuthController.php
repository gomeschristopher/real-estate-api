<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->all('email', 'password');

            Validator::make($credentials, [
                'email' => 'required|string',
                'password' => 'required|string',
            ])->validate();

            if (!$token = Auth::attempt($credentials)) {
                throw new Exception("UNAUTHORIZE");
            }

            return response()->json([
                'idToken' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }

    public function logout()
    {
        try {
            return response()->json(Auth::logout(), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }

    public function refresh()
    {
        try {
            return response()->json([
                'idToken' => Auth::refresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }
}
