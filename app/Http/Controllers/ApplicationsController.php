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
            $time[] = $fila[0];
            $status[] = $fila[2];
            $latitude[] = $fila[3];
            $longitude[] = $fila[4];
        }
        $arrayAppNamesList = array_values($name);
        $appsTimesList = array_values($time);
        $appsStatusList = array_values($status);
        $appsLatitudeList = array_values($latitude);
        $appsLongitudeList = array_values($longitude);
        fclose($gestor);

        $appsFullInfo = [];

        array_push($appsFullInfo, $arrayAppNamesList);
        array_push($appsFullInfo, $appsTimesList);
        array_push($appsFullInfo, $appsStatusList);
        array_push($appsFullInfo, $appsLatitudeList);
        array_push($appsFullInfo, $appsLongitudeList);
        

        return $appsFullInfo;
    }

    public function showApps(Request $request)
    {
        $appsFullInfo = $this->readCSVinfo($request);
        $arrayAppNames = array_values(array_unique($appsFullInfo[0]));
        for ($i = 0; $i < count($arrayAppNames); $i++) {
            if (!Application::where("name", $arrayAppNames[$i])->first()) {
                $application = new Application();
                $application->name = $arrayAppNames[$i];
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
            } else if ($applicationAlreadyRegistered = Application::where("name", $arrayAppNames[$i])->first()) {
                var_dump("App $applicationAlreadyRegistered->name  already exists.");
            }
        }
        return response()->json(
            [
                'message' => "Apps updated"
            ],
            200
        );
    }
}
