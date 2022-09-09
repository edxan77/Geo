<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Load;

class loadAndMove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Downloading file and moving It into DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->progressStart(100);
        $this->output->progressAdvance(15);
        Load::downloadLogic();
        $this->output->progressAdvance(15);
        Load::unZip();
        $this->output->progressAdvance(15);
        Load::import();
        $this->output->progressAdvance(45);
    
        if(Load::getErrors()){
            $errors = Load::getErrors();
            return $this->error("\n".$errors[0]);   
        }

        $this->output->progressAdvance(10);
        $this->output->progressFinish();
        
        return $this->info("Data Successfully Imported To DB");

    }
}
