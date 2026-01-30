<?php

namespace Codepreneur\CasysPay\Http\Controllers;

use Codepreneur\CasysPay\Checksum\ChecksumVerifier;
use Codepreneur\CasysPay\Events\CasysPaymentSucceeded;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SuccessController
{
    public function __construct(private readonly ChecksumVerifier $verifier) {}

    public function __invoke(Request $request): Response
    {
        abort_unless($this->verifier->valid($request), 404);

        CasysPaymentSucceeded::dispatch($request);

        $view = config('casys.responses.success_view');
        if (is_string($view)) {
            return response()->view($view, ['request' => $request]);
        }

        return response()->noContent();
    }
}
