<?php

namespace Codepreneur\CasysPay\Tests;

use Codepreneur\CasysPay\CasysServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CasysServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.url', 'http://localhost');
        $app['config']->set('casys.merchant_id', 'merchant-id');
        $app['config']->set('casys.merchant_name', 'Merchant Name');
        $app['config']->set('casys.currency', 'MKD');
        $app['config']->set('casys.password', 'secret');
        $app['config']->set('casys.routes.success', '/casys/success');
        $app['config']->set('casys.routes.fail', '/casys/fail');
    }
}
