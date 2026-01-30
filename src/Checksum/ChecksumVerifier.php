<?php

namespace Codepreneur\CasysPay\Checksum;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChecksumVerifier
{
    public function valid(Request $request): bool
    {
        $details = (string)$request->input('Details1');
        $details2 = (string)$request->input('Details2');
        $successUrl = url(config('casys.routes.success'));
        $failUrl = url(config('casys.routes.fail'));

        $checksumHeader = (string)$request->input('ReturnCheckSumHeader');
        $checksumData = $checksumHeader
            . (string)config('casys.merchant_id')
            . (string)$request->input('AmountToPay')
            . (string)config('casys.merchant_name')
            . (string)config('casys.currency')
            . $details
            . $details2
            . $successUrl
            . $failUrl
            . (string)$request->input('FirstName')
            . (string)$request->input('LastName')
            . (string)$request->input('Address')
            . (string)$request->input('City')
            . (string)$request->input('Zip')
            . (string)$request->input('Country')
            . (string)$request->input('Telephone')
            . (string)$request->input('Email');

        $paymentRef = $request->input('cPayPaymentRef');
        if (is_scalar($paymentRef) && $paymentRef !== '') {
            $checksumData .= (string)$paymentRef;
        }

        $checksum = hash_hmac('sha256', $checksumData, (string)config('casys.password'));

        return Str::upper($checksum) === Str::upper((string)$request->input('ReturnCheckSum'));
    }
}
