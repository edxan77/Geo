<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Radius;

class geoController extends Controller
{
    public function getRadius(Request $req)
    {

        Radius::CityOnRadius($req);
        if (Radius::getError() != null) {
            $errorCheck = Radius::getError();
            return response()->json($errorCheck[0])->setStatusCode(404);
        }
        return response()->json(Radius::CityOnRadius($req));
    }
}
