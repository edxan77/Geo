<?php
namespace App\Service;

use App\Models\geoTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RadiusService
{
     private array $error = [];

    public function CityOnRadius(Request $req, int $km = 10)
    {

        if ($req->name != "") {
            $serchModel = geoTable::where('name', $req->name)->first();

            if ($serchModel != null) {
                $long = $serchModel->longitude;
                $lat = $serchModel->latitude;
                $name = $serchModel->name;
                $fCode = $serchModel->featurecode;
                $distance = $km;
            } else if ($serchModel == null) {
                array_push($this->error, "No City With This Name On this DB");

            }

        } else {
            array_push($this->error, "Empty Querry Parameter");

        }

        if ($req->dist != "") {
            $distance = $req->dist;
        }

        try {
            $cities = DB::table('geo_tables')
                ->selectRaw('( 6371 * acos( cos( radians(?) ) *
                               cos( radians( latitude ) )
                               * cos( radians( longitude ) - radians(?)
                               ) + sin( radians(?) ) *
                               sin( radians( latitude ) ) )
                             ) AS distance', [$lat, $long, $lat])

                ->havingRaw("distance < ?", [$distance])
                ->where('featurecode', 'LIKE', '%' . "ADM1H" . '%')
                ->selectRaw('name ')
                ->orderBy('distance', 'desc')
                ->limit(10)
                ->get();

        } catch (\Exception) {
            array_push($this->error, "Connection Error");
        }

        if ($this->error == null) {
            return response()->json($cities);
        }

    }

    public function getError(): array
    {
        return $this->error;
    }
}
