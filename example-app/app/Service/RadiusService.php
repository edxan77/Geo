<?php
namespace App\Service;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RadiusService
{
     

    public function cityOnRadius(Request $req, int $km = 10)
    {

        if ($req->name != "") {
            $city = City::where('name', $req->name)->first();

            if ($city != null) {
                $long = $city->longitude;
                $lat = $city->latitude;
                $name = $city->name;
                $fCode = $city->featurecode;
                $distance = $km;
            } else if ($city == null) {
               
                return response("No City With This Name On this DB")->setStatusCode(404);

            }

        } else {
            
            return response("Empty Parameter ?name")->setStatusCode(404);

        }

        if ($req->dist != "") {
            $distance = $req->dist;
        }

        try {
            $cities = DB::table('cities')
                ->selectRaw('( 6371 * acos( cos( radians(?) ) *
                               cos( radians( latitude ) )
                               * cos( radians( longitude ) - radians(?)
                               ) + sin( radians(?) ) *
                               sin( radians( latitude ) ) )
                             ) AS distance', [$lat, $long, $lat])

                ->havingRaw("distance < ?", [$distance])
                ->where('code', 'LIKE', '%' . "ADM1H" . '%')
                ->selectRaw('name ')
                ->orderBy('distance', 'desc')
                ->limit(10)
                ->get();

        } catch (\Exception) {
            
            return response("Connection Error")->setStatusCode(404);
        }

        
            return response()->json($cities);
        

    }

    
}
