<?php

namespace Codepreneur\CasysPay\Checksum;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChecksumVerifier
{
    public function valid(Request $request): bool
    {
        $details = (string) $request->input('Details1');
        $details2 = (string) $request->input('Details2');
        $successUrl = url(config('casys.routes.success'));
        $failUrl = url(config('casys.routes.fail'));

        $checksumHeader = (string) $request->input('ReturnCheckSumHeader');
        $checksumData = $checksumHeader
            .config('casys.merchant_id')
            .$request->input('AmountToPay')
            .config('casys.merchant_name')
            .config('casys.currency')
            .$details
            .$details2
            .$successUrl
            .$failUrl
            .$request->input('FirstName')
            .$request->input('LastName')
            .$request->input('Address')
            .$request->input('City')
            .$request->input('Zip')
            .$request->input('Country')
            .$request->input('Telephone')
            .$request->input('Email');

        $paymentRef = $request->input('cPayPaymentRef');
        if (is_scalar($paymentRef) && $paymentRef !== '') {
            $checksumData .= $paymentRef;
        }

        $checksum = hash_hmac('sha256', $checksumData, (string) config('casys.password'));

        return Str::upper($checksum) === Str::upper((string) $request->input('ReturnCheckSum'));
    }
}
