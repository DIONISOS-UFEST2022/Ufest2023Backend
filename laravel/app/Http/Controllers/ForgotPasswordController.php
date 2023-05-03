<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;




class ForgotPasswordController extends Controller
{
    public function sendToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'msg' => __($status)
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => [trans($status)]
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'string:8|confirmed|required',
        ]);
        $status = Password::reset(
            $credential,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        return response()->json([
            'msg' => __($status)
        ]);
    }

    public function getToken($token)
    {
        if (!$token) {
            return response()->json([
                'success' => false,
            ]);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
}
