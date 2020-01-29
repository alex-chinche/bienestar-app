<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Application;
use App\Http\Middleware\CheckToken;
use App\Helpers\Token;
use App\User;
use Illuminate\Support\Facades\Storage;

class ApplicationsController extends Controller
{
    function readCSVinfo(Request $request)
    {
        $CSVfile = $request->file;
        $longitudDeLinea = 100;
        $delimitador = ",";
        $gestor = fopen($CSVfile, "r");
        if (!$gestor) {
            exit("No se puede abrir el archivo $CSVfile");
        }
        fgetcsv($gestor);
        while ($fila = fgetcsv($gestor, $longitudDeLinea, $delimitador)) {
            $name[] = $fila[1];
            //$timeToString = date("Y-m-d H:i:s", $fila[0]);
            //$date[] = $timeToString;
            //$date[] = strtotime($fila[0]);
        }
        $appsNamesList = array_values(array_unique($name));
        fclose($gestor);

        return $appsNamesList;
    }

    public function showApps(Request $request)
    {
        $appsNamesList = $this->readCSVinfo($request);
        /*
        var_dump(count($appsNamesList));
        exit;
        */
        for ($i = 0; $i < count($appsNamesList); $i++) {
            $application = new Application();
            $application->name = $appsNamesList[$i];
            $appName = [$application->name];
            $appIcon = [$application->icon];
            switch ($application->name) {
                case "Whatsapp":
                    $application->icon = "https://lh3.googleusercontent.com/bYtqbOcTYOlgc6gqZ2rwb8lptHuwlNE75zYJu6Bn076-hTmvd96HH-6v7S0YUAAJXoJN";
                    break;
                case "Instagram":
                    $application->icon = "https://lh3.googleusercontent.com/2sREY-8UpjmaLDCTztldQf6u2RGUtuyf6VT5iyX3z53JS4TdvfQlX-rNChXKgpBYMw";
                    break;
                case "Reloj":
                    $application->icon = "https://lh3.googleusercontent.com/k-K6mdmZJZrJiuMJCHILReDGjMl_2ljzFIz3QLULfKL1q0tWtTcAkc0RDsjg9QEuXYw";
                    break;
                case "Gmail":
                    $application->icon = "https://lh3.googleusercontent.com/qTG9HMCp-s_aubJGeQWkR6M_myn-aXDJnraWn9oePcY1dGbYqXibaeLQBAeMdmxSBus";
                    break;
                case "Chrome":
                    $application->icon = "https://lh3.googleusercontent.com/KwUBNPbMTk9jDXYS2AeX3illtVRTkrKVh5xR1Mg4WHd0CG2tV4mrh1z3kXi5z_warlk";
                    break;
                case "Facebook":
                    $application->icon = "https://lh3.googleusercontent.com/ccWDU4A7fX1R24v-vvT480ySh26AYp97g1VrIB_FIdjRcuQB2JP2WdY7h_wVVAeSpg";
                    break;
                default:
                    $application->icon = "https://cdn3.vectorstock.com/i/1000x1000/50/07/http-404-not-found-error-message-hypertext-vector-20025007.jpg";
                    break;
            }

            $application->save();

            array_push($appName, $application->name);
            array_push($appIcon, $application->icon);


            //$appsList = [[$appName, $appIcon]];
            
            //$imageFound = Storage::url("$appsNamesList[$i].png");
            //$singleImage = asset($imageFound);
            //$application->icon = $singleImage;
            //array_push($appsIconsList, $singleImage);
            
        }

        return response()->json([
            'lista apps' => $appName
        ], 200);
    }
}
