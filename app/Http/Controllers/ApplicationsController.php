<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Application;
use App\Http\Middleware\CheckToken;
use App\Helpers\Token;
use App\User;

class ApplicationsController extends Controller
{
    function readCSVinfo(Request $request)
    {
        $CSVfile = $request->file;
        $longitudDeLinea = 100;
        $delimitador = ",";
        $gestor = fopen($CSVfile, "r");
        if (!$gestor) 
        {
            exit("No se puede abrir el archivo $CSVfile");
        }
        fgetcsv($gestor);
        while ($fila = fgetcsv($gestor, $longitudDeLinea, $delimitador)) 
        {
            foreach ($fila as $key => $value) 
            {
                $name[] = $fila[1];
            }
        }
        $appsList = array_values(array_unique($name));
        fclose($gestor);

        return $appsList;
    }

    public function showApps(Request $request)
    {
        $appsList = $this->readCSVinfo($request);
        for ($i = 0; $i < count($appsList); $i++) 
        {
            $application = new Application();
            $application->name = "hola";
            $application->icon = "icono1";
            $application->save();
        }


        return response()->json([
            'lista apps' => $appsList
        ], 200);
    }
}
