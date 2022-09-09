<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class InRadius extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'In:Radius{cityName}{Km?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->progressStart(100);
        $this->output->progressAdvance(15);
        $this->output->progressAdvance(17);
        $table = new Table($this->output);
        $separator = new TableSeparator;
        $this->output->progressAdvance(17);
        $this->output->progressAdvance(20);
        $cityName = $this->argument('cityName');
        $Km = $this->argument('Km');
        $contents = Http::get("http://localhost/test?name=$cityName&dist=$Km")->body();
        $req = Http::get("http://localhost/test?name=$cityName&dist=$Km");
        $decode = json_decode($contents);
        $table->setHeaders([
            'CityName', 'Distance'
        ]);
        if ($req->successful()) {
            
                for($i=0;$i<count($decode->original);$i++)
                {
                    $table->addRows([
                        $separator,
                        [$decode->original[$i]->name,$decode->original[$i]->distance]
                    ]);
                     }
            
        } else {
            return $this->error("\n" . $decode);
        }

        $this->output->progressAdvance(20);
        $this->output->progressAdvance(10);
        $this->output->progressFinish();
        $table->render();
        $this->info("Table Successfully Created");
    }
}
