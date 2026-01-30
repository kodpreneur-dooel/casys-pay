<?php

namespace Codepreneur\CasysPay\Support;

use Illuminate\Support\Facades\Facade;

class Casys extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Codepreneur\CasysPay\Contracts\CasysClientInterface::class;
    }
}
