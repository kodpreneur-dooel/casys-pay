<?php

namespace Codepreneur\CasysPay\Payload;

use Codepreneur\CasysPay\Contracts\PayloadBuilderInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

readonly class PayloadBuilder implements PayloadBuilderInterface
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
    public function build(array $payload): PaymentPayload
    {
        $merchantId = (string) config('casys.merchant_id');
        $merchantName = (string) config('casys.merchant_name');
        $currency = (string) config('casys.currency', 'MKD');

        $fields = [
            'AmountToPay' => (string) (((int) Arr::get($payload, 'amount')) * 100),
            'PayToMerchant' => $merchantId,
            'MerchantName' => $merchantName,
            'AmountCurrency' => $currency,
            'Details1' => $this->normalizeVisibleText((string) Arr::get($payload, 'details1', ''), 32),
            'Details2' => Str::substr((string) Arr::get($payload, 'details2', (string) time()), 0, 10),
            'PaymentOKURL' => (string) Arr::get($payload, 'success_url', url(config('casys.routes.success'))),
            'PaymentFailURL' => (string) Arr::get($payload, 'fail_url', url(config('casys.routes.fail'))),
        ];

        $customer = (array) Arr::get($payload, 'customer', []);

        $optionalFields = [
            'FirstName' => $this->normalizeAlphaNumeric((string) Arr::get($customer, 'first_name', ''), 64, false),
            'LastName' => $this->normalizeAlphaNumeric((string) Arr::get($customer, 'last_name', ''), 64, false),
            'Address' => $this->normalizeVisibleText((string) Arr::get($customer, 'address', ''), 50),
            'City' => $this->normalizeAlphaNumeric((string) Arr::get($customer, 'city', ''), 50, false),
            'Zip' => $this->normalizeDigits((string) Arr::get($customer, 'zip', ''), 16),
            'Country' => $this->normalizeCountryCode((string) Arr::get($customer, 'country', '')),
            'Telephone' => $this->normalizeDigits((string) Arr::get($customer, 'phone', ''), 15),
            'Email' => $this->normalizeEmail((string) Arr::get($customer, 'email', '')),
        ];

        foreach ($optionalFields as $field => $value) {
            if ($value !== '') {
                $fields[$field] = $value;
            }
        }

        $header = str_pad((string) count($fields), 2, '0', STR_PAD_LEFT)
            .implode(',', array_keys($fields)).','
            .collect(array_values($fields))
                ->map(fn (string $value): string => str_pad((string) Str::length($value, 'UTF-8'), 3, '0', STR_PAD_LEFT))
                ->implode('');

        $checksum = hash_hmac(
            'sha256',
            $header.implode('', array_values($fields)),
            (string) config('casys.password'),
        );

        return new PaymentPayload([
            'isSimple' => Str::lower((string) Arr::get($payload, 'is_simple', 'true')),
            ...$fields,
            'CheckSumHeader' => $header,
            'CheckSum' => $checksum,
        ]);
    }

    private function normalizeAlphaNumeric(string $value, int $maxLength, bool $allowSpaces): string
    {
        $normalized = Str::ascii(trim($value));
        $normalized = preg_replace('/\s+/', $allowSpaces ? ' ' : '', $normalized) ?? '';
        $normalized = preg_replace($allowSpaces ? '/[^A-Za-z0-9 ]/' : '/[^A-Za-z0-9]/', '', $normalized) ?? '';

        return Str::substr(trim($normalized), 0, $maxLength);
    }

    private function normalizeVisibleText(string $value, int $maxLength): string
    {
        $normalized = Str::ascii(trim($value));
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? '';
        $normalized = preg_replace('/[^A-Za-z0-9 @\\-\\/\\?=&]/', '', $normalized) ?? '';

        return Str::substr(trim($normalized), 0, $maxLength);
    }

    private function normalizeDigits(string $value, int $maxLength): string
    {
        $normalized = preg_replace('/\D+/', '', $value) ?? '';

        return Str::substr($normalized, 0, $maxLength);
    }

    private function normalizeCountryCode(string $value): string
    {
        $normalized = $this->normalizeDigits($value, 3);

        return strlen($normalized) === 3 ? $normalized : '';
    }

    private function normalizeEmail(string $value): string
    {
        $email = trim($value);

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return '';
        }

        return Str::substr($email, 0, 256);
    }
}
