<?php

namespace Codepreneur\CasysPay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class CasysPaymentSucceeded
{
    use Dispatchable;

    public function __construct(public readonly Request $request)
    {
    }
}
