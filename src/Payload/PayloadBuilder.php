<?php

namespace Codepreneur\CasysPay\Payload;

use Codepreneur\CasysPay\Checksum\ChecksumCalculator;
use Codepreneur\CasysPay\Contracts\PayloadBuilderInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

readonly class PayloadBuilder implements PayloadBuilderInterface
{
    public function __construct(private ChecksumCalculator $checksumCalculator) {}

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
    public function build(array $payload): PaymentPayload
    {
        $merchantId = (string) config('casys.merchant_id');
        $merchantName = (string) config('casys.merchant_name');
        $currency = (string) config('casys.currency', 'MKD');

        $amount = (int) Arr::get($payload, 'amount');
        $details1 = (string) Arr::get($payload, 'details1', '');
        $details2 = (string) Arr::get($payload, 'details2', (string) time());
        $successUrl = (string) Arr::get($payload, 'success_url', url(config('casys.routes.success')));
        $failUrl = (string) Arr::get($payload, 'fail_url', url(config('casys.routes.fail')));

        $customer = (array) Arr::get($payload, 'customer', []);
        $firstName = (string) Arr::get($customer, 'first_name', '');
        $lastName = (string) Arr::get($customer, 'last_name', '');
        $address = (string) Arr::get($customer, 'address', '');
        $city = (string) Arr::get($customer, 'city', '');
        $zip = (string) Arr::get($customer, 'zip', '');
        $country = (string) Arr::get($customer, 'country', '');
        $phone = (string) Arr::get($customer, 'phone', '');
        $email = (string) Arr::get($customer, 'email', '');

        $checksum = $this->checksumCalculator->make([
            'amount' => $amount,
            'merchant_id' => $merchantId,
            'merchant_name' => $merchantName,
            'currency' => $currency,
            'details1' => $details1,
            'details2' => $details2,
            'success_url' => $successUrl,
            'fail_url' => $failUrl,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'address' => $address,
            'city' => $city,
            'zip' => $zip,
            'country' => $country,
            'phone' => $phone,
            'email' => $email,
        ]);

        return new PaymentPayload([
            'AmountToPay' => $amount * 100,
            'PayToMerchant' => $merchantId,
            'MerchantName' => $merchantName,
            'AmountCurrency' => $currency,
            'Details1' => $details1,
            'Details2' => $details2,
            'PaymentOKURL' => $successUrl,
            'PaymentFailURL' => $failUrl,
            'CheckSumHeader' => $checksum['header'],
            'CheckSum' => $checksum['checksum'],
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'Address' => $address,
            'City' => $city,
            'Zip' => $zip,
            'Country' => $country,
            'Telephone' => $phone,
            'Email' => $email,
            'isSimple' => Str::lower((string) Arr::get($payload, 'is_simple', 'true')),
        ]);
    }
}
