<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationsController;
use App\User;
use App\Application;
use App\Usage;
use App\Restrict;

class RestrictsController extends Controller
{
    public function postRestrictions(Request $request)
    {
        try {
            $user = new User;
            $userController = new UserController;
            $user = $userController->getUserFromToken($request);

            if (!Restrict::where("user_id", $user->id)->where("application_id", $request->application_id)) {
                $restriction = new Restrict;
                $restriction->user_id = $user->id;
                $restriction->application_id = $request->application_id;
                $restriction->max_possible_hour = $request->max_possible_hour;
                $restriction->min_possible_hour = $request->min_possible_hour;
                $restriction->max_time_used = $request->max_time_used;

                $restriction->save();
            } else {
                Restrict::where("user_id", $user->id)->where("application_id", $request->application_id)->delete();

                $restriction = new Restrict;
                $restriction->user_id = $user->id;
                $restriction->application_id = $request->application_id;
                $restriction->max_possible_hour = $request->max_possible_hour;
                $restriction->min_possible_hour = $request->min_possible_hour;
                $restriction->max_time_used = $request->max_time_used;

                $restriction->save();
            }

            return response()->json([
                'message' => "Restriction created succesfully"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Not possible to upload data"
            ], 401);
        }
    }

    public function getRestrictions(Request $request)
    {
        try {
            $user = new User;
            $userController = new UserController;
            $user = $userController->getUserFromToken($request);
            $restrictsGot = Restrict::where("user_id", $user->id)->get();

            $usageGotArray = $restrictsGot->toArray();

            $totalRestrictionsArray = [];

            for ($i = 0; $i < count($usageGotArray); $i++) {
                $totalRestrictionsArray[] = ['application_id' => $usageGotArray[$i]['application_id'], 'max_possible_hour' => $usageGotArray[$i]['max_possible_hour'], 'min_possible_hour' => $usageGotArray[$i]['min_possible_hour'], 'max_time_used' => $usageGotArray[$i]['max_time_used']];
            }

            return response()->json(
                $totalRestrictionsArray,
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Not possible to get restrictions"
            ], 401);
        }
    }
}
