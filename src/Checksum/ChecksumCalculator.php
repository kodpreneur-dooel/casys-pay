<?php

namespace Codepreneur\CasysPay\Checksum;

use Illuminate\Support\Str;

class ChecksumCalculator
{
    /**
     * @param array<string, string|int> $payload
     * @return array{checksum: string, header: string}
     */
    public function make(array $payload): array
    {
        $amountCents = ((int)$payload['amount']) * 100;

        $amountLength = $this->lengthPrefix((string)$amountCents);
        $merchantIdLength = $this->lengthPrefix((string)$payload['merchant_id']);
        $merchantNameLength = $this->lengthPrefix((string)$payload['merchant_name']);
        $currencyLength = $this->lengthPrefix((string)$payload['currency']);
        $detailsLength = $this->lengthPrefix((string)$payload['details1']);
        $detailsLength2 = $this->lengthPrefix((string)$payload['details2']);
        $paymentOkUrlLength = $this->lengthPrefix((string)$payload['success_url']);
        $paymentFailUrlLength = $this->lengthPrefix((string)$payload['fail_url']);
        $firstNameLength = $this->lengthPrefix((string)$payload['first_name']);
        $lastNameLength = $this->lengthPrefix((string)$payload['last_name']);
        $addressLength = $this->lengthPrefix((string)$payload['address']);
        $cityLength = $this->lengthPrefix((string)$payload['city']);
        $zipLength = $this->lengthPrefix((string)$payload['zip']);
        $countryLength = $this->lengthPrefix((string)$payload['country']);
        $phoneLength = $this->lengthPrefix((string)$payload['phone']);
        $emailLength = $this->lengthPrefix((string)$payload['email']);

        $header = '16AmountToPay,PayToMerchant,MerchantName,AmountCurrency,Details1,Details2,PaymentOKURL,PaymentFailURL,FirstName,LastName,Address,City,Zip,Country,Telephone,Email,'
            . $amountLength . $merchantIdLength . $merchantNameLength . $currencyLength . $detailsLength . $detailsLength2
            . $paymentOkUrlLength . $paymentFailUrlLength . $firstNameLength . $lastNameLength . $addressLength
            . $cityLength . $zipLength . $countryLength . $phoneLength . $emailLength;

        $data = $header
            . $amountCents
            . $payload['merchant_id']
            . $payload['merchant_name']
            . $payload['currency']
            . $payload['details1']
            . $payload['details2']
            . $payload['success_url']
            . $payload['fail_url']
            . $payload['first_name']
            . $payload['last_name']
            . $payload['address']
            . $payload['city']
            . $payload['zip']
            . $payload['country']
            . $payload['phone']
            . $payload['email'];

        $checksum = hash_hmac('sha256', $data, (string)config('casys.password'));

        return [
            'checksum' => $checksum,
            'header' => $header,
        ];
    }

    private function lengthPrefix(string $value): string
    {
        return Str::padLeft((string)Str::length($value, 'UTF-8'), 3, '0');
    }
}
