<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\models\party;
use Illuminate\Support\Facades\DB;
class VerificationController extends Controller
{
    public function verify(Request $request) {
        $userId=$request->userId;
        $user = User::findOrFail($userId);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return redirect()->to('/');
    }
    public function resend(Request $request) {
        $email=$request->email;
        $user=User::where('email', $email)->firstOrFail();
        if ($user->hasVerifiedEmail()) {
            return response()->json(["msg" => "Email already verified."], 400);
        }
        $user->sendEmailVerificationNotification();
        return response()->json(["msg" => "Email verification link sent on your email id"],400);
    }
}
