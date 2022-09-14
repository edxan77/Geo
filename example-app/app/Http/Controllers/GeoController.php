<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Radius;

class GeoController extends Controller
{
    public function getRadius(Request $req)
    {

        return Radius::cityOnRadius($req);

    }
}
