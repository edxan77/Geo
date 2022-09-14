<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Radius;

class CityController extends Controller
{
    public function getCitiesOnRadius(Request $req)
    {
        
        return Radius::cityOnRadius($req);

    }
}
