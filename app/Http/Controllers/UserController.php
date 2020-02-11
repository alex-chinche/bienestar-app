<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Application;
use App\Helpers\Token;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;

            $userAlreadyRegistered = User::where('email', $request->email)->first();

            if ($userAlreadyRegistered) {
                return response()->json([
                    'message' => "Email already registered"
                ], 401);
            } else if (strlen($request->password) < 5 && !$userAlreadyRegistered) {
                return response()->json([
                    'message' => "Password must be longer than 5"
                ], 401);
            } else {
                $user->save();
                $mainToken = new Token();
                $finalToken = $mainToken->set_token($user->email);
                return response()->json([
                    'token' => $finalToken
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Not Connected"
            ], 401);
        }
    }

    public function loginUser(Request $request)
    {
        $data = ['email' => $request->email];
        $user = User::where('email', $request->email)->first();

        try {
            if ($user->password == $request->password) {
                $token = new Token($data);
                $encoded_token = $token->set_token($user->email);
                return response()->json([
                    'token' => $encoded_token
                ], 200);
            } else {
                return response()->json([
                    'message' => "Incorrect password"
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Incorrect email"
            ], 401);
        }
    }

    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function rememberPassword(Request $request)
    {
        $data = ['email' => $request->email];
        $user = User::where('email', $request->email)->first();
        try {
            if ($user->email == $request->email) {
                $user->password = $this->generateRandomString(10);
                $newPass = ['password' => $user->password];
                User::where($data)->update($newPass);
                $to = $user->email;
                $subject = 'Password Recovery';
                $message = 'Here you have your new password: ' . $user->password;
                $headers = 'From: Bienestar-info@info.com' . "\r\n" .
                    'Reply-To: ' . $user->email . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
                return response()->json([
                    'message' => "Password changed correctly",
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Incorrect email"
            ], 401);
        }
    }
    public function getUserFromToken(Request $request)
    {
        $tokenSummon = new Token;
        $codedToken = $request->header("token");
        $emailFromToken = $tokenSummon->decode_token($codedToken);
        $user = User::where("email", $emailFromToken)->first();

        return $user;
    }
}
