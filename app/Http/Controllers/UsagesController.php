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
        //crear usage $usage = new Usage;
        $appsInfo = new ApplicationsController;
        //$appsFullInfo es el array que contiene los arrays de  name, date, status, longitude, latitude
        $appsFullInfo = $appsInfo->readCSVinfo($request);


        $arrayNames = array_values($appsFullInfo[0]);
        $arrayTimes = array_values($appsFullInfo[1]);
        $arrayStatus = array_values($appsFullInfo[2]);
        //dia del año Y/m/d
        //hora del dia H:i:s

        $arrayApps = [];
        $arrayTimeIntervaleOfADay = [];
        $arrayDates = [];
        $arrayContainerOfAllInfo = [];





        $totalDigitalTimeUsedInADay = 0;
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
                    $dateDayMinusOne = date("Y/m/d", $dayMinusOne);

                    //Seconds used before midnight
                    $timeBeforeMidnight = strtotime(86400) - strtotime($dayHourOpened);
                    $digitaltimeBeforeMidnight = date("H:i:s", $timeBeforeMidnight);
                    var_dump("Tiempo de uso de $arrayNames[$i] el dia $dateDayMinusOne es $digitaltimeBeforeMidnight");
                    array_push($arrayApps, $arrayNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, $digitaltimeBeforeMidnight);
                    array_push($arrayDates, $dateDayMinusOne);

                    //new usage cut before midnight
                    /*$usageCutBeforeMidnight = new Usage;
                    $usageCutBeforeMidnight->user_id = $userSummon->id;
                    $usageCutBeforeMidnight->application_id = $selectedAppBeforeMidnight->id;
                    $usageCutBeforeMidnight->date = $dayMinusOne;
                    $usageCutBeforeMidnight->time = $digitaltimeBeforeMidnight;

                    $usageCutBeforeMidnight->save();
                    */
                    //Seconds used after midnight

                    $timeAfterMidnight = strtotime($dayHourClosed);
                    $digitaltimeAfterMidnight = date("H:i:s", $timeAfterMidnight);
                    var_dump("Tiempo de uso de $arrayNames[$i] el dia $dayOfYearClosed es $digitaltimeAfterMidnight");
                    array_push($arrayApps, $arrayNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, $digitaltimeAfterMidnight);
                    array_push($arrayDates, $dayOfYearClosed);

                    //new usage cut after midnight
                    /*$usageCutAfterMidnight = new Usage;
                    $usageCutAfterMidnight->user_id = $userSummon->id;
                    $usageCutAfterMidnight->application_id = $selectedAppBeforeMidnight->id;
                    $usageCutAfterMidnight->date = $dayOfYearClosed;
                    $usageCutAfterMidnight->time = $digitaltimeAfterMidnight;

                    $usageCutAfterMidnight->save();
                    */
                } else {
                    $timeUsedPerDay = strtotime($dayHourClosed) - strtotime($dayHourOpened);
                    $digitalTimeUsedPerDay = date("H:i:s", $timeUsedPerDay);
                    var_dump("Tiempo de uso de $arrayNames[$i] el dia $dayOfYearClosed es $digitalTimeUsedPerDay");
                    array_push($arrayApps, $arrayNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, $digitalTimeUsedPerDay);
                    array_push($arrayDates, $dayOfYearClosed);


                    //new normal usage
                    /*$normalUsage = new Usage;
                    $normalUsage->user_id = $userSummon->id;
                    $normalUsage->application_id = $selectedAppBeforeMidnight->id;
                    $normalUsage->date = $dayOfYearClosed;
                    $normalUsage->time = $digitalTotalTimeUsedPerDay;
                    $normalUsage->save();
                    */
                }
            }
        }

        array_push($arrayContainerOfAllInfo, $arrayApps);
        array_push($arrayContainerOfAllInfo, $arrayTimeIntervaleOfADay);
        array_push($arrayContainerOfAllInfo, $arrayDates);
        var_dump($arrayContainerOfAllInfo);


        //crea los usages para un unico usuario
        /*
        for ($i = 0; $i < count($arrayUniqueNames); $i++) {
            $gettingUser = new UserController;
            $userSummon = $gettingUser->getUserFromToken($request);
            $uniqueAppUsage = new Usage;
            $uniqueAppUsage->user_id = $userSummon->id;
            $uniqueAppUsage->application_id = ;
            $uniqueAppUsage->date = ;
            $uniqueAppUsage->time = ;
        }
        */



        return response()->json([
            'message' => "App time used uploaded successfully"
        ], 200);
    }
}
