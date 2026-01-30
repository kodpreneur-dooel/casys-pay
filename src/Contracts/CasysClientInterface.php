<?php

namespace Codepreneur\CasysPay\Contracts;

use Codepreneur\CasysPay\Payload\PaymentPayload;

interface CasysClientInterface
{
    public function buildPayload(array $payload): PaymentPayload;
}
