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

class LoadAndMoveService
{

    private array $errors = [];

    public function downloadLogic ():void
    {
       
        $FileSystem = new Filesystem();
        if(empty($FileSystem->files(config('DirPath.unzip_short'))))
        {
           $contents = Http::get(config('DirPath.data_link'))->body();
            $req =  Http::get(config('DirPath.data_link'));

            if($req->successful())
            {
                Storage::disk("local")->put(config('DirPath.zip_short'), $contents);
    
           }else{
                array_push($this->errors, "File Download Error");
            }
        
        }
     }

    public function unZip():void
    {
            
        $zip = new ZipArchive();
            
        $toUnZip = $zip->open(storage_path(config('DirPath.zip_full')));
        $zip->extractTo(storage_path(config('DirPath.unzip')));

        $filename=storage_path(config('DirPath.data_file'));
        $content = File::get($filename);
    }

    public function import():void
    {
        try {
            $pdo =  DB::connection()->getPdo();
         } catch (\Exception $e) {
             array_push($this->errors, "Connection Error");
         }
         
        
         if (!collect(DB::select("SHOW INDEXES FROM geo_tables"))->pluck('Key_name')->contains('long')) {
             
             $pdo->exec('ALTER TABLE `geo_tables` ADD  INDEX `long` (`longitude`)');
             $pdo->exec('ALTER TABLE `geo_tables` ADD  INDEX `lat` (`latitude`)');
             $pdo->exec('ALTER TABLE `geo_tables` ADD  INDEX `forname` (`name`)');
             $pdo->exec('ALTER TABLE `geo_tables` ADD  INDEX `fcode` (`featureCode`)');
             
            }
     
        $pdo->exec('TRUNCATE table `geo_tables`');
        $pdo->exec("LOAD DATA LOCAL INFILE '".storage_path('../storage/app/unzip/AM.txt')."' INTO TABLE geo_tables FIELDS TERMINATED BY '\t' ENCLOSED BY '\"' LINES TERMINATED BY '\\n'");
    }

    public function getErrors():array
    {
        return $this->errors;
    }
}