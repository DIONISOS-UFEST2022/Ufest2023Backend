<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;
use App\Http\Resources\PanitiaResource;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msg' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
			'login_token' => $user->createToken('userLogin')->plainTextToken,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'msg' => 'you have successfuly logout.'
        ], 201);
    }

    public function me()
    {
		$user = Auth::user();
		$panitia = $user->panitia;
		if($panitia) {
			$panitia = new PanitiaResource($panitia);
		}
		if($user) {
			return response()->json([
				'success' => true,
				'user' =>  new UserResource($user),
				'panitia' => $panitia,
			], 201);
		}
		return response()->json([
				'success' => false,
				'msg' =>  'something when wrong please try again'
			], 404);
    }
}
