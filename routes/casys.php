<?php

use Codepreneur\CasysPay\Http\Controllers\FailController;
use Codepreneur\CasysPay\Http\Controllers\SuccessController;
use Illuminate\Support\Facades\Route;

Route::post(config('casys.routes.success', '/casys/success'), SuccessController::class)
    ->name('casys.success');

Route::post(config('casys.routes.fail', '/casys/fail'), FailController::class)
    ->name('casys.fail');
