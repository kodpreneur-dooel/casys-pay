<?php

namespace Codepreneur\CasysPay;

use Codepreneur\CasysPay\Contracts\CasysClientInterface;
use Codepreneur\CasysPay\Contracts\PayloadBuilderInterface;
use Codepreneur\CasysPay\Payload\PaymentPayload;

class CasysClient implements CasysClientInterface
{
    public function __construct(private readonly PayloadBuilderInterface $payloadBuilder)
    {
    }

    public function buildPayload(array $payload): PaymentPayload
    {
        return $this->payloadBuilder->build($payload);
    }
}
