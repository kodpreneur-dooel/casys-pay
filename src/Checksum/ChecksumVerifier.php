<?php

namespace Codepreneur\CasysPay\Checksum;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChecksumVerifier
{
    public function valid(Request $request): bool
    {
        $checksumHeader = (string) $request->input('ReturnCheckSumHeader');

        if ($checksumHeader === '') {
            return false;
        }

        $checksumData = $checksumHeader;

        foreach ($this->fieldsFromHeader($checksumHeader) as $field) {
            $checksumData .= (string) $request->input($field, '');
        }

        $checksum = hash_hmac('sha256', $checksumData, (string) config('casys.password'));

        return Str::upper($checksum) === Str::upper((string) $request->input('ReturnCheckSum'));
    }

    /**
     * @return array<int, string>
     */
    private function fieldsFromHeader(string $checksumHeader): array
    {
        $fieldsWithTrailingComma = preg_replace('/^\d{2}/', '', $checksumHeader);
        $fieldsWithTrailingComma = preg_replace('/\d+$/', '', (string) $fieldsWithTrailingComma);

        return array_values(array_filter(explode(',', (string) $fieldsWithTrailingComma)));
    }
}
