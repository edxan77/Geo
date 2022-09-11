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

        if (collect(DB::select("SHOW INDEXES FROM geo_tables"))->pluck('Key_name')->contains('location')) {

            $pdo->exec("DROP INDEX location ON geo_tables");

        }

        $pdo->exec('TRUNCATE table `geo_tables`');
        $pdo->exec("ALTER TABLE `geo_tables` MODIFY `coords` POINT  NULL");
        $pdo->exec("LOAD DATA LOCAL INFILE '" . storage_path(config('DirPath.data_file')) . "' INTO TABLE geo_tables FIELDS TERMINATED BY '\t' ENCLOSED BY '\"' LINES TERMINATED BY '\\n'");
        $pdo->exec("update geo_tables set coords=Point(geo_tables.longitude, geo_tables.latitude)");
        $pdo->exec("ALTER TABLE `geo_tables` MODIFY `coords` POINT NOT NULL");
        $pdo->exec("create spatial index location ON geo_tables(coords)");

    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
