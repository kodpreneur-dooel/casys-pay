<?php

namespace Codepreneur\CasysPay;

use Codepreneur\CasysPay\Contracts\CasysClientInterface;
use Codepreneur\CasysPay\Contracts\PayloadBuilderInterface;
use Codepreneur\CasysPay\Payload\PayloadBuilder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CasysServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('casys')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('casys');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(CasysClientInterface::class, CasysClient::class);
        $this->app->bind(PayloadBuilderInterface::class, PayloadBuilder::class);
    }
}
