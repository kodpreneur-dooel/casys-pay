<?php

namespace Codepreneur\CasysPay\Tests;

use Codepreneur\CasysPay\Contracts\CasysClientInterface;
use Codepreneur\CasysPay\Contracts\PayloadBuilderInterface;

class CasysServiceProviderTest extends TestCase
{
    public function test_it_registers_the_package_services(): void
    {
        $this->assertInstanceOf(CasysClientInterface::class, $this->app->make(CasysClientInterface::class));
        $this->assertInstanceOf(PayloadBuilderInterface::class, $this->app->make(PayloadBuilderInterface::class));
    }

    public function test_it_registers_the_package_routes(): void
    {
        $this->assertTrue($this->app['router']->has('casys.success'));
        $this->assertTrue($this->app['router']->has('casys.fail'));
    }

    public function test_it_can_build_a_payload(): void
    {
        $payload = $this->app->make(CasysClientInterface::class)->buildPayload([
            'amount' => 12,
            'details1' => 'ORDER-1',
            'details2' => 'SESSION-1',
            'customer' => [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane@example.com',
                'phone' => '+38970111222',
            ],
        ]);

        $this->assertSame(1200, $payload->get('AmountToPay'));
        $this->assertSame('merchant-id', $payload->get('PayToMerchant'));
        $this->assertSame('http://localhost/casys/success', $payload->get('PaymentOKURL'));
        $this->assertSame('true', $payload->get('isSimple'));
        $this->assertNotEmpty($payload->get('CheckSumHeader'));
        $this->assertNotEmpty($payload->get('CheckSum'));
    }
}
