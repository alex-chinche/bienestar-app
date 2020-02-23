<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationsController;
use App\User;
use App\Application;
use App\Usage;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\all;

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
                    array_push($arrayApps, $arrayNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, strtotime($digitaltimeBeforeMidnight));
                    array_push($arrayDates, strtotime($dateDayMinusOne));

                    //Seconds used after midnight

                    $timeAfterMidnight = strtotime($dayHourClosed);
                    $digitaltimeAfterMidnight = date("H:i:s", $timeAfterMidnight);
                    array_push($arrayApps, $arrayNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, strtotime($digitaltimeAfterMidnight));
                    array_push($arrayDates, strtotime($dayOfYearClosed));

                    //caso normal
                } else {
                    $timeUsedPerDay = strtotime($dayHourClosed) - strtotime($dayHourOpened);
                    $digitalTimeUsedPerDay = date("H:i:s", $timeUsedPerDay);
                    array_push($arrayApps, $arrayNames[$i]);
                    array_push($arrayTimeIntervaleOfADay, strtotime($digitalTimeUsedPerDay));
                    array_push($arrayDates, strtotime($dayOfYearClosed));
                }
            }
        }
        array_push($arrayContainerOfAllInfo, $arrayApps);
        array_push($arrayContainerOfAllInfo, $arrayTimeIntervaleOfADay);
        array_push($arrayContainerOfAllInfo, $arrayDates);

        $user = new User;
        $userController = new UserController;
        $user = $userController->getUserFromToken($request);

        Usage::where("user_id", $user->id)->delete();

        $app = new Application;

        for ($i = 0; $i < count($arrayApps); $i++) {
            $app = new Application;
            $app = Application::where("name", $arrayApps[$i])->first();
            $appUsage = new Usage;
            $appUsage->user_id = $user->id;
            $appUsage->application_id = $app->id;
            $digitalTime = date("H:i:s", $arrayTimeIntervaleOfADay[$i]);
            $digitalDate = date("Y/m/d", $arrayDates[$i]);
            $appUsage->time = $digitalTime;
            $appUsage->date = $digitalDate;

            $appUsage->save();
        }
        return response()->json([
            $arrayContainerOfAllInfo
        ], 200);
    }
    public function getUseTimes(Request $request)
    {

        try {
            $user = new User;
            $userController = new UserController;
            $user = $userController->getUserFromToken($request);
            $usageGot = Usage::where("user_id", $user->id)->get();

            return response($usageGot, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Not possible to get usages"
            ], 401);
        }
    }
    public function getTotalUsagesPerApp(Request $request)
    {
        $user = new User;
        $userController = new UserController;
        $user = $userController->getUserFromToken($request);
        $usageGot = Usage::where("user_id", $user->id)->get();
        $usageGotArray = $usageGot->toArray();
        $applicationIdsArray = array_unique(array_column($usageGotArray, "application_id"));

        foreach ($applicationIdsArray as $appId) {
            $usageByAppId[$appId] = array_column(array_filter($usageGotArray, function ($var) use ($appId) {
                return ($var["application_id"] == $appId);
            }), "time");
        }
        
        $putotime = strtotime($usageByAppId[1][0], 'H:i:s');
        var_dump($putotime);
        // dd(date("H:i:s", strtotime($usageByAppId[1][0])));


        $usosorimeraapp = $usageGot;
        print($usosorimeraapp);
        //print($usosorimeraapp);
    }
}
