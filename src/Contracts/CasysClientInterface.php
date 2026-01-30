<?php

namespace Codepreneur\CasysPay\Contracts;

use Codepreneur\CasysPay\Payload\PaymentPayload;

interface CasysClientInterface
{
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
    public function buildPayload(array $payload): PaymentPayload;
}
