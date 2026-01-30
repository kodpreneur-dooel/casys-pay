<?php

namespace Codepreneur\CasysPay;

use Codepreneur\CasysPay\Contracts\CasysClientInterface;
use Codepreneur\CasysPay\Contracts\PayloadBuilderInterface;
use Codepreneur\CasysPay\Payload\PayloadBuilder;
use Illuminate\Support\ServiceProvider;

class CasysServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/casys.php', 'casys');

        $this->app->bind(CasysClientInterface::class, CasysClient::class);
        $this->app->bind(PayloadBuilderInterface::class, PayloadBuilder::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/casys.php' => config_path('casys.php'),
        ], 'casys-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/casys'),
        ], 'casys-views');

        $this->loadRoutesFrom(__DIR__.'/../routes/casys.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'casys');
    }
}
