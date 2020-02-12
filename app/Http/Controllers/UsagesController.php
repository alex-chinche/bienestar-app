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
        //$appsFullInfo es el array que contiene los arrays de  name, date, status, longitude, latitude
        $appsFullInfo = $appsInfo->readCSVinfo($request);

        $arrayNames = array_values($appsFullInfo[0]);
        $arrayTimes = array_values($appsFullInfo[1]);
        $arrayStatus = array_values($appsFullInfo[2]);
        //dia del año Y/m/d
        //hora del dia H:i:s
        for ($i = 0; $i < count($arrayTimes); $i++) {
            $timeSaved = strtotime($arrayTimes[$i]);
            if ($arrayStatus[$i] == "opens") {
                $dayOfYearOpened = date("Y/m/d", $timeSaved);
                $dayHourOpened = date("H:i:s", $timeSaved);
            } else if ($arrayStatus[$i] == "closes") {
                $dayOfYearClosed = date("Y/m/d", $timeSaved);
                $dayHourClosed = date("H:i:s", $timeSaved);
                if ($dayOfYearOpened < $dayOfYearClosed) {
                    $dayToChange = date("Y/m/d", $timeSaved);
                    $dayMinusOne = strtotime('-1 day', strtotime($dayToChange));
                    $dayMinusOne = date("Y/m/d", $dayMinusOne);
                    //Seconds used before midnight
                    $timeBeforeMidnight = strtotime(86400) - strtotime($dayHourOpened);
                    $digitaltimeBeforeMidnight = date("H:i:s", $timeBeforeMidnight);
                    var_dump("Tiempo de uso de $arrayNames[$i] el dia $dayMinusOne es $digitaltimeBeforeMidnight");
                    //Seconds used after midnight
                    $timeAfterMidnight = strtotime($dayHourClosed);
                    $digitaltimeAfterMidnight = date("H:i:s", $timeAfterMidnight);
                    var_dump("Tiempo de uso de $arrayNames[$i] el dia $dayOfYearClosed es $digitaltimeAfterMidnight");
                } else {
                    $timeUsedPerDay = strtotime($dayHourClosed) - strtotime($dayHourOpened);
                    $digitalTimeUsedPerDay = date("H:i:s", $timeUsedPerDay);
                    var_dump("Tiempo de uso de $arrayNames[$i] el dia $dayOfYearClosed es $digitalTimeUsedPerDay");
                }
            }
        }



        /////////////////

        $gettingUser = new UserController;
        $userSummon = $gettingUser->getUserFromToken($request);
        $usage->user_id = $userSummon->id;
        $usage->application_id = 2;

        ////////////////

        $usage->date = "2019-11-18";
        $usage->time = "08:10:10";
        $usage->save();

        return response()->json([
            'message' => "App time used uploaded successfully"
        ], 200);
    }
}
