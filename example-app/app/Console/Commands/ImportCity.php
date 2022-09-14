<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Load;

class ImportCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cities:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Downloading file and moving it into DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Load::downloadLogic();

        // Load::unZip();

        $this->comment("\n" . "Trying import file to DB");
        $progressBar = $this->output->createProgressBar(10);
        $progressBar->setFormat('[%bar%]');
        $progressBar->setEmptyBarCharacter('>');
        $progressBar->start(100);

        for ($i = 0; $i < 7; $i++) {
            sleep(1);

            $progressBar->advance(10);
        }

        Load::import();

        if (Load::getErrors()) {
            $errors = Load::getErrors();
            return $this->error("\n" . $errors[0]);
        }

        $progressBar->advance('30');
        $progressBar->finish();

        return $this->info("\n" . "Data Successfully Imported To DB");

    }
}
