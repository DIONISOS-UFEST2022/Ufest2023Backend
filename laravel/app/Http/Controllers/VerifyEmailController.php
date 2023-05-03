<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    public function verify($id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return request()->wantsJson()
                ? new JsonResponse('', 204)
                : redirect(url(env('APP_FRONTEND_URL'), '?verified=1'));
        }
        return request()->wantsJson()
            ? new JsonResponse('', 204)
            : redirect(url(env('APP_FRONTEND_URL'), '?verified=1'));
    }

    public function resend()
    {
        request()->user()->sendEmailVerificationNotification();
        return response()->json(
            [
                'msg' => 'Request has been sent',
            ]
        );
    }
}
