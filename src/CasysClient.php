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

    /**
     * @param array{
     *     amount:int,
     *     details1?:string,
     *     details2?:string,
     *     success_url?:string,
     *     fail_url?:string,
     *     customer?:array{
     *         first_name?:string,
     *         last_name?:string,
     *         address?:string,
     *         city?:string,
     *         zip?:string,
     *         country?:string,
     *         phone?:string,
     *         email?:string
     *     },
     *     is_simple?:string|bool
     * } $payload
     */
    public function buildPayload(array $payload): PaymentPayload
    {
        return $this->payloadBuilder->build($payload);
    }
}
