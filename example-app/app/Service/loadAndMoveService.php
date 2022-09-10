<?php
namespace App\Service;

use Illuminate\Support\Facades\DB;

class LoadAndMoveService
{

    private array $errors = [];

    // public function downloadLogic ():void
    // {

    //     $FileSystem = new Filesystem();
    //     if(empty($FileSystem->files(config('DirPath.unzip_short'))))
    //     {
    //        $contents = Http::get(config('DirPath.data_link'))->body();
    //         $req =  Http::get(config('DirPath.data_link'));

    //         if($req->successful())
    //         {
    //             Storage::disk("local")->put(config('DirPath.zip_short'), $contents);

    //        }else{
    //             array_push($this->errors, "File Download Error");
    //         }

    //     }
    //  }

    // public function unZip():void
    // {

    //     $zip = new ZipArchive();

    //     $toUnZip = $zip->open(storage_path(config('DirPath.zip_full')));
    //     $zip->extractTo(storage_path(config('DirPath.unzip')));

    //     $filename=storage_path(config('DirPath.data_file'));
    //     $content = File::get($filename);
    // }

    public function import(): void
    {
        try {
            $pdo = DB::connection()->getPdo();
        } catch (\Exception$e) {
            array_push($this->errors, "Connection Error");
        }

        $pdo->exec('TRUNCATE table `geo_tables`');
        $pdo->exec("LOAD DATA LOCAL INFILE '" . storage_path(config('DirPath.data_file')) . "' INTO TABLE geo_tables FIELDS TERMINATED BY '\t' ENCLOSED BY '\"' LINES TERMINATED BY '\\n'");
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
