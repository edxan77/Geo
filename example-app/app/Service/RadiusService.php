<?php
namespace App\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use File;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Filesystem\Filesystem;
use  App\Models\geoTable;
class RadiusService
{
    private array $error = [];

    public function CityOnRadius(Request $req,int $km=10)
    {
       
        if($req->name !="")
        {
            $serchModel = geoTable::where('name',$req->name)->first();
            
            if($serchModel!=null)
            {
                $long = $serchModel->longitude;
                $lat = $serchModel->latitude;
                $name = $serchModel->name;
                $fCode = $serchModel->featurecode;
                $distance = $km;
            }else if($serchModel==null){
                array_push($this->error, "No City With This Name On this DB");
            
            }

         }else{
            array_push($this->error, "Empty Querry Parameter");
            
            
        }

    
        if($req->dist != "")
        {
            $distance = $req->dist;
        }
       
       
        try {
            $pdo =  DB::connection()->getPdo();
         } catch (\Exception $e) {
             array_push($this->error, "Connection Error");
             
         }
         
        $query =  "SELECT DISTINCT name, ( 3959 * acos( cos( radians($lat) ) * cos( radians( geo_tables.latitude ) ) 
         * cos( radians( geo_tables.longitude ) - radians($long) ) + sin( radians($lat) ) * sin(radians(geo_tables.latitude)) ) ) AS distance 
         FROM geo_tables
         WHERE  geo_tables.featurecode LIKE :fcode
         HAVING distance<:distance AND geo_tables.name<>:name 
         ORDER BY distance asc
         LIMIT 10";
         

         $getSelect = $pdo->prepare($query);
         $getSelect->execute([':distance'=>$distance,':name'=>$name,':fcode'=>"%ADM1H%"]);
         $result = $getSelect->fetchAll();
       
   if($this->error == null){
    return response()->json($result);
   }
        
    }

    public function getError():array
    {
        return $this->error;
    }
}