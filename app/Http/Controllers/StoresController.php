<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationsController;
use App\User;
use App\Application;
use App\Usage;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function postLocations(Request $request)
    {
        $appsInfo = new ApplicationsController;
        $appsFullInfo = $appsInfo->readCSVinfo($request);
        $arrayNames = array_values(array_reverse($appsFullInfo[0]));
        $arrayTimes = array_values(array_reverse($appsFullInfo[1]));
        $arrayStatus = array_values(array_reverse($appsFullInfo[2]));
        $arrayLatitude = array_values(array_reverse($appsFullInfo[3]));
        $arrayLongitude = array_values(array_reverse($appsFullInfo[4]));

        $arrayTimeIntervaleOfADay = [];
        $arrayDates = [];
        $arrayOpenLatitudes = [];
        $arrayOpenLongitudes = [];
        $arrayCloseLatitudes = [];
        $arrayCloseLongitudes = [];
        $arrayContainerOfAllInfo = [];


        $arrayFinalNames = array_values(array_unique($arrayNames));
        $actualAppOnList = $arrayNames[0];

        for ($i = 0; $i < count($arrayFinalNames); $i++) {
            $actualFinalApp = $arrayFinalNames[$i];

            for ($j = 0; $j < count($arrayNames); $j++) {
                if ($actualFinalApp == $actualAppOnList && $arrayStatus[$j] == "closes") {
                    array_push($arrayCloseLatitudes, $arrayLatitude[$j]);
                    array_push($arrayCloseLongitudes, $arrayLongitude[$j]);
                    $actualAppOnList = $arrayNames[$j];
                    var_dump($actualAppOnList);
                } else if ($actualFinalApp == $actualAppOnList && $arrayStatus[$j] == "opens") {
                    array_push($arrayOpenLatitudes, $arrayLatitude[$j]);
                    array_push($arrayOpenLongitudes, $arrayLongitude[$j]);
                    $actualAppOnList = $arrayNames[$j];
                    var_dump($actualAppOnList);
                } else {
                    var_dump($actualAppOnList);
                }
            }
        }




        $user = new User;
        $userController = new UserController;
        $user = $userController->getUserFromToken($request);
    }
}
