<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Helpers\Token;

class CheckToken
{
    public function handle($request, Closure $next)
    {
        try {
            $saved_token = new Token();
            $token = $request->header('Authorization');
            $verified_email = $saved_token->decode_token($token);
            $received_user = User::where('email', $verified_email)->first();
            $received_email = $received_user->email;
            if ($received_email == $verified_email) {
                return $next($request);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "access unavailable"
            ], 401);
        }
    }
}
