<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationsController;
use App\User;
use App\Application;
use App\Usage;
use Illuminate\Http\Request;


class UsagesController extends Controller
{
    public function postUseTimes(Request $request)
    {
        $usage = new Usage;
        $appsInfo = new ApplicationsController;

        $appsFullInfo = $appsInfo->readCSVinfo($request);
        $arrayNames = array_values($appsFullInfo[0]);
        $arrayTimes = array_values($appsFullInfo[1]);
        $arrayStatus = array_values($appsFullInfo[2]);
        for ($i = 0; $i < count($arrayTimes); $i++) {
            if ($arrayStatus[$i] = "opens") {
                $timeSaved = strtotime($arrayTimes[$i]);
                var_dump("$timeSaved abierto");
            }
            else if ($arrayStatus[$i] = "closes") {
                var_dump("$timeSaved cerrado");
            }
        }





        $gettingUser = new UserController;
        $userSummon = $gettingUser->getUserFromToken($request);
        $usage->user_id = $userSummon->id;
        $usage->application_id = 2;

        //foreach ($appsFullInfo as $key => $value) {

        // }

        $usage->date = "2019-11-18";
        $usage->time = "08:10:10";
        $usage->save();

        return response()->json([
            'message' => "App time used uploaded successfully"
        ], 200);
    }
}
