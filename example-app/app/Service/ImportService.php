<?php
namespace App\Service;

use App\Models\City;
use Illuminate\Support\Facades\DB;

class ImportService
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

    private function getCSV()
    {
        $importData = [];
        try {
            if (($open = fopen(storage_path(config('dir_path.data_file')), "r")) !== false) {

                while (($data = fgetcsv($open, 1000, "\t")) !== false) {
                    if (count($data) == 19) {
                        array_push($importData, 
                        [
                        'name' => $data[1], 
                        'longitude' => $data[4],
                        'latitude' => $data[5],
                        'code' => $data[7]
                        ]);

                    }

                }

                fclose($open);

                return $importData;
            }
        } catch (\Exception $e) {
            array_push($this->errors, "Parse Error");
        }

    }

    public function import(): void
    {

        $cities = $this->getCSV();
        $chunks = array_chunk($cities, 5000);
        // try {
        DB::beginTransaction();
        try {

            DB::table('cities')->delete();
        } catch (\Exception $e) {
            DB::rollback();
            array_push($this->errors, "Transaction Error");
        }

        try {

            foreach ($chunks as $chunk) {
                City::insert($chunk);
            }
        } catch (\Exception $e) {
            DB::rollback();
            array_push($this->errors, "Transaction Insert Error");
        }

        DB::commit();

    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
