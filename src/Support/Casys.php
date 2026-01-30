<?php

namespace Codepreneur\CasysPay\Support;

use Codepreneur\CasysPay\Contracts\CasysClientInterface;
use Illuminate\Support\Facades\Facade;

class Casys extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CasysClientInterface::class;
    }
}
