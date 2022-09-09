<?php
namespace App\Facade;
use Illuminate\Support\Facades\Facade;
class LoadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "Load";
    }
}