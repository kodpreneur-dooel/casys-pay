<?php

namespace Codepreneur\CasysPay\Contracts;

use Codepreneur\CasysPay\Payload\PaymentPayload;

interface PayloadBuilderInterface
{
    public function build(array $payload): PaymentPayload;
}
