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
        $arrayOrderedNames = sort($arrayNames);
        $arrayTimes = array_values($appsFullInfo[1]);
        $arrayStatus = array_values($appsFullInfo[2]);
        //dia del año Y/m/d
        //hora del dia H:i:s

        $arrayApps = [];
        $arrayTimeIntervaleOfADay = [];
        $arrayDates = [];
        $arrayContainerOfAllInfo = [];


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
                    array_push($arrayApps, $arrayOrderedNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, strtotime($digitaltimeBeforeMidnight));
                    array_push($arrayDates, strtotime($dateDayMinusOne));

                    //Seconds used after midnight

                    $timeAfterMidnight = strtotime($dayHourClosed);
                    $digitaltimeAfterMidnight = date("H:i:s", $timeAfterMidnight);
                    array_push($arrayApps, $arrayOrderedNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, strtotime($digitaltimeAfterMidnight));
                    array_push($arrayDates, strtotime($dayOfYearClosed));

                    //caso normal
                } else {
                    $timeUsedPerDay = strtotime($dayHourClosed) - strtotime($dayHourOpened);
                    $digitalTimeUsedPerDay = date("H:i:s", $timeUsedPerDay);
                    array_push($arrayApps, $arrayOrderedNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, strtotime($digitalTimeUsedPerDay));
                    array_push($arrayDates, strtotime($dayOfYearClosed));
                }
            }
        }

        array_push($arrayContainerOfAllInfo, $arrayApps);
        array_push($arrayContainerOfAllInfo, $arrayTimeIntervaleOfADay);
        array_push($arrayContainerOfAllInfo, $arrayDates);

        //////////////////

        $arrayFinalApps = [];
        $arrayFinalTimeIntervaleOfADay = [];
        $arrayFinalDates = [];
        $arrayFinalContainerOfAllInfo = [];

        $pastName = $arrayApps[0];
        $pastDate = $arrayDates[0];
        $totalTime = $arrayTimeIntervaleOfADay[0];

        for ($i = 1; $i < count($arrayApps) - 1; $i++) {
            if ($arrayOrderedNames[$i] == $pastName && $arrayDates[$i] == $pastDate) {
                $totalTime += $arrayTimeIntervaleOfADay[$i];
                $arrayFinalTimeIntervaleOfADay[$i] = $totalTime;
            } else {
                $totalTime = $arrayTimeIntervaleOfADay[$i];
                array_push($arrayFinalApps, $pastName);
                array_push($arrayFinalTimeIntervaleOfADay, $totalTime);
                array_push($arrayFinalDates, $pastDate);
            }
            $pastName = $arrayOrderedNames[$i];
            $pastDate = $arrayDates[$i];
        }
        var_dump("holaaaaaaaaaaaaaa");
        var_dump(date("H:i:s", 3163276815));

        array_push($arrayFinalContainerOfAllInfo, $arrayFinalApps);
        array_push($arrayFinalContainerOfAllInfo, $arrayFinalTimeIntervaleOfADay);
        array_push($arrayFinalContainerOfAllInfo, $arrayFinalDates);
        var_dump($arrayFinalContainerOfAllInfo);



        return response()->json([
            'message' => "App time used uploaded successfully"
        ], 200);
    }

    public function getUseTimes(Request $request)
    {
    }
}
