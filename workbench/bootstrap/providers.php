<?php

use Codepreneur\CasysPay\CasysServiceProvider;
use Laravel\Boost\BoostServiceProvider;
use Laravel\Mcp\Server\McpServiceProvider;
use Workbench\App\Providers\WorkbenchServiceProvider;

return [
    CasysServiceProvider::class,
    BoostServiceProvider::class,
    McpServiceProvider::class,
    WorkbenchServiceProvider::class,
];
