<?php

namespace Codepreneur\CasysPay\Http\Controllers;

use Codepreneur\CasysPay\Checksum\ChecksumVerifier;
use Codepreneur\CasysPay\Events\CasysPaymentFailed;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FailController
{
    public function __construct(private readonly ChecksumVerifier $verifier) {}

    public function __invoke(Request $request): Response
    {
        abort_unless($this->verifier->valid($request), 404);

        CasysPaymentFailed::dispatch($request);

        $view = config('casys.responses.fail_view');
        if (is_string($view)) {
            return response()->view($view, ['request' => $request]);
        }

        return response()->noContent();
    }
}
