<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationsController;
use App\User;
use App\Application;
use App\Store;
use App\Usage;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function postLocations(Request $request)
    {
        try {
            $appsInfo = new ApplicationsController;
            $appsFullInfo = $appsInfo->readCSVinfo($request);
            $arrayNames = array_values(array_reverse($appsFullInfo[0]));
            $arrayStatus = array_values(array_reverse($appsFullInfo[2]));
            $arrayLatitude = array_values(array_reverse($appsFullInfo[3]));
            $arrayLongitude = array_values(array_reverse($appsFullInfo[4]));
            $arrayOpenLatitudes = [];
            $arrayOpenLongitudes = [];
            $arrayCloseLatitudes = [];
            $arrayCloseLongitudes = [];

            $arrayFinalNames = array_values(array_unique($arrayNames));
            $actualAppOnList = $arrayNames[0];

            for ($i = 0; $i < count($arrayFinalNames); $i++) {
                $actualFinalApp = $arrayFinalNames[$i];

                for ($j = 0; $j < count($arrayNames); $j++) {
                    $actualAppOnList = $arrayNames[$j];
                    if ($actualFinalApp == $actualAppOnList && $arrayStatus[$j] == "closes") {
                        array_push($arrayCloseLatitudes, $arrayLatitude[$j]);
                        array_push($arrayCloseLongitudes, $arrayLongitude[$j]);
                    } else if ($actualFinalApp == $actualAppOnList && $arrayStatus[$j] == "opens") {
                        array_push($arrayOpenLatitudes, $arrayLatitude[$j]);
                        array_push($arrayOpenLongitudes, $arrayLongitude[$j]);
                        break;
                    } else {
                    }
                }
                $user = new User;
                $userController = new UserController;
                $user = $userController->getUserFromToken($request);
                if (!Store::where("user_id", $user->id)->where("application_id", $i+1)) {
                    $store = new Store;
                    $store->user_id = $user->id;
                    $store->application_id = $i + 1;
                    $store->open_latitude = $arrayOpenLatitudes[$i];
                    $store->open_longitude = $arrayOpenLongitudes[$i];
                    $store->close_latitude = $arrayCloseLatitudes[$i];
                    $store->close_longitude = $arrayCloseLongitudes[$i];

                    $store->save();
                } else {
                    Store::where("user_id", $user->id)->where("application_id", $i+1)->delete();
                    $store = new Store;
                    $store->user_id = $user->id;
                    $store->application_id = $i + 1;
                    $store->open_latitude = $arrayOpenLatitudes[$i];
                    $store->open_longitude = $arrayOpenLongitudes[$i];
                    $store->close_latitude = $arrayCloseLatitudes[$i];
                    $store->close_longitude = $arrayCloseLongitudes[$i];

                    $store->save();
                }
            }
                return response()->json([
                    'message' => "Locations uploaded succesfully"
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Not possible to upload locations"
            ], 401);
        }
    }
}
