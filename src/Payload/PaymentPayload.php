<?php

namespace Codepreneur\CasysPay\Payload;

class PaymentPayload
{
    /** @var array<string, mixed> */
    private array $attributes;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }
}
