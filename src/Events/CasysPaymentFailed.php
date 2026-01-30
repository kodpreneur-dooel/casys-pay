<?php

namespace Codepreneur\CasysPay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class CasysPaymentFailed
{
    use Dispatchable;

    public function __construct(public readonly Request $request)
    {
    }
}
