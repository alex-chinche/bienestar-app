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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        $token1 = new Token();
        $token2 = $token1->set_token($user->email);

        return response()->json([
            'token' => $token2
        ], 200);
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
                    'message' => "logged correctly",
                    'token' => $encoded_token
                ], 200);
            } else {
                return response()->json([
                    'message' => "incorrect password"
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "incorrect email"
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
                    'message' => "password changed correctly",
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "incorrect email"
            ], 401);
        }
    }    
}
