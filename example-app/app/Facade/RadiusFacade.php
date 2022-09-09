<?php
namespace App\Facade;
use Illuminate\Support\Facades\Facade;

class RadiusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "Radius";
    }
}