<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Application;

class ApplicationsController extends Controller
{
    
    public function showApps()
    {
        $appGot = Application::all();
        return response($appGot, 200);
    }
}
